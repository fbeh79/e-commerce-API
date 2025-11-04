<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\FooterSectionController;
use App\Http\Controllers\CopuonController;
use App\Http\Controllers\TransActionsController;

Route::prefix('sliders')->group(function () {
    Route::post('/', [SliderController::class, 'store']);
    Route::get('/', [SliderController::class, 'index']);
    Route::get('/{slider}', [SliderController::class, 'show']);
    Route::put('/{slider}', [SliderController::class, 'update']);
    Route::delete('/{slider}', [SliderController::class, 'destroy']);

});
Route::apiResource('features', FeatureController::class);

Route::prefix('About')->group(function () {
    Route::get('/', [AboutController::class, 'index']);
    Route::put('/{about}', [AboutController::class, 'update']);
});
Route::prefix('contact')->group(function () {

    Route::get('/', [ContactController::class, 'index']);
    Route::put('/{contact}', [ContactController::class, 'show']);
    Route::delete('/{contact}', [ContactController::class, 'destroy']);

});
Route::prefix('Footer')->group(function () {

Route::put('/{Footer_Section}',[FooterSectionController::class, 'update']);
Route::put('/',[FooterSectionController::class, 'index']);
});
Route::apiResource('categories', CategoryController::class);
Route::apiResource('product',ProductController::class);

Route::apiResource('Coupon', CopuonController::class);

Route::get('/order', [OrderController::class, 'index']);
Route::get('/Transaction', [TransActionsController::class, 'index']);
Route::get('/transaction/chart', [TransActionsController::class, 'chart']);
