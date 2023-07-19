<?php

use Illuminate\Support\Facades\Route;
use RaselSwe\Bkash\Http\Controllers\BkashController;

Route::group(['namespace' => 'RaselSwe\Bkash\Http\Controllers'], function(){
    Route::get('/bkash/sample/pay', [BkashController::class, 'pay']);
    Route::get('/bkash/sample/execute-payment', [BkashController::class, 'confirmPayment']);
    Route::get('/bkash/sample/refund-payment', [BkashController::class, 'refundPayment']);
});
