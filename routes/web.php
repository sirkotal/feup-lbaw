<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Middleware\Authenticate;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::redirect('/', '/mainpage');

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/user', 'show')->name('user')->middleware('auth');
    Route::post('/user/edit_profile', 'edit')->name('edit_profile');
    Route::get('/user/edit_profile', 'showEdit')->name('show/edit_profile')->middleware('auth');
    Route::post('/user/edit_profile/edit_password', 'editPassword')->name('edit_password');
    Route::get('/user/admin', 'showAdmin')->name('admin_page')->middleware('auth');
    Route::put('/user/admin/delete_user', 'deleteUser')->name('delete_user');
    Route::post('/user/admin/block_user', 'blockUser')->name('block_user');
    Route::post('/user', 'editPhoto')->name('edit_photo');
});

Route::get('/product/{id}', [ProductController::class, 'show'])->name('showProductDetails');

Route::post('/product/{id}/add_to_shoppingcart', [CartController::class, 'addToShoppingCart'])->name('addToShoppingCart');

Route::post('/product/{id}/remove_from_shoppingcart', [CartController::class, 'removeFromShoppingCart'])->name('removeFromShoppingCart');

Route::post('/product/{id}/remove_from_cart_page', [CartController::class, 'removeFromCartPage'])->name('removeFromCartPage');

Route::post('/product/{id}/delete_from_cart', [CartController::class, 'deleteFromCart'])->name('deleteFromCart');

Route::get('/mainpage', [StaticPageController::class, 'showMainPage'])->name('mainPage');

Route::get('/category/{id}', [CategoryController::class, 'show'])->name('showProducts');

Route::get('/search', [ProductController::class, 'search'])->name('showResult');

Route::get('/shopping-cart', [CartController::class, 'show'])->name('shoppingcart');

Route::get('/product/{id}', [ProductController::class, 'show'])->name('showProductDetails');

Route::get('/search-products', [StaticPageController::class, 'showSearchedProductsPage'])->name('searchProducts');

Route::post('/sort-products', [ProductController::class, 'sort'])->name('sort.products');

Route::get('/get_shopping_cart_status/{id}', [CartController::class, 'getShoppingCartStatus']);

Route::get('/faq', [StaticPageController::class, 'showFAQPage'])->name('faq');

Route::get('/about', [StaticPageController::class, 'showAboutPage'])->name('about');

Route::get('/checkout', [StaticPageController::class, 'showCheckoutPage'])->middleware('auth')->name('checkoutPage');

Route::post('/createOrder/{id}', [OrderController::class, 'createOrder'])->name('createOrder');

Route::post('/admin/add_new_product', [ProductController::class, 'addProduct'])->name('addProduct');

Route::post('/admin/delete_product/{id}', [ProductController::class, 'deleteProduct'])->name('adminDeleteProduct');

Route::post('/admin/edit_product/{id}', [ProductController::class, 'adminEditProduct'])->name('adminEditProduct');


