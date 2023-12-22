<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ResetPasswordController;
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
    Route::get('/profile', 'show')->name('user')->middleware('auth');
    Route::post('/profile/edit_profile', 'edit')->name('edit_profile');
    Route::get('/profile/edit_profile', 'showEdit')->name('show/edit_profile')->middleware('auth');
    Route::post('/profile/edit_profile/edit_password', 'editPassword')->name('edit_password');
    Route::get('/profile/admin/user', 'adminUsers')->name('admin_users')->middleware('auth');
    Route::get('/profile/admin/product', 'adminProducts')->name('admin_products')->middleware('auth');
    Route::get('/profile/admin/order', 'adminOrders')->name('admin_orders')->middleware('auth');
    Route::get('/profile/admin/promotion', 'adminPromotions')->name('admin_promotions')->middleware('auth');
    Route::get('/profile/admin/review', 'adminReviews')->name('admin_reviews')->middleware('auth');
    Route::post('/profile/admin/delete_user', 'deleteUser')->name('delete_user');
    Route::post('/profile/admin/block_user', 'blockUser')->name('block_user');
    Route::post('/profile/edit_photo', 'editPhoto')->name('edit_photo');
    Route::post('/notifications_read/{id}', 'readNotifications')->name('readNotifications');
    Route::get('/profile/admin/user_details/{id}', 'userDetails')->name('AdminUsersDetails')->middleware('auth');
    Route::get('/profile/admin/statistics', 'statistics')->name('Statistics')->middleware('auth');
});

Route::controller(StaticPageController::class)->group(function () {
    Route::get('/mainpage', 'showMainPage')->name('mainPage');
    Route::get('/search-products', 'showSearchedProductsPage')->name('searchProducts');
    Route::get('/faq', 'showFAQPage')->name('faq');
    Route::get('/about', 'showAboutPage')->name('about');
    Route::get('/checkout', 'showCheckoutPage')->middleware('auth')->name('checkoutPage');
    Route::get('/features', 'showFeaturesPage')->name('features');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('/product/{id}', 'show')->name('showProductDetails');
    Route::get('/search', 'search')->name('showResult');
    Route::get('/product/{id}', 'show')->name('showProductDetails');
    Route::post('/admin/add_new_product', 'addProduct')->name('addProduct');
    Route::post('/admin/delete_product/{id}', 'deleteProduct')->name('adminDeleteProduct');
    Route::post('/admin/edit_product/{id}', 'edit')->name('adminEditProduct');
    Route::post('/admin/edit_product/delete_photo/{id}', 'deletePhoto')->name('adminDeletePhoto');
    Route::post('/admin/edit_product/add_photo/{id}', 'addPhoto')->name('adminAddPhoto');
    Route::post('/product_info/{id}', 'getProductInfo')->name('getProductInfo');
    Route::get('/products/load', 'load')->name('load');
    Route::get('products/promotions', 'promotionProducts')->name('promotions');
});

Route::controller(CartController::class)->group(function () {
    Route::get('/shopping-cart', 'show')->name('shoppingcart');
    Route::get('/get_shopping_cart_status/{id}', 'getShoppingCartStatus');
    Route::post('/product/{id}/add_to_shoppingcart', 'addToShoppingCart')->name('addToShoppingCart');
    Route::post('/product/{id}/remove_from_shoppingcart', 'removeFromShoppingCart')->name('removeFromShoppingCart');
    Route::post('/product/{id}/remove_from_cart_page', 'removeFromCartPage')->name('removeFromCartPage');
    Route::post('/product/{id}/delete_from_cart', 'deleteFromCart')->name('deleteFromCart');
    Route::post('/save-cart-items/{id}/{quantity}', 'saveCartItems')->name('saveCartItems');
});

Route::controller(ReviewController::class)->group(function () {
    Route::post('/upvote_review/{id}', 'upvoteReview')->name('upvoteReview');
    Route::post('/submit_review/{id}', 'submitReview')->name('submitReview');
    Route::post('/delete_review/{id}', 'deleteReview')->name('deleteReview');
    Route::post('/update_review/{id}', 'updateReview')->name('updateReview');
});

Route::controller(WishlistController::class)->group(function () {
    Route::get('/wishlist', 'show')->name('wishlist');
    Route::post('/wishlist/add/{id}', 'addToWishlist');
    Route::post('/wishlist/remove/{id}', 'removeFromWishlist');
});

Route::controller(StripeController::class)->group(function () {
    Route::get('/stripe/success', 'handleSuccess')->name('stripe.success');
    Route::get('/stripe/cancel', 'handleCancel')->name('stripe.cancel');
});

Route::controller(DiscountController::class)->group(function () {
    Route::post('/admin/add_new_promotion', 'addPromotion');
    Route::post('/profile/admin/edit_promotion', 'edit');
    Route::post('/admin/delete_promotion/{id}', 'deletePromotion');
});

Route::controller(OrderController::class)->group(function () {
    Route::post('/createOrder', 'createOrder')->name('createOrder');
    Route::post('/profile/admin/edit_order', 'edit');
});

Route::controller(ReportController::class)->group(function () {
    Route::post('/report_review/{id}', 'reportReview');
    Route::post('/admin/delete_report', 'deleteReport');
});

Route::get('/category/{id}', [CategoryController::class, 'show'])->name('showProducts');

Route::post('/forgot-password', [MailController::class, 'send'])->middleware('guest');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('forgotPassword');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->middleware('guest')->name('password.update');

Route::post('/notification/delete/{id}', [NotificationController::class, 'delete']);
Route::get('/last_notification', [NotificationController::class, 'show']);
