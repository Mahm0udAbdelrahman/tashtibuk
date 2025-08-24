<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Delivery\LoginController;
use App\Http\Controllers\Api\Delivery\LogoutController;
use App\Http\Controllers\Api\Delivery\ProfileController;
use App\Http\Controllers\Api\Delivery\WalletController;
use App\Http\Controllers\Api\Delivery\PasswordController;
use App\Http\Controllers\Api\Delivery\RegisterController;
use App\Http\Controllers\Api\Delivery\WithdrawalController;
use App\Http\Controllers\Api\Delivery\NotificationController;
use App\Http\Controllers\Api\Delivery\DeleteAccountController;
use App\Http\Controllers\Api\Delivery\OrderController;

Route::prefix('delivery')->middleware(['lang'])->group(function () {

    Route::post("/register", [RegisterController::class, 'register']);
    Route::post('/verify', [RegisterController::class, 'verify']);
    Route::post('/otp', [RegisterController::class, 'otp']);
    Route::post("/login", [LoginController::class, 'login']);
    Route::post('/forget-password', [PasswordController::class, 'forgetPassword']);
    Route::post('/confirmation-otp', [PasswordController::class, 'confirmationOtp']);
    Route::post('/reset-password', [PasswordController::class, 'resetPassword']);


    Route::middleware(['auth:delivery'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'profile']);
        Route::post('/profile', [ProfileController::class, 'updateProfile']);
        Route::post('/location', [ProfileController::class, 'location']);
        Route::post('/change-password', [PasswordController::class, 'changePassword']);
        Route::post('/logout', [LogoutController::class, 'logout']);
        Route::get('/delete_account', [DeleteAccountController::class, 'deleteAccount']);
        
        
        //order
        Route::get('order', [OrderController::class, 'order']);
        Route::get('order/{id}', [OrderController::class, 'detailsOrder']);
        Route::post('order/{id}', [OrderController::class, 'updateStatus']);
        Route::post('accept_order/{id}',[OrderController::class, 'acceptOrder']);
        
        Route::get('wallet', [WalletController::class, 'index']);
        
        Route::get('withdrawal', [WithdrawalController::class, 'store']);




        // notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
    });

});
