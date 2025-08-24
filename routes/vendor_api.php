<?php

use App\Http\Controllers\Api\Vendor\DeleteAccountController;
use App\Http\Controllers\Api\Vendor\LoginController;
use App\Http\Controllers\Api\Vendor\OrderController;
use App\Http\Controllers\Api\Vendor\LogoutController;
use App\Http\Controllers\Api\Vendor\WalletController;
use App\Http\Controllers\Api\Vendor\PasswordController;
use App\Http\Controllers\Api\Vendor\ProfileController;
use App\Http\Controllers\Api\Vendor\RegisterController;
use App\Http\Controllers\Api\Vendor\ProductController;
use App\Http\Controllers\Api\Vendor\NotificationController;
use App\Http\Controllers\Api\Vendor\WithdrawalController;

use Illuminate\Support\Facades\Route;

Route::prefix('vendor')->middleware(['lang'])->group(function () {

    Route::post("/register", [RegisterController::class, 'register']);
    Route::post('/verify', [RegisterController::class, 'verify']);
    Route::post('/otp', [RegisterController::class, 'otp']);
    Route::post("/login", [LoginController::class, 'login']);
    Route::post('/forget-password', [PasswordController::class, 'forgetPassword']);
    Route::post('/confirmation-otp', [PasswordController::class, 'confirmationOtp']);
    Route::post('/reset-password', [PasswordController::class, 'resetPassword']);
    
    Route::get('product', [ProductController::class, 'index']);
    
    Route::get('size', [ProductController::class, 'size']);
    Route::get('category', [ProductController::class, 'category']);
    Route::get('subCategory', [ProductController::class, 'subCategory']);
    
    
    Route::get('product/{id}', [ProductController::class, 'show']);


    Route::middleware(['auth:vendor'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'profile']);
        Route::post('/profile', [ProfileController::class, 'updateProfile']);
        Route::post('/change-password', [PasswordController::class, 'changePassword']);
        Route::post('/logout', [LogoutController::class, 'logout']);
        Route::get('/delete_account', [DeleteAccountController::class, 'deleteAccount']);
        
         //Product
        Route::post('product', [ProductController::class, 'store']);
        Route::post('product/{id}', [ProductController::class, 'update']);
        Route::delete('product/{id}', [ProductController::class, 'destroy']);
        
        Route::get('order', [OrderController::class, 'order']);
        Route::get('order/{id}', [OrderController::class, 'detailsOrder']);
        Route::post('order/{id}', [OrderController::class, 'updateStatus']);
        Route::get('wallet', [WalletController::class, 'index']);
         Route::get('withdrawal', [WithdrawalController::class, 'store']);

         
           // notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
    });

});
