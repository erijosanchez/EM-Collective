<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand'])
            ->withCount('variants');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products   = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::active()->parents()->with('children')->ordered()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->ordered()->get();
        $brands     = Brand::active()->orderBy('name')->get();
        $sizes      = Size::orderBy('sort_order')->get();
        $colors     = Color::orderBy('name')->get();

        return view('admin.products.form', compact('categories', 'brands', 'sizes', 'colors'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);

        return DB::transaction(function () use ($request, $data) {
            $product = Product::create($data);

            $this->handleImages($request, $product);
            $this->handleVariants($request, $product);

            return redirect()->route('admin.products.index')
                ->with('success', 'Producto creado correctamente.');
        });
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'variants.size', 'variants.color']);
        $categories = Category::active()->ordered()->get();
        $brands     = Brand::active()->orderBy('name')->get();
        $sizes      = Size::orderBy('sort_order')->get();
        $colors     = Color::orderBy('name')->get();

        return view('admin.products.form', compact('product', 'categories', 'brands', 'sizes', 'colors'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product->id);

        return DB::transaction(function () use ($request, $data, $product) {
            $product->update($data);

            $this->handleImages($request, $product);

            // Eliminar variantes marcadas para borrar
            if ($request->filled('delete_variants')) {
                ProductVariant::whereIn('id', $request->delete_variants)
                    ->where('product_id', $product->id)
                    ->delete();
            }

            $this->handleVariants($request, $product);

            return redirect()->route('admin.products.index')
                ->with('success', 'Producto actualizado correctamente.');
        });
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado.');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────

    protected function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        $rules = [
            'name'             => 'required|string|max:200',
            'category_id'      => 'required|exists:categories,id',
            'brand_id'         => 'nullable|exists:brands,id',
            'description'      => 'nullable|string',
            'details'          => 'nullable|string',
            'base_price'       => 'required|numeric|min:0',
            'sale_price'       => 'nullable|numeric|min:0|lt:base_price',
            'sku'              => 'nullable|string|max:100|unique:products,sku' . ($ignoreId ? ",{$ignoreId}" : ''),
            'gender'           => 'required|in:men,women,kids,unisex',
            'is_active'        => 'nullable|boolean',
            'is_featured'      => 'nullable|boolean',
            'meta_title'       => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:320',
            'meta_keywords'    => 'nullable|string|max:300',
        ];

        $validated = $request->validate($rules);
        $validated['is_active']   = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['slug']        = Str::slug($validated['name']);

        return $validated;
    }

    protected function handleImages(Request $request, Product $product): void
    {
        if (!$request->hasFile('images')) return;

        $currentCount = $product->images()->count();
        $allowed = 5 - $currentCount;

        foreach ($request->file('images') as $index => $file) {
            if ($index >= $allowed) break;

            $path = $file->store('products', 'public');

            $product->images()->create([
                'path'       => $path,
                'sort_order' => $currentCount + $index,
                'is_primary' => ($currentCount === 0 && $index === 0),
            ]);
        }
    }

    protected function handleVariants(Request $request, Product $product): void
    {
        if (!$request->filled('variants')) return;

        foreach ($request->variants as $variantData) {
            if (empty($variantData['size_id']) && empty($variantData['color_id'])) continue;

            $attributes = [
                'product_id' => $product->id,
                'size_id'    => $variantData['size_id'] ?? null,
                'color_id'   => $variantData['color_id'] ?? null,
            ];

            $values = [
                'sku'            => $variantData['sku'] ?? Str::upper(Str::random(8)),
                'stock'          => $variantData['stock'] ?? 0,
                'price_modifier' => $variantData['price_modifier'] ?? 0,
                'is_active'      => true,
            ];

            if (!empty($variantData['id'])) {
                ProductVariant::where('id', $variantData['id'])
                    ->where('product_id', $product->id)
                    ->update($values);
            } else {
                ProductVariant::firstOrCreate($attributes, $values);
            }
        }
    }
}
