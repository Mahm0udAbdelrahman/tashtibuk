<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\SizeController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\LoginController;
use App\Http\Controllers\Dashboard\VendorController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ComplaintController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\HelpController;
use App\Http\Controllers\Dashboard\WithdrawalVendorController;
use App\Http\Controllers\Dashboard\WithdrawalDeliveryController;
use App\Http\Controllers\Dashboard\DeliveryController;
use App\Http\Controllers\Dashboard\SubCategoryController;
use App\Http\Controllers\Dashboard\TermsOfConditionsController;

Route::prefix('dashboard')->middleware(['lang'])->group(function () {

    Route::post("/login", [LoginController::class, 'login']);
    Route::get('/role', [RoleController::class, 'index']);
    Route::get('/permissions', [RoleController::class, 'getPermission']);

    Route::post('/store_role', [RoleController::class, 'store']);

    Route::get('/role/{id}', [RoleController::class, 'show']);

    Route::post('/role/{id}', [RoleController::class, 'update']);

    Route::delete('/role/{id}', [RoleController::class, 'destroy']);

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {

        Route::get("/logout", [LoginController::class, 'logout']);

        Route::get('category', [CategoryController::class, 'index']);
        Route::post('category', [CategoryController::class, 'store']);
        Route::get('category/{id}', [CategoryController::class, 'show']);
        Route::post('category/{id}', [CategoryController::class, 'update']);
        Route::delete('category/{id}', [CategoryController::class, 'destroy']);

        Route::get('sub_category', [SubCategoryController::class, 'index']);
        Route::post('sub_category', [SubCategoryController::class, 'store']);
        Route::get('sub_category/{id}', [SubCategoryController::class, 'show']);
        Route::post('sub_category/{id}', [SubCategoryController::class, 'update']);
        Route::delete('sub_category/{id}', [SubCategoryController::class, 'destroy']);

        Route::get('size', [SizeController::class, 'index']);
        Route::post('size', [SizeController::class, 'store']);
        Route::get('size/{id}', [SizeController::class, 'show']);
        Route::post('size/{id}', [SizeController::class, 'update']);
        Route::delete('size/{id}', [SizeController::class, 'destroy']);
        
        Route::get('help', [HelpController::class, 'index']);
        Route::post('help', [HelpController::class, 'store']);
        Route::get('help/{id}', [HelpController::class, 'show']);
        Route::post('help/{id}', [HelpController::class, 'update']);
        Route::delete('help/{id}', [HelpController::class, 'destroy']);

        Route::post('terms_of_conditions', [TermsOfConditionsController::class, 'update']);
        
        Route::get('setting', [SettingController::class, 'index']);
        Route::post('setting', [SettingController::class, 'update']);
        
        Route::get('complaint', [ComplaintController::class, 'index']);
        Route::get('complaint/{id}', [ComplaintController::class, 'show']);
        Route::delete('complaint/{id}', [ComplaintController::class, 'destory']);
        
        Route::get('vendor', [VendorController::class, 'index']);
        Route::post('vendor', [VendorController::class, 'store']);
        Route::get('vendor/{id}', [VendorController::class, 'show']);
        Route::post('vendor/{id}', [VendorController::class, 'update']);
        Route::delete('vendor/{id}', [VendorController::class, 'destroy']);
        
        Route::get('delivery', [DeliveryController::class, 'index']);
        Route::post('delivery', [DeliveryController::class, 'store']);
        Route::get('delivery/{id}', [DeliveryController::class, 'show']);
        Route::post('delivery/{id}', [DeliveryController::class, 'update']);
        Route::delete('delivery/{id}', [DeliveryController::class, 'destroy']);
        
        Route::get('user', [UserController::class, 'index']);
        Route::post('user', [UserController::class, 'store']);
        Route::get('user/{id}', [UserController::class, 'show']);
        Route::post('user/{id}', [UserController::class, 'update']);
        Route::delete('user/{id}', [UserController::class, 'destroy']);
        
        Route::get('order', [OrderController::class, 'index']);
        Route::get('order/{id}', [OrderController::class, 'show']);
        Route::delete('order/{id}', [OrderController::class, 'destroy']);
        
        
         Route::get('withdrawal_vendor', [WithdrawalVendorController::class, 'index']);
        Route::post('withdrawal_vendor/{id}', [WithdrawalVendorController::class, 'update']);
        Route::delete('withdrawal_vendor/{id}', [WithdrawalVendorController::class, 'destroy']);


        Route::get('withdrawal_delivery', [WithdrawalDeliveryController::class, 'index']);
        Route::post('withdrawal_delivery/{id}', [WithdrawalDeliveryController::class, 'update']);
        Route::delete('withdrawal_delivery/{id}', [WithdrawalDeliveryController::class, 'destroy']);


    });

});
