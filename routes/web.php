<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Admin\CarouselController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\CustomerAuth\LoginController as CustomerLoginController;
use App\Http\Controllers\CustomerAuth\RegisterController as CustomerRegisterController;
use App\Http\Controllers\CustomerAuth\ReturnController as CustomerReturnController;
use App\Http\Controllers\Admin\ReturnController as AdminReturnController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Client\ReviewController as ClientReviewController;
use App\Models\Visit;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Customer Authentication Routes
Route::prefix('customer')->group(function() {
    Route::get('/login', [CustomerLoginController::class, 'showLoginForm'])->name('customer.login');
    Route::post('/login', [CustomerLoginController::class, 'login'])->name('customer.login.post');
    Route::post('/logout', [CustomerLoginController::class, 'logout'])->name('customer.logout');
    Route::get('/register', [CustomerRegisterController::class, 'showRegistrationForm'])->name('customer.register');
    Route::post('/register', [CustomerRegisterController::class, 'register'])->name('customer.register.post');

    // Returns / Warranty (guest can submit, but my-returns requires login)
    Route::get('/return', [CustomerReturnController::class, 'showForm'])->name('customer.return.form');
    Route::post('/return', [CustomerReturnController::class, 'submit'])->name('customer.return.submit');
    Route::get('/my-returns', [CustomerReturnController::class, 'myReturns'])->name('customer.my-returns')->middleware('auth:customer');
});
// Client


Route::post('/track-time', function(Request $request) {
    // Validar los datos recibidos
    $validated = $request->validate([
        'time_spent' => 'required|integer|min:1',
        'page_url' => 'sometimes|string'
    ]);
    
    // Obtener la IP del usuario
    $ip = $request->ip();
    
    // Buscar la última visita de esta IP
    $visit = Visit::where('ip_address', $ip)
                ->latest()
                ->first();
    
    // Actualizar el tiempo si existe el registro
    if ($visit) {
        $visit->update([
            'time_spent' => $validated['time_spent'],
            'exit_url' => $validated['page_url'] ?? null
        ]);
    }
    
    return response()->noContent();
})->middleware('web');


Route::post('/review/store', [ClientReviewController::class, 'store'])->name('review.store')->middleware('auth:customer');
Route::post('/review/update/{id}', [ClientReviewController::class, 'update'])->name('review.update')->middleware('auth:customer');
Route::post('/review/delete/{id}', [ClientReviewController::class, 'destroy'])->name('review.destroy')->middleware('auth:customer');

Route::controller(ClientController::class)->group(function(){
    Route::get('/', 'index')->name('clientHome');
    Route::get('/products', 'products')->name('clientProducts');
    Route::get('/products-search', 'searchProduct')->name('clientProductSearch');
    Route::get('/category', 'category')->name('clientCategory');
    Route::get('/category/{category}', 'categoryProducts')->name('clientCategoryProducts');
    Route::get('/product/{product}', 'productDetail')->name('clientProductDetail');
    Route::get('/product/{product}', 'productDetail')->name('clientProductDetail');
    Route::get('/carts', 'carts')->name('clientCarts');
    Route::post('/add-to-cart', 'addToCart')->name('clientAddToCart');
    Route::post('/update-cart', 'updateCart')->name('clientUpdateCart');
    Route::post('/delete-cart', 'deleteCart')->name('clientDeleteCart');
    Route::get('/checkout', 'checkout')->name('clientCheckout');
    Route::post('/checkout-save', 'checkoutSave')->name('clientCheckoutSave');
    Route::get('/success/{order_code}', 'successOrder')->name('clientOrderCode');
    Route::get('/check-order', 'checkOrder')->name('clientCheckOrder');
    Route::post('/check-order-status', 'checkOrderStatus')->name('clientCheckOrderStatus');
    Route::get('/about', 'about')->name('clientAbout');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/client/contactForm', 'contactForm')->name('clientContactForm');
    Route::get('/profile', 'profile')->name('customer.profile')->middleware('auth:customer');
    Route::post('/profile-update', 'profileUpdate')->name('customer.profile.update')->middleware('auth:customer');
    Route::get('/my-orders', 'myOrders')->name('clientMyOrders')->middleware('auth:customer');
});


