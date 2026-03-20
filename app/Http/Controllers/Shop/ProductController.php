<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        if (!$product->is_active) abort(404);

        $product->load([
            'category.parent',
            'brand',
            'images',
            'variants.size',
            'variants.color',
            'approvedReviews.user',
        ]);

        // Productos relacionados (misma categoría, diferente producto)
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['images'])
            ->inStock()
            ->take(4)
            ->get();

        return view('shop.product', compact('product', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        $q = $request->get('q', '');

        $query = Product::active()->with(['category', 'brand', 'images']);

        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhereHas('brand',    fn($bq) => $bq->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', "%{$q}%"));
            });
        }

        // Filtros opcionales en búsqueda
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->on_sale) {
            $query->onSale();
        }

        match ($request->get('sort', 'relevance')) {
            'price_asc'  => $query->orderByRaw('COALESCE(sale_price, base_price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, base_price) DESC'),
            'newest'     => $query->latest(),
            default      => $query->orderBy('is_featured', 'desc')->latest(),
        };

        $products   = $query->paginate(24);
        $categories = Category::active()->parents()->ordered()->get();

        return view('shop.search', compact('products', 'q', 'categories'));
    }
}
