<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\OrderController;

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

Auth::routes(['register'=>false]);

Route::get('user/login','App\Http\Controllers\FrontendController@login')->name('login.form');
Route::post('user/login','App\Http\Controllers\FrontendController@loginSubmit')->name('login.submit');
Route::get('user/logout','App\Http\Controllers\FrontendController@logout')->name('user.logout');

Route::get('user/register','App\Http\Controllers\FrontendController@register')->name('register.form');
Route::post('user/register','App\Http\Controllers\FrontendController@registerSubmit')->name('register.submit');
// Reset password
Route::post('password-reset', 'App\Http\Controllers\FrontendController@showResetForm')->name('password.reset'); 
// Socialite 
Route::get('login/{provider}/', 'App\Http\Controllers\Auth\LoginController@redirect')->name('login.redirect');
Route::get('login/{provider}/callback/', 'App\Http\Controllers\Auth\LoginController@Callback')->name('login.callback');

Route::get('/','App\Http\Controllers\FrontendController@home')->name('home');

// Frontend Routes
Route::get('/home', 'App\Http\Controllers\FrontendController@index');
Route::get('/about-us','App\Http\Controllers\FrontendController@aboutUs')->name('about-us');
Route::get('/contact','App\Http\Controllers\FrontendController@contact')->name('contact');
Route::post('/contact/message','App\Http\Controllers\MessageController@store')->name('contact.store');
Route::get('product-detail/{slug}','App\Http\Controllers\FrontendController@productDetail')->name('product-detail');
Route::post('/product/search','App\Http\Controllers\FrontendController@productSearch')->name('product.search');
Route::get('/product-cat/{slug}','App\Http\Controllers\FrontendController@productCat')->name('product-cat');
Route::get('/product-sub-cat/{slug}/{sub_slug}','App\Http\Controllers\FrontendController@productSubCat')->name('product-sub-cat');
Route::get('/product-brand/{slug}','App\Http\Controllers\FrontendController@productBrand')->name('product-brand');
// Cart section
Route::get('/add-to-cart/{slug}','App\Http\Controllers\CartController@addToCart')->name('add-to-cart')->middleware('user');
Route::post('/add-to-cart','App\Http\Controllers\CartController@singleAddToCart')->name('single-add-to-cart')->middleware('user');
Route::get('cart-delete/{id}','App\Http\Controllers\CartController@cartDelete')->name('cart-delete');
Route::post('cart-update','App\Http\Controllers\CartController@cartUpdate')->name('cart.update');

Route::get('/cart',function(){
    return view('frontend.pages.cart');
})->name('cart');
Route::get('/checkout','App\Http\Controllers\CartController@checkout')->name('checkout')->middleware('user');
// Wishlist
Route::get('/wishlist',function(){
    return view('frontend.pages.wishlist');
})->name('wishlist');
Route::get('/wishlist/{slug}','App\Http\Controllers\WishlistController@wishlist')->name('add-to-wishlist')->middleware('user');
Route::get('wishlist-delete/{id}','App\Http\Controllers\WishlistController@wishlistDelete')->name('wishlist-delete');
Route::post('cart/order','App\Http\Controllers\OrderController@store')->name('cart.order');
Route::get('order/pdf/{id}','App\Http\Controllers\OrderController@pdf')->name('order.pdf');
Route::get('/income','App\Http\Controllers\OrderController@incomeChart')->name('product.order.income');
// Route::get('/user/chart','AdminController@userPieChart')->name('user.piechart');
Route::get('/product-grids','App\Http\Controllers\FrontendController@productGrids')->name('product-grids');
Route::get('/product-lists','App\Http\Controllers\FrontendController@productLists')->name('product-lists');
Route::match(['get','post'],'/filter','App\Http\Controllers\FrontendController@productFilter')->name('shop.filter');
// Order Track
Route::get('/product/track','App\Http\Controllers\OrderController@orderTrack')->name('order.track');
Route::post('product/track/order','App\Http\Controllers\OrderController@productTrackOrder')->name('product.track.order');
// Blog
Route::get('/blog','App\Http\Controllers\FrontendController@blog')->name('blog');
Route::get('/blog-detail/{slug}','App\Http\Controllers\FrontendController@blogDetail')->name('blog.detail');
Route::get('/blog/search','App\Http\Controllers\FrontendController@blogSearch')->name('blog.search');
Route::post('/blog/filter','App\Http\Controllers\FrontendController@blogFilter')->name('blog.filter');
Route::get('blog-cat/{slug}','App\Http\Controllers\FrontendController@blogByCategory')->name('blog.category');
Route::get('blog-tag/{slug}','App\Http\Controllers\FrontendController@blogByTag')->name('blog.tag');

// NewsLetter
Route::post('/subscribe','App\Http\Controllers\FrontendController@subscribe')->name('subscribe');

// Product Review
Route::resource('/review','App\Http\Controllers\ProductReviewController');
Route::post('product/{slug}/review','App\Http\Controllers\ProductReviewController@store')->name('review.store');

