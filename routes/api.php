<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->group(
    function () {
        // // User Type
        // Route::get('/user-types', 'AuthenticationController@getUserTypes');
        // // State
        // Route::get('/state', 'AuthenticationController@getState');
        // // City
        // Route::get('/city', 'AuthenticationController@getCity');
        // // Get State With City
        // Route::get('/state-with-city', 'AuthenticationController@getStateWithCity');
        // Login
        Route::post('/login', 'AuthenticationController@login');
        // Register
        Route::post('/register', 'AuthenticationController@register');
        // Forgot Password
        Route::post('/forgot-password', 'AuthenticationController@forgotPassword');
        // // Article Section
        // Route::post('/get-article-list', 'PostController@getArticleList');
        // Route::get('/get-article-detail/{slug}', 'PostController@getArticleDetail');

        //  // Get Notification List
        //  Route::get('/cms-page', 'CmsController@index');

         // product api routes
         Route::prefix('product')->group(function(){
             Route::post('/categories-wise', 'ProductController@getCategoryWise');
             Route::post('/getallproducts', 'ProductController@index');
             Route::get('/details/{slug}', 'ProductController@details');
         });

         // home api routes
         Route::get('/home-banner', 'HomeController@index');
         Route::get('/users-story', 'HomeController@userStory');
         Route::get('/all-video-shorts', 'HomeController@allVideoPhotoShorts');

         Route::prefix('category')->group(function(){
            Route::get('/get-category', 'HomeController@getCategory');
         });

        // Authorize Access Route
        Route::middleware(['auth:api'])->group(function () {
            // Logout
            Route::get('logout', 'AuthenticationController@logout');

            // Profile
            Route::get('get-profile', 'MyAccountController@getProfile');
            Route::post('update-profile', 'MyAccountController@updateProfile');
            Route::post('add-update-profile-photo', 'MyAccountController@addUpdateProfilePhoto');

            // Change Password
            Route::post('change-password', 'MyAccountController@changePassword');

            // Story
            Route::post('create-story', 'UserController@createStory');

            // Upload Video Photo
            Route::post('upload-video-photo', 'UserController@uploadVideoPhoto');

            // Video Like and comment and share
            Route::post('add-like-dislike', 'UserController@addLikeAndDislike');
            Route::post('add-comment-share', 'UserController@addCommentAndShare');
            Route::post('remove-like', 'UserController@removeLike');
            Route::post('remove-comment', 'UserController@removeComment');
            Route::post('remove-dislike', 'UserController@removeDislike');

            //follower Info
            Route::post('get-user-profile', 'UserController@getUserProfile');
            Route::post('follow-user', 'UserController@followUser');
            Route::post('un-follow-user', 'UserController@unFollowUser');
            Route::post('get-follower', 'UserController@getFollower');
            Route::post('get-following', 'UserController@getFollowing');

            // Manage Address
            Route::post('add-address', 'CustomerAddressController@addAddress');
            Route::post('update-address', 'CustomerAddressController@updateAddress');
            Route::get('get-address-list', 'CustomerAddressController@getAddressList');
            Route::post('get-address-details', 'CustomerAddressController@getAddressDetails');
            Route::delete('delete-address/{id}', 'CustomerAddressController@deleteAddress');
            Route::post('change-primary-address', 'CustomerAddressController@changePrimaryAddress');
            // Use Barcode
            Route::post('use-barcode', 'BarCodeController@useBarcode');
            // Get Notification List
            Route::get('/get-push-notification-list', 'PushNotificationController@getPushNotificationList');

            // cart api routes
            Route::prefix('cart')->group(function(){
                Route::post('/addToCart', 'CartController@addToCart');
                Route::post('/cartDelete', 'CartController@cartDelete');
                Route::get('/', 'CartController@index');
            });

             // order api routes
             Route::prefix('order')->group(function(){
                Route::post('/place', 'OrderController@place');
                Route::post('/', 'OrderController@index');
                Route::post('/details', 'OrderController@detail');
            });

            // product review routes
            // Route::prefix('review')->group(function(){
            //     Route::post('/', 'ReviewController@store');
            //     Route::post('/delete', 'ReviewController@destroy');
            // });
        });

        // Send Test Push
        Route::post('/send-test-push', 'PushNotificationController@sendTestPush');
    }
);
