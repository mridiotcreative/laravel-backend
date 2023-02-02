<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function ()
{
    dd("Page is under construction");
})->name('home');

Route::get('/', 'FrontendController@home')->name('home');
Auth::routes(['register' => false]);
// Get City By State
Route::post('/city-by-state', 'Frontend\LoginController@getCityByState')->name('register.city');
// Frontend Section
// Guest Middleware Redirect Home If Authenticated
Route::group(
    ['namespace' => 'Frontend', 'middleware' => ['guest:customer']],
    function () {
        // User Login
        Route::get('/login', 'LoginController@login')->name('login.form');
        Route::post('/login', 'LoginController@loginSubmit')->name('login.submit');
        // Register
        Route::get('/register', 'LoginController@register')->name('register.form');
        Route::post('/register', 'LoginController@registerSubmit')->name('register.submit');
        // Forgot Password
        Route::get('/forgot-password', 'LoginController@resetPasswordForm')->name('password.forgot');
        Route::post('/forgot-password', 'LoginController@resetPasswordSubmit')->name('password.forgot.submit');
        // Set New Password
        Route::get('/set-new-password/{token}', 'LoginController@setNewPasswordForm')->name('password.setNew');
        Route::post('/set-new-password/{token}', 'LoginController@setNewPasswordSubmit')->name('password.setNew.submit');
    }
);

// Frontend Page
Route::get('/home', 'FrontendController@home');
Route::get('/about-us', 'FrontendController@aboutUs')->name('about-us');
// Contact Us
Route::post('/contact-us', 'ContactUsController@submitContactUs')->name('contact.store');
Route::get('/contact-us', 'ContactUsController@contact')->name('contact');

// Product
Route::get('product-detail/{slug}', 'FrontendController@productDetail')->name('product-detail');
Route::match(['get', 'post'], '/product/{search?}', 'FrontendController@productSearch')->name('product.search');
Route::match(['get', 'post'], '/product-grids', 'FrontendController@productGrids')->name('product-grids');

// // Cart section
Route::middleware(['customer'])->group(function () {
    Route::post('/add-to-cart', 'CartController@addToCart')->name('cart.add');
    Route::post('cart-delete', 'CartController@cartDelete')->name('cart.delete');
    Route::post('cart-update', 'CartController@cartUpdate')->name('cart.update');

    // Cart
    Route::get('/cart', 'CartController@cart')->name('cart');
    Route::post('/cart-point', 'CartController@cartPoint')->name('cart.point');
    Route::get('/order-summery', 'CartController@orderSummery')->name('order.summery');
    // Checkout
    Route::get('/checkout', 'Frontend\CheckoutController@checkout')->name('checkout');
    Route::post('/razorpay-order', 'Frontend\CheckoutController@razorpayOrder')->name('razorpay.order');
    Route::post('/checkout', 'Frontend\CheckoutController@checkoutSubmit')->name('checkout.submit');

    Route::post('cart/order', 'OrderController@store')->name('cart.order');
    Route::get('order/pdf/{id}', 'OrderController@pdf')->name('order.pdf');
    Route::get('/income', 'OrderController@incomeChart')->name('product.order.income');
    // Coupon
    Route::post('/coupon-store', 'CouponController@couponStore')->name('coupon-store');
});
// Thank You
Route::any('/thankyou', 'FrontendController@thankyou')->name('thankyou');
// Order Track
Route::get('/product/track', 'OrderController@orderTrack')->name('order.track');
Route::post('product/track/order', 'OrderController@productTrackOrder')->name('product.track.order');
// Blog
Route::get('/blogs', 'FrontendController@blogs')->name('blogs');
Route::get('/blog-detail/{slug}', 'FrontendController@blogDetail')->name('blog.detail');
Route::get('/blog/search', 'FrontendController@blogSearch')->name('blog.search');
Route::post('/blog/filter', 'FrontendController@blogFilter')->name('blog.filter');
Route::get('blog-cat/{slug}', 'FrontendController@blogByCategory')->name('blog.category');
Route::get('blog-tag/{slug}', 'FrontendController@blogByTag')->name('blog.tag');

// NewsLetter
Route::post('/subscribe', 'FrontendController@subscribe')->name('subscribe');