// Post Comment 
Route::post('post/{slug}/comment','App\Http\Controllers\PostCommentController@store')->name('post-comment.store');
Route::resource('/comment','App\Http\Controllers\PostCommentController');
// Coupon
Route::post('/coupon-store','App\Http\Controllers\CouponController@couponStore')->name('coupon-store');
// Payment
Route::get('payment', 'App\Http\Controllers\PayPalController@payment')->name('payment');
Route::get('cancel', 'App\Http\Controllers\PayPalController@cancel')->name('payment.cancel');
Route::get('payment/success', 'App\Http\Controllers\PayPalController@success')->name('payment.success');



// Backend section start

Route::group(['prefix'=>'/admin','middleware'=>['auth','admin']],function(){
    Route::get('/','App\Http\Controllers\AdminController@index')->name('admin');
    Route::get('/file-manager',function(){
        return view('backend.layouts.file-manager');
    })->name('file-manager');
    // user route
    Route::resource('users','App\Http\Controllers\UsersController');
    // Banner
    Route::resource('banner','App\Http\Controllers\BannerController');
    // Brand
    Route::resource('brand','App\Http\Controllers\BrandController');
    // Profile
    Route::get('/profile','App\Http\Controllers\AdminController@profile')->name('admin-profile');
    Route::post('/profile/{id}','App\Http\Controllers\AdminController@profileUpdate')->name('profile-update');
    // Category
    Route::resource('/category','App\Http\Controllers\CategoryController');
    // Product
    Route::resource('/product','App\Http\Controllers\ProductController');
    // Ajax for sub category
    Route::post('/category/{id}/child','App\Http\Controllers\CategoryController@getChildByParent');
    // POST category
    Route::resource('/post-category','App\Http\Controllers\PostCategoryController');
    // Post tag
    Route::resource('/post-tag','App\Http\Controllers\PostTagController');
    // Post
    Route::resource('/post','App\Http\Controllers\PostController');
    // Message
    Route::resource('/message','App\Http\Controllers\MessageController');
    Route::get('/message/five','App\Http\Controllers\MessageController@messageFive')->name('messages.five');

    // Order
    Route::resource('/order','App\Http\Controllers\OrderController');
    // Shipping
    Route::resource('/shipping','App\Http\Controllers\ShippingController');
    // Coupon
    Route::resource('/coupon','App\Http\Controllers\CouponController');
    // Settings
    Route::get('settings','App\Http\Controllers\AdminController@settings')->name('settings');
    Route::post('setting/update','App\Http\Controllers\AdminController@settingsUpdate')->name('settings.update');

    // Notification
    Route::get('/notification/{id}','App\Http\Controllers\NotificationController@show')->name('admin.notification');
    Route::get('/notifications','App\Http\Controllers\NotificationController@index')->name('all.notification');
    Route::delete('/notification/{id}','App\Http\Controllers\NotificationController@delete')->name('notification.delete');
    // Password Change
    Route::get('change-password', 'App\Http\Controllers\AdminController@changePassword')->name('change.password.form');
    Route::post('change-password', 'App\Http\Controllers\AdminController@changPasswordStore')->name('change.password');
});










// User section start
Route::group(['prefix'=>'/user','middleware'=>['user']],function(){
    Route::get('/','App\Http\Controllers\HomeController@index')->name('user');
     // Profile
     Route::get('/profile','App\Http\Controllers\HomeController@profile')->name('user-profile');
     Route::post('/profile/{id}','App\Http\Controllers\HomeController@profileUpdate')->name('user-profile-update');
    //  Order
    Route::get('/order',"App\Http\Controllers\HomeController@orderIndex")->name('user.order.index');
    Route::get('/order/show/{id}',"App\Http\Controllers\HomeController@orderShow")->name('user.order.show');
    Route::delete('/order/delete/{id}','App\Http\Controllers\HomeController@userOrderDelete')->name('user.order.delete');
    // Product Review
    Route::get('/user-review','App\Http\Controllers\HomeController@productReviewIndex')->name('user.productreview.index');
    Route::delete('/user-review/delete/{id}','App\Http\Controllers\HomeController@productReviewDelete')->name('user.productreview.delete');
    Route::get('/user-review/edit/{id}','App\Http\Controllers\HomeController@productReviewEdit')->name('user.productreview.edit');
    Route::patch('/user-review/update/{id}','App\Http\Controllers\HomeController@productReviewUpdate')->name('user.productreview.update');
    
    // Post comment
    Route::get('user-post/comment','App\Http\Controllers\HomeController@userComment')->name('user.post-comment.index');
    Route::delete('user-post/comment/delete/{id}','App\Http\Controllers\HomeController@userCommentDelete')->name('user.post-comment.delete');
    Route::get('user-post/comment/edit/{id}','App\Http\Controllers\HomeController@userCommentEdit')->name('user.post-comment.edit');
    Route::patch('user-post/comment/udpate/{id}','App\Http\Controllers\HomeController@userCommentUpdate')->name('user.post-comment.update');
    
    // Password Change
    Route::get('change-password', 'App\Http\Controllers\HomeController@changePassword')->name('user.change.password.form');
    Route::post('change-password', 'App\Http\Controllers\HomeController@changPasswordStore')->name('change.password');

});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () { '\vendor\UniSharp\LaravelFilemanager\Lfm::routes()'; });

