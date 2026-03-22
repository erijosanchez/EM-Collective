<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\CategoryController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\AccountController;
use App\Http\Controllers\Shop\NewsletterController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\SitemapController;

// ════════════════════════════════════════════════════════
// SEO
// ════════════════════════════════════════════════════════

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// ════════════════════════════════════════════════════════
// TIENDA PÚBLICA
// ════════════════════════════════════════════════════════

Route::get('/', [HomeController::class, 'index'])->name('home');

// Catálogo
Route::get('/buscar', [ProductController::class, 'search'])->name('product.search');
Route::get('/producto/{product:slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/categoria/{category:slug}', [CategoryController::class, 'show'])->name('category.show');

// ════════════════════════════════════════════════════════
// CARRITO
// ════════════════════════════════════════════════════════

Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar', [CartController::class, 'add'])->name('cart.add');
Route::patch('/carrito/{itemId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{itemId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/carrito/cupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');
Route::delete('/carrito/cupon/remover', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// ════════════════════════════════════════════════════════
// CHECKOUT
// ════════════════════════════════════════════════════════

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/exito/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/fallido/{order}', [CheckoutController::class, 'failed'])->name('checkout.failed');
Route::get('/checkout/pendiente/{order}', [CheckoutController::class, 'pending'])->name('checkout.pending');

// Webhook Mercado Pago — excluido del CSRF en VerifyCsrfToken
Route::post('/webhook/mercadopago', [MercadoPagoController::class, 'webhook'])
    ->name('webhook.mercadopago');

// ════════════════════════════════════════════════════════
// NEWSLETTER
// ════════════════════════════════════════════════════════

Route::post('/newsletter/suscribir', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/desuscribir/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::get('/track/email/{token}', [NewsletterController::class, 'trackOpen'])->name('newsletter.track');

// ════════════════════════════════════════════════════════
// CUENTA DEL CLIENTE — requiere autenticación
// ════════════════════════════════════════════════════════

Route::prefix('mi-cuenta')->middleware('auth')->name('account.')->group(function () {

    Route::get('/',                             [AccountController::class, 'index'])->name('index');
    Route::get('/perfil',                       [AccountController::class, 'profile'])->name('profile');
    Route::put('/perfil',                       [AccountController::class, 'profileUpdate'])->name('profile.update');
    Route::put('/perfil/contrasena',            [AccountController::class, 'passwordUpdate'])->name('password.update');

    // Pedidos
    Route::get('/pedidos',                      [AccountController::class, 'orders'])->name('orders');
    Route::get('/pedidos/{order}',              [AccountController::class, 'orderShow'])->name('orders.show');

    // Wishlist
    Route::get('/wishlist',                     [AccountController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/{productId}',        [AccountController::class, 'wishlistToggle'])->name('wishlist.toggle');

    // Direcciones
    Route::get('/direcciones',                  [AccountController::class, 'addresses'])->name('addresses');
    Route::post('/direcciones',                 [AccountController::class, 'addressStore'])->name('addresses.store');
    Route::delete('/direcciones/{address}',     [AccountController::class, 'addressDestroy'])->name('addresses.destroy');
    Route::patch('/direcciones/{address}/default', [AccountController::class, 'addressSetDefault'])->name('addresses.default');
});

// ════════════════════════════════════════════════════════
// PANEL ADMIN — requiere auth + rol admin
// ════════════════════════════════════════════════════════

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Pedidos
        Route::get('/pedidos',              [Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/pedidos/{order}',      [Admin\OrderController::class, 'show'])->name('orders.show');
        Route::put('/pedidos/{order}',      [Admin\OrderController::class, 'update'])->name('orders.update');

        // Productos
        Route::resource('productos', Admin\ProductController::class)
            ->parameters(['productos' => 'product'])
            ->names([
                'index'   => 'products.index',
                'create'  => 'products.create',
                'store'   => 'products.store',
                'edit'    => 'products.edit',
                'update'  => 'products.update',
                'destroy' => 'products.destroy',
            ]);

        // Categorías
        Route::resource('categorias', Admin\CategoryController::class)
            ->parameters(['categorias' => 'category'])
            ->names([
                'index'   => 'categories.index',
                'create'  => 'categories.create',
                'store'   => 'categories.store',
                'edit'    => 'categories.edit',
                'update'  => 'categories.update',
                'destroy' => 'categories.destroy',
            ]);

        // Cupones
        Route::resource('cupones', Admin\CouponController::class)
            ->parameters(['cupones' => 'coupon'])
            ->names([
                'index'   => 'coupons.index',
                'create'  => 'coupons.create',
                'store'   => 'coupons.store',
                'edit'    => 'coupons.edit',
                'update'  => 'coupons.update',
                'destroy' => 'coupons.destroy',
            ]);

        // Banners
        Route::resource('banners', Admin\BannerController::class)
            ->names([
                'index'   => 'banners.index',
                'create'  => 'banners.create',
                'store'   => 'banners.store',
                'edit'    => 'banners.edit',
                'update'  => 'banners.update',
                'destroy' => 'banners.destroy',
            ]);

        // Campañas
        Route::resource('campanas', Admin\CampaignController::class)
            ->parameters(['campanas' => 'campaign'])
            ->names([
                'index'   => 'campaigns.index',
                'create'  => 'campaigns.create',
                'store'   => 'campaigns.store',
                'edit'    => 'campaigns.edit',
                'update'  => 'campaigns.update',
                'destroy' => 'campaigns.destroy',
            ]);
        Route::post('/campanas/{campaign}/enviar', [Admin\CampaignController::class, 'send'])
            ->name('campaigns.send');

        // Ajustes generales
        Route::get('/ajustes',  [Admin\SettingsController::class, 'index'])->name('settings');
        Route::put('/ajustes',  [Admin\SettingsController::class, 'update'])->name('settings.update');
    });

require __DIR__ . '/auth.php';
