<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\AdminMiddleware;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\MercadoPagoService;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrar servicios como singletons
        $this->app->singleton(CartService::class);
        $this->app->singleton(MercadoPagoService::class);
        $this->app->singleton(CheckoutService::class, function ($app) {
            return new CheckoutService($app->make(CartService::class));
        });
    }

    public function boot(): void
    {
        // Compartir datos globales con todas las vistas de la tienda
        View::composer(['layouts.shop', 'shop.*'], function ($view) {
            $cartService = app(CartService::class);
            $view->with('cartCount', $cartService->getItemCount());
        });

        // Compartir categorías principales en el nav
        View::composer('layouts.shop', function ($view) {
            $mainCategories = \App\Models\Category::active()
                ->parents()
                ->with('children')
                ->ordered()
                ->get();
            $view->with('navCategories', $mainCategories);
        });
    }
}
