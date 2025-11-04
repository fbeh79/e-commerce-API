<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\FooterSectionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/sliders', [SliderController::class, 'index']);
Route::get('/features',[FeatureController::class,'index']);
Route::get('/about',[AboutController::class,'index']);
Route::post('/contact',[ContactController::class,'store']);
Route::post('/Footer',[FooterSectionController::class, 'store']);
Route::get('/categories',[CategoryController::class,'index']);
Route::get('/products',[ProductController::class,'index']);
Route::get('/products/products-random',[ProductController::class,'randomProduct']);
Route::get('/products/tabs',[ProductController::class,'productsTabs']);
Route::get('/menu',[ProductController::class,'menu']);
Route::post('/auth/login',[AuthController::class,'login']);
Route::post('/auth/check-otp',[AuthController::class,'checkOtp']);
Route::post('/auth/resend-otp',[AuthController::class,'resendOtp']);
Route::get('/auth/me',[AuthController::class,'me'])->middleware(['auth:sanctum']);
Route::post('/auth/logout',[AuthController::class,'logout'])->middleware(['auth:sanctum']);

Route::prefix('profile')->middleware('auth:sanctum')->group(function(){
    Route::get('/provinces-cities',[ProfileController::class,'provincesCities']);
    Route::post('/addresses',[ProfileController::class,'userAddress']);
    Route::put('/addresses/{address}',[ProfileController::class,'updateAddress']);
    Route::get('/addresses',[ProfileController::class,'indexAddress']);

    Route::get('/wishlist',[ProfileController::class,'indexWishlist']);
    Route::get('/Add_wishlist',[ProfileController::class,'AddToWishlist']);
    Route::get('/delete_wish/{wishlist}',[ProfileController::class,'deleteWishlist']);

    Route::get('/order',[ProfileController::class,'orders']);
    Route::get('/transaction',[ProfileController::class,'transactions']);
});
Route::prefix('payment')->group(function(){
    Route::post('/check-coupon',[PaymentController::class,'checkCoupon']);
    Route::post('/send',[PaymentController::class,'PaymentSend']);
    Route::post('/callback', [PaymentController::class, 'callback'])->name('callback');
});