// Product Review
Route::resource('/review', 'ProductReviewController');
Route::get('review', ['uses'=>'ProductReviewController@index', 'as'=>'review.index']);
Route::post('product/{slug}/review', 'ProductReviewController@store')->name('review.store');
Route::post('/product.review-delete-multiple', 'ProductReviewController@deleteMultipleRecord')->name('product.review.delete.multiple');

// Post Comment
Route::post('post/{slug}/comment', 'PostCommentController@store')->name('post-comment.store');
Route::resource('/comment', 'PostCommentController');
Route::get('comment', ['uses'=>'PostCommentController@index', 'as'=>'comment.index']);
Route::post('/comment-delete-multiple', 'PostCommentController@deleteMultipleRecord')->name('comment.delete.multiple');

Route::get('cms/{slug}', 'CmsDetailsController@viewCMSpage')->name('cms.page.view');


// Payment
// Route::get('payment', 'PayPalController@payment')->name('payment');
// Route::get('cancel', 'PayPalController@cancel')->name('payment.cancel');
// Route::get('payment/success', 'PayPalController@success')->name('payment.success');

// My Account
Route::group(['prefix' => 'user', 'as' => 'user.', 'namespace' => 'Frontend', 'middleware' => ['customer']], function () {
    // Logout
    Route::get('logout', 'LoginController@logout')->name('logout');
    // My Profile
    Route::get('my-profile', 'MyProfileController@myProfile')->name('profile.form');
    Route::post('my-profile', 'MyProfileController@updateMyProfile')->name('profile.update');
    // Manage Address
    Route::get('manage-address', 'CustomerAddressController@getAddressList')->name('address.list');
    Route::post('get-address', 'CustomerAddressController@getAddressDetails')->name('address.details');
    Route::post('change-primary-address', 'CustomerAddressController@changePrimaryAddress')->name('change.primary.address');
    Route::post('manage-address', 'CustomerAddressController@storeOrUpdateAddress')->name('address.storeOrUpdate');
    Route::delete('address-destroy', 'CustomerAddressController@addressDestroy')->name('address.destroy');

    // User Change Password
    Route::get('change-password', 'MyProfileController@userChangePassword')->name('change.password.form');
    Route::post('change-password', 'MyProfileController@userChangePasswordStore')->name('change.password');

    // Order History
    Route::get('order-history', 'MyProfileController@orderHistory')->name('order.history');
    Route::get('order-from-past-items', 'MyProfileController@orderFromPastItems')->name('order.past.items');
    Route::post('order-details-by-id', 'MyProfileController@orderByID')->name('order.details.by.id');

    // My Points
    Route::get('my-points', 'MyProfileController@myPoints')->name('points');
});

