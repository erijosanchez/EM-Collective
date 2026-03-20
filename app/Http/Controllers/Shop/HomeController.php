<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::active()->orderBy('sort_order')->get();

        $mainCategories = Category::active()
            ->parents()
            ->ordered()
            ->get();

        $featuredProducts = Product::active()
            ->featured()
            ->with(['category', 'brand', 'images'])
            ->inStock()
            ->take(8)
            ->get();

        $saleProducts = Product::active()
            ->onSale()
            ->with(['category', 'images'])
            ->inStock()
            ->take(8)
            ->get();

        return view('shop.home', compact(
            'banners',
            'mainCategories',
            'featuredProducts',
            'saleProducts',
        ));
    }
}
