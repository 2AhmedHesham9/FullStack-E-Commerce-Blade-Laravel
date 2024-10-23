<?php

use GuzzleHttp\Middleware;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CoupnController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WishlistController;

// Route::get('/', function () {
//     return view('welcome');
// });
route::get('/facebook/login', [UserController::class, 'redirectToFacebook'])->name('facebook.login');;
route::get('/auth/facebook/callback', [UserController::class, 'handleFacebookCallback']);

route::get('/google/login', [UserController::class, 'redirectToGoogle'])->name('google.login');;
route::get('/auth/google/callback', [UserController::class, 'handleGoogleCallback']);

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');
Route::get('/about', [HomeController::class, 'about'])->name('home.about');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{slug_product}/details', [ShopController::class, 'productDetails'])->name('product.details');

Route::get('/search', [HomeController::class, 'search'])->name('home.search');



// user
Route::middleware(['auth'])->group(function () {

    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    //    ORDER
    Route::controller(OrderController::class)->group(function () {
        Route::POST('/place-an-order', 'store')->name('cart.place.an.order');
        Route::get('/order-confirmation', 'orderConfirmation')->name('cart.order.confirmation');
        Route::get('/user-orders', 'userOrders')->name('user.orders');
        Route::get('/user-order-details/{id}', 'userOrderDetails')->name('user.order.details');
        Route::put('/user/cancel-order', 'usercancleorder')->name('user.cancle.order');
    });


    // CART
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/increase-quantity/{rowId}', [CartController::class, 'increaseCartQuantity'])->name('cart.qty.increase');
    Route::put('/cart/decrease-quantity/{rowId}', [CartController::class, 'decreaseCartQuantity'])->name('cart.qty.decrease');
    Route::delete('/cart/remove/{rowId}', [CartController::class, 'removeItem'])->name('cart.item.remove');
    Route::delete('/cart/clear', [CartController::class, 'emptyCart'])->name('cart.empty');
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCouponCode'])->name('cart.coupon.apply');


    // wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{rowId}', [WishlistController::class, 'removeItem'])->name('wishlist.item.remove');
    Route::delete('/wishlist/clear', [WishlistController::class, 'emptyWishlist'])->name('wishlist.empty');
    Route::post('/wishlist/move/{rowId}', [WishlistController::class, 'copyToCart'])->name('wishlist.move');


    // contact
    Route::controller(ContactController::class)->group(function () {
        route::get('/contacts', 'index')->name('admin.contacts');
        route::POST('/contact/store', 'store')->name('home.contact.store');
        Route::Delete('/contacts/{contact}',  'destroy')->name('admin.contact.destroy');
    });
});

// Admin + BRAND + Category + Product + Coupns + Orders + Slides
Route::prefix('admin')->middleware(['auth', AuthAdmin::class])->group(function () {
    // Admin
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/users', [AdminController::class, 'Show_users_dashboard'])->name('admin.show.users');
    Route::get('/user/setting', [AdminController::class, 'getUserData'])->name('admin.show.setting');

    // BRAND
    Route::get('/brands', [BrandController::class, 'index'])->name('admin.brands');
    Route::get('/brand/add', [BrandController::class, 'create'])->name('admin.brand.add');
    Route::post('/brand/store', [BrandController::class, 'store'])->name('admin.brand.store');
    // Route::get('/brand/show/{id}', [BrandController::class, 'show'])->name('admin.brand.show');
    Route::get('/brand/edit/{id}', [BrandController::class, 'edit'])->name('admin.brand.edit');
    Route::put('/brand/update', [BrandController::class, 'update'])->name('admin.brand.update');
    // Route::post('/brand/delete/{id}', [BrandController::class, 'destroy'])->name('admin.brand.destroy');
    Route::delete('/brand/delete/{id}', [BrandController::class, 'destroy'])->name('admin.brand.destroy');

    // Category
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::get('/category/add', [CategoryController::class, 'create'])->name('admin.category.add');
    Route::post('/category/store', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('admin.category.edit');
    Route::put('/category/update', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('/category/delete/{id}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');

    // Product
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products');
    Route::get('/product/add', [ProductController::class, 'create'])->name('admin.product.add');
    Route::post('/product/store', [ProductController::class, 'store'])->name('admin.product.store');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('admin.product.edit');
    Route::put('/product/update', [ProductController::class, 'update'])->name('admin.product.update');
    Route::delete('/product/delete/{id}', [ProductController::class, 'destroy'])->name('admin.product.destroy');

    // Coupns
    Route::get('/coupns', [CouponController::class, 'index'])->name('admin.coupons');
    Route::get('/coupon/add', [CouponController::class, 'create'])->name('admin.coupon.add');
    Route::post('/coupon/store', [CouponController::class, 'store'])->name('admin.coupon.store');
    Route::get('/coupon/edit/{id}', [CouponController::class, 'edit'])->name('admin.coupon.edit');
    Route::put('/coupon/update', [CouponController::class, 'update'])->name('admin.coupon.update');
    Route::delete('/coupon/delete/{id}', [CouponController::class, 'destroy'])->name('admin.coupon.destroy');

    // Orders
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index')->name('admin.orders');
        Route::get('/{id}/order-details', 'adminOrderDetails')->name('admin.orders.details');
        Route::put('/order/update-status', 'updateOrderStatus')->name('admin.order.status.update');
    });

    // Slides
    route::controller(SlideController::class)->group(function () {

        Route::get('/slides', 'index')->name('admin.slides');
        Route::get('/create-slide', 'create')->name('admin.slide.add');
        Route::POST('/slide/store', 'store')->name('admin.slide.store');
        Route::get('/slide/{id}/edit', 'edit')->name('admin.slide.edit');
        Route::PUT('/slide/update', 'update')->name('admin.slide.update');
        Route::delete('/slide/{id}/delete', 'destroy')->name('admin.slide.destroy');
    });
});