// Backend section start
// Admin Login
Route::get('/admin/login', 'Auth\LoginController@showLoginForm')->name('admin.login');
Route::post('/admin/login', 'Auth\LoginController@login')->name('admin.login.submit');
Route::group(['prefix' => '/admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', 'AdminController@index')->name('admin');
    Route::post('logout', 'AdminController@logout')->name('logout');
    // Route::get('/file-manager', function () {
    //     return view('backend.layouts.file-manager');
    // })->name('file-manager');

    // Customer
    Route::get('/active-customer/{customer}', 'CustomerController@makeAccountActive')->name('customer.active');
    Route::post('/declined-customer', 'CustomerController@makeAccountDeclined')->name('customer.declined');
    Route::post('/customer-status-change', 'CustomerController@changeStatus')->name('customer.change.status');
    Route::get('/customer/from/{customer?}', 'CustomerController@createOrEdit')->name('customer.create');
    Route::match(['POST', 'PATCH'], '/customer/{customer?}', 'CustomerController@storeOrUpdate')->name('customer.update');
    Route::resource('customer', 'CustomerController')->only(['index', 'destroy']);
    Route::get('customer', ['uses'=>'CustomerController@index', 'as'=>'customer.index']);
    Route::get('/customer/show/{id}', "CustomerController@customerShow")->name('customer.show');
    Route::post('/customer-delete-multiple', 'CustomerController@deleteMultipleRecord')->name('customer.delete.multiple');
    // user route
    Route::get('/users/form/{users?}', 'UsersController@createOrEdit')->name('users.create');
    Route::match(['POST', 'PATCH'], '/users/{users?}', 'UsersController@storeOrUpdate')->name('users.update');
    Route::resource('users', 'UsersController', ['only' => ['index', 'destroy']]);
    Route::get('users', ['uses'=>'UsersController@index', 'as'=>'users.index']);
    // Banner
    Route::get('/banner/form/{banner?}', 'BannerController@createOrEdit')->name('banner.create');
    Route::match(['POST', 'PATCH'], '/banner/{banner?}', 'BannerController@storeOrUpdate')->name('banner.update');
    Route::resource('banner', 'BannerController')->only(['index', 'destroy', null]);
    Route::get('banner', ['uses'=>'BannerController@index', 'as'=>'banner.index']);
    Route::post('/banner-status-change', 'BannerController@changeStatus')->name('banner.change.status');
    Route::post('/banner-delete-multiple', 'BannerController@deleteMultipleRecord')->name('banner.delete.multiple');
    // Brand
    Route::get('/brand/form/{brand?}', 'BrandController@createOrEdit')->name('brand.create');
    Route::match(['POST', 'PATCH'], '/brand/{brand?}', 'BrandController@storeOrUpdate')->name('brand.update');
    Route::resource('brand', 'BrandController')->only(['index', 'destroy']);
    Route::get('brand', ['uses'=>'BrandController@index', 'as'=>'brand.index']);
    // Profile
    Route::get('/profile', 'AdminController@profile')->name('admin-profile');
    Route::post('/profile/{id}', 'AdminController@profileUpdate')->name('profile-update');
    // Category
    Route::get('/category/form/{category?}', 'CategoryController@createOrEdit')->name('category.create');
    Route::match(['POST', 'PATCH'], '/category/{category?}', 'CategoryController@storeOrUpdate')->name('category.update');
    Route::resource('/category', 'CategoryController')->only(['index', 'destroy', null]);
    Route::get('category', ['uses'=>'CategoryController@index', 'as'=>'category.index']);
    Route::get('/category/export-category-csv', 'CategoryController@ExportCategoryCSV')->name('category.export.csv');
    Route::post('/category/import-category-csv', 'CategoryController@ImportCategoryCSV')->name('category.import.csv');
    Route::post('/category-status-change', 'CategoryController@changeStatus')->name('category.change.status');
    Route::post('/category-delete-multiple', 'CategoryController@deleteMultipleCategory')->name('category.delete.multiple');
    // Product
    Route::get('/product/form/{product?}', 'ProductController@createOrEdit')->name('product.create');
    Route::match(['POST', 'PATCH'], '/product/{product?}', 'ProductController@storeOrUpdate')->name('product.update');
    Route::resource('/product', 'ProductController')->only(['index', 'destroy']);
    Route::get('product', ['uses'=>'ProductController@index', 'as'=>'product.index']);
    Route::post('/product-status-change', 'ProductController@changeStatus')->name('product.change.status');
    Route::post('/product-delete-multiple', 'ProductController@deleteMultipleRecord')->name('product.delete.multiple');
    // Ajax for sub category
    Route::post('/category/{id}/child', 'CategoryController@getChildByParent');
    // POST category
    Route::get('/post-category/form/{postCategory?}', 'PostCategoryController@createOrEdit')->name('post-category.create');
    Route::match(['POST', 'PATCH'], '/post-category/{postCategory?}', 'PostCategoryController@storeOrUpdate')->name('post-category.update');
    Route::resource('/post-category', 'PostCategoryController')->only(['index', 'destroy']);
    Route::get('post-category', ['uses'=>'PostCategoryController@index', 'as'=>'post-category.index']);
    Route::post('/post-category-delete-multiple', 'PostCategoryController@deleteMultipleRecord')->name('post.category.delete.multiple');
    // Post tag
    Route::get('/post-tag/form/{postTag?}', 'PostTagController@createOrEdit')->name('post-tag.create');
    Route::match(['POST', 'PATCH'], '/post-tag/{postTag?}', 'PostTagController@storeOrUpdate')->name('post-tag.update');
    Route::resource('/post-tag', 'PostTagController')->only(['index', 'destroy']);
    Route::get('post-tag', ['uses'=>'PostTagController@index', 'as'=>'post-tag.index']);
    Route::post('/post-tag-delete-multiple', 'PostTagController@deleteMultipleRecord')->name('post.tag.delete.multiple');
    // Post
    Route::get('/post/form/{post?}', 'PostController@createOrEdit')->name('post.create');
    Route::match(['POST', 'PATCH'], '/post/{post?}', 'PostController@storeOrUpdate')->name('post.update');
    Route::resource('/post', 'PostController')->only(['index', 'destroy']);
    Route::get('post', ['uses'=>'PostController@index', 'as'=>'post.index']);
    Route::post('/post-delete-multiple', 'PostController@deleteMultipleRecord')->name('post.delete.multiple');
    // Message
    Route::resource('/message', 'MessageController');
    Route::get('/message/five', 'MessageController@messageFive')->name('messages.five');

    // Order
    Route::resource('/order', 'OrderController');
    Route::get('order', ['uses'=>'OrderController@index', 'as'=>'order.index']);
    Route::post('/order/update-status', 'OrderController@updateStatus')->name('order.update-status');
    Route::post('/order-delete-multiple', 'OrderController@deleteMultipleRecord')->name('order.delete.multiple');
    // Shipping
    Route::get('/shipping/form/{shipping?}', 'ShippingController@createOrEdit')->name('shipping.create');
    Route::match(['POST', 'PATCH'], '/shipping/{shipping?}', 'ShippingController@storeOrUpdate')->name('shipping.update');
    Route::resource('/shipping', 'ShippingController')->only(['index', 'destroy']);
    Route::get('shipping', ['uses'=>'ShippingController@index', 'as'=>'shipping.index']);
    // Coupon
    Route::get('/coupon/form/{coupon?}', 'CouponController@createOrEdit')->name('coupon.create');
    Route::match(['POST', 'PATCH'], '/coupon/{coupon?}', 'CouponController@storeOrUpdate')->name('coupon.update');
    Route::resource('/coupon', 'CouponController')->only(['index', 'destroy']);
    // Settings
    Route::get('settings', 'AdminController@settings')->name('settings');
    Route::post('setting/update', 'AdminController@settingsUpdate')->name('settings.update');

    // Notification
    Route::get('/notification/{id}', 'NotificationController@show')->name('admin.notification');
    Route::get('/notifications', 'NotificationController@index')->name('all.notification');
    Route::delete('/notification/{id}', 'NotificationController@delete')->name('notification.delete');
    // Password Change
    Route::get('change-password', 'AdminController@changePassword')->name('change.password.form');
    Route::post('change-password', 'AdminController@changPasswordStore')->name('change.password');

    // Contact Us
    Route::resource('/contact-us', 'ContactUsController')->only(['index', 'show']);
    Route::get('contact-us', ['uses'=>'ContactUsController@index', 'as'=>'contact-us.index']);

    // Generate Barcode
    Route::get('/barcode/{id?}', 'BarcodeController@listBarcode')->name('barcode.list');
    Route::post('/barcode-generate', 'BarcodeController@create')->name('generate.barcode');
    Route::post('/barcode-status-change', 'BarcodeController@changeStatus')->name('barcode.change.status');
    Route::get('order/pdf/{id}', 'OrderController@pdf')->name('order.pdf');
    Route::post('/barcode-destroy', 'BarcodeController@destroy')->name('barcode.destroy');

    // cms
    Route::get('/cms/form/{cms?}', 'CmsDetailsController@createOrEdit')->name('cms.create');
    Route::match(['POST', 'PATCH'], '/cms/{cms?}', 'CmsDetailsController@storeOrUpdate')->name('cms.update');
    Route::resource('cms', 'CmsDetailsController')->only(['index', 'destroy', null]);
    Route::get('cms', ['uses'=>'CmsDetailsController@index', 'as'=>'cms.index']);
    Route::post('/cms-status-change', 'CmsDetailsController@changeStatus')->name('cms.change.status');
    Route::post('/cms-delete-multiple', 'CmsDetailsController@deleteMultipleRecord')->name('cms.delete.multiple');

    // pricemaster
    Route::get('/pricemaster/form/{pricemaster?}', 'PriceMasterController@createOrEdit')->name('pricemaster.create');
    Route::match(['POST', 'PATCH'], '/pricemaster/{pricemaster?}', 'PriceMasterController@storeOrUpdate')->name('pricemaster.update');
    Route::resource('pricemaster', 'PriceMasterController')->only(['index', 'destroy', null]);
    Route::get('pricemaster', ['uses'=>'PriceMasterController@index', 'as'=>'pricemaster.index']);
    Route::post('/pricemaster-status-change', 'PriceMasterController@changeStatus')->name('pricemaster.change.status');
    Route::post('/pricemaster-delete-multiple', 'PriceMasterController@deleteMultipleRecord')->name('pricemaster.delete.multiple');

    // offermaster
    Route::get('/offermaster/form/{offermaster?}', 'OfferMasterController@createOrEdit')->name('offermaster.create');
    Route::match(['POST', 'PATCH'], '/offermaster/{offermaster?}', 'OfferMasterController@storeOrUpdate')->name('offermaster.update');
    Route::resource('offermaster', 'OfferMasterController')->only(['index', 'destroy', null]);
    Route::get('offermaster', ['uses'=>'OfferMasterController@index', 'as'=>'offermaster.index']);
    Route::post('/offermaster-status-change', 'OfferMasterController@changeStatus')->name('offermaster.change.status');
    Route::post('/offermaster-delete-multiple', 'OfferMasterController@deleteMultipleRecord')->name('offermaster.delete.multiple');
});


