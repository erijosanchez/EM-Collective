<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;


class CategoryController extends Controller
{
    public function show(Request $request, Category $category)
    {
        // Si es una subcategoría la mostramos directo
        // Si es una categoría padre, mostramos sus productos Y los de sus hijos
        $categoryIds = $category->allDescendantIds();

        $activeChild = null;
        if ($category->children->count() && $request->has('sub')) {
            $activeChild = $category->children->firstWhere('slug', $request->sub);
            if ($activeChild) $categoryIds = [$activeChild->id];
        }

        $query = Product::active()
            ->whereIn('category_id', $categoryIds)
            ->with(['category', 'brand', 'images', 'variants.size', 'variants.color']);

        // ── Filtros ──────────────────────────────────────────────────────
        if ($request->filled('price_min')) {
            $query->where(function ($q) use ($request) {
                $q->where('sale_price', '>=', $request->price_min)
                    ->orWhere(function ($q2) use ($request) {
                        $q2->whereNull('sale_price')->where('base_price', '>=', $request->price_min);
                    });
            });
        }

        if ($request->filled('price_max')) {
            $query->where(function ($q) use ($request) {
                $q->where('sale_price', '<=', $request->price_max)
                    ->orWhere(function ($q2) use ($request) {
                        $q2->whereNull('sale_price')->where('base_price', '<=', $request->price_max);
                    });
            });
        }

        if ($request->filled('sizes')) {
            $query->whereHas(
                'variants',
                fn($q) =>
                $q->whereIn('size_id', $request->sizes)->where('stock', '>', 0)
            );
        }

        if ($request->filled('colors')) {
            $query->whereHas(
                'variants',
                fn($q) =>
                $q->whereIn('color_id', $request->colors)->where('stock', '>', 0)
            );
        }

        if ($request->on_sale) {
            $query->onSale();
        }

        // ── Ordenamiento ─────────────────────────────────────────────────
        match ($request->get('sort', 'relevance')) {
            'price_asc'  => $query->orderByRaw('COALESCE(sale_price, base_price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, base_price) DESC'),
            'newest'     => $query->latest(),
            default      => $query->orderBy('sort_order')->orderBy('is_featured', 'desc'),
        };

        $products = $query->paginate(
            (int) \App\Models\Setting::get('products_per_page', 24)
        );

        // Tallas y colores disponibles en esta categoría para los filtros
        $sizes = Size::whereHas(
            'variants.product',
            fn($q) =>
            $q->active()->whereIn('category_id', $categoryIds)
        )->orderBy('sort_order')->get();

        $colors = Color::whereHas(
            'variants.product',
            fn($q) =>
            $q->active()->whereIn('category_id', $categoryIds)
        )->orderBy('name')->get();

        return view('shop.category', compact(
            'category',
            'products',
            'sizes',
            'colors',
            'activeChild',
        ));
    }
}
