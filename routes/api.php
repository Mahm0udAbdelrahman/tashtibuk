<?php

use App\Http\Controllers\Api\User\RateController;
use App\Http\Controllers\Api\User\CategoryController;
use App\Http\Controllers\Api\User\DeleteAccountController;
use App\Http\Controllers\Api\User\LoginController;
use App\Http\Controllers\Api\User\OrderController;
use App\Http\Controllers\Api\User\LogoutController;
use App\Http\Controllers\Api\User\PasswordController;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\User\RegisterController;
use App\Http\Controllers\Api\User\ComplaintController;
use App\Http\Controllers\Api\User\FavoriteController;
use App\Http\Controllers\Api\User\VendorController;
use App\Http\Controllers\Api\User\CartController;
use App\Http\Controllers\Api\User\NotificationController;
use App\Http\Controllers\Api\User\HelpController;
use App\Http\Controllers\Api\User\RefundRequestController;
use App\Http\Controllers\Api\User\HomeController;
use App\Http\Controllers\Api\User\TermsOfConditionsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['lang']], function () {
// register
    Route::post("/register", [RegisterController::class, 'register']);

// verify
    Route::post('/verify', [RegisterController::class, 'verify']);
    Route::post('/otp', [RegisterController::class, 'otp']);
//login
    Route::post("/login", [LoginController::class, 'login']);
//forget-password
    Route::post('/forget-password', [PasswordController::class, 'forgetPassword']);
//confirmationOtp
    Route::post('/confirmation-otp', [PasswordController::class, 'confirmationOtp']);
//reset-password
    Route::post('/reset-password', [PasswordController::class, 'resetPassword']);
    Route::get('/categories', [CategoryController::class, 'index']);
    
    //vendor
    Route::get('vendor', [VendorController::class, 'index']);
    Route::get('details_vendor/{id}', [VendorController::class, 'show']);
    Route::get('product_vendor/{id}', [VendorController::class, 'productVendor']);
     Route::get('best-selling_products', [HomeController::class, 'products']);
  Route::get('terms_of_conditions',[TermsOfConditionsController::class, 'index']);
  Route::get('help',[HelpController::class, 'index']);


    Route::group(['middleware' => ['auth:sanctum']], function () {
        // profile
        Route::get('/profile', [ProfileController::class, 'profile']);
        Route::post('/profile', [ProfileController::class, 'updateProfile']);
        Route::post('/change-password', [PasswordController::class, 'changePassword']);
        Route::post('/logout', [LogoutController::class, 'logout']);
        //delete_account
        Route::get('delete_account', [DeleteAccountController::class, 'deleteAccount']);
        
         Route::get('favorite',[FavoriteController::class,'index']);
        Route::post('favorite/{product_id}',[FavoriteController::class,'store']);
        
        Route::post('rate/{product_id}', [RateController::class, 'store']);
        
        Route::get('cart', [CartController::class, 'index']);
        Route::post('cart', [CartController::class, 'store']);
        Route::post('cart/{id}', [CartController::class, 'update']);
        Route::post('cart/clear/all', [CartController::class, 'clear']);
        Route::delete('cart/{id}', [CartController::class, 'destroy']);
        Route::get('cart/order', [CartController::class, 'getOrderByCart']);
        
        Route::post('order', [OrderController::class, 'store']);
        Route::get('history_order', [OrderController::class, 'index']);
        
        Route::post('complaint', [ComplaintController::class, 'store']);
        
        Route::get('/notifications', [NotificationController::class, 'index']);
                
        Route::get('refund_request/{id}', [RefundRequestController::class, 'show']);
        
        Route::post('refund_request', [RefundRequestController::class, 'store']);

            

         



    });

});
Route::get('/callback', [OrderController::class, 'callback']);

require base_path('routes/vendor_api.php');
require base_path('routes/dashboard_api.php');
require base_path('routes/delivery_api.php');