// User section start
Route::group(['prefix' => '/user', 'middleware' => ['user']], function () {
    Route::get('/', 'HomeController@index')->name('user');
    // Profile
    Route::get('/profile', 'HomeController@profile')->name('user-profile');
    Route::post('/profile/{id}', 'HomeController@profileUpdate')->name('user-profile-update');
    //  Order
    Route::get('/order', "HomeController@orderIndex")->name('user.order.index');
    Route::get('/order/show/{id}', "HomeController@orderShow")->name('user.order.show');
    Route::delete('/order/delete/{id}', 'HomeController@userOrderDelete')->name('user.order.delete');
    // Product Review
    Route::get('/user-review', 'HomeController@productReviewIndex')->name('user.productreview.index');
    Route::delete('/user-review/delete/{id}', 'HomeController@productReviewDelete')->name('user.productreview.delete');
    Route::get('/user-review/edit/{id}', 'HomeController@productReviewEdit')->name('user.productreview.edit');
    Route::patch('/user-review/update/{id}', 'HomeController@productReviewUpdate')->name('user.productreview.update');

    // Post comment
    Route::get('user-post/comment', 'HomeController@userComment')->name('user.post-comment.index');
    Route::delete('user-post/comment/delete/{id}', 'HomeController@userCommentDelete')->name('user.post-comment.delete');
    Route::get('user-post/comment/edit/{id}', 'HomeController@userCommentEdit')->name('user.post-comment.edit');
    Route::patch('user-post/comment/udpate/{id}', 'HomeController@userCommentUpdate')->name('user.post-comment.update');

    // Password Change
    // Route::get('change-password', 'HomeController@changePassword')->name('user.change.password.form');
    // Route::post('change-password', 'HomeController@changPasswordStore')->name('change.password');
});


// Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
//     \UniSharp\LaravelFilemanager\Lfm::routes();
// });

// Barcode
Route::get('/get-barcode', 'BarcodeController@getBarcode');

// Email Template Routes
Route::get('account-inactive', function () {
    return view('frontend.email.account_inactive');
});
Route::get('account-under-verify', function () {
    return view('frontend.email.account_under_verify');
});
Route::get('account-declined', function () {
    return view('frontend.email.acount_declined')->with(['text' => 'declined reason here']);
});
Route::get('account-verified', function () {
    return view('frontend.email.account_verified');
});
Route::get('reset-password', function () {
    return view('frontend.email.reset_password')->with(['resetLink' => url('/reset-password')]);
});
Route::get('order-placed', function () {
    return view('frontend.email.order_placed');
});