Route::controller(CartController::class)->group(function(){
    Route::get('/carts', 'carts')->name('clientCarts');
    Route::post('/add-to-cart', 'addToCart')->name('clientAddToCart');
    Route::post('/update-cart', 'updateCart')->name('clientUpdateCart');
    Route::post('/delete-cart', 'deleteCart')->name('clientDeleteCart');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware(['auth'])->group(function () {
    // Shop
    Route::controller(ShopController::class)->group(function() {
        Route::post('/shop/create', 'create')->name('shopCreate');
        Route::get('/shop/detail', 'detail')->name('shopDetail');
        Route::post('/shop/update', 'update')->name('shopUpdate');
        Route::post('/shop/update-password', 'updatePassword')->name('shopUpdatePassword');
    });

    // Category
    Route::controller(CategoryController::class)->group(function() {
        Route::get('/admin/category', 'index')->name('category');
        Route::get('/admin/category/create', 'create')->name('categoryCreate');
        Route::post('/admin/category/check', 'check')->name('categoryCheck');
        Route::post('/admin/category/save', 'save')->name('categorySave');
        Route::get('/admin/category/delete/{id}/{path}', 'delete')->name('categoryDelete');
    });
    // Carousel
    Route::controller(CarouselController::class)->group(function () {
        Route::get('/admin/carousel', 'index')->name('carousel');
        Route::get('/admin/carousel/create', 'create')->name('carouselCreate');
        Route::post('/admin/carousel/check', 'check')->name('carouselCheck');
        Route::post('/admin/carousel/save', 'save')->name('carouselSave');
        Route::get('/admin/carousel/delete/{id}', 'delete')->name('carouselDelete');
    });

        // Contact
    Route::controller(ContactController::class)->group(function () {
        Route::get('/admin/contactForm', 'index')->name('contactForm');
    });
    

    // Product
    Route::controller(ProductController::class)->group(function() {
        Route::get('/admin/products', 'index')->name('products');
        Route::get('/admin/product/create', 'create')->name('productCreate');
        Route::post('/admin/product/check', 'check')->name('productCheck');
        Route::post('/admin/product/save', 'save')->name('producSave');
        Route::post('/admin/product/images/', 'getImages')->name('productGetImages');
        Route::get('/admin/product/images/{product}', 'addImages')->name('productAddImages');
        Route::post('/admin/product/images/save', 'addImagesSave')->name('productAddImagesSave');
        Route::post('/admin/product/images/delete', 'deleteImages')->name('productDeleteImages');
        Route::get('/admin/product/edit/{product}', 'edit')->name('productEdit');
        Route::post('/admin/product/edit/{product}/{id}/save', 'editSave')->name('productEditSave');
        Route::get('/admin/product/delete/{id}', 'delete')->name('productDelete');
    });

    // Orders
    Route::controller(OrderController::class)->group(function() {
        Route::get('/admin/orders', 'index')->name('orders');
        Route::get('/admin/order/{order_code}', 'detail')->name('orderDetail');
        Route::post('/admin/order/update-status/{order_code}', 'updateStatus')->name('orderUpdateStatus');
        Route::get('/admin/order/delete/{order_code}', 'delete')->name('orderDelete');
    });

    // Returns / Warranty (Admin)
    Route::controller(AdminReturnController::class)->group(function() {
        Route::get('/admin/returns', 'index')->name('admin.returns');
        Route::get('/admin/return/{id}', 'detail')->name('admin.returns.detail');
        Route::post('/admin/return/{id}/status', 'updateStatus')->name('admin.returns.update-status');
    });

    // Reviews (Admin)
    Route::controller(AdminReviewController::class)->group(function() {
        Route::get('/admin/reviews', 'index')->name('admin.reviews');
        Route::get('/admin/review/{id}/approve', 'approve')->name('admin.reviews.approve');
        Route::get('/admin/review/{id}/reject', 'reject')->name('admin.reviews.reject');
        Route::get('/admin/review/{id}/delete', 'destroy')->name('admin.reviews.destroy');
    });
});
