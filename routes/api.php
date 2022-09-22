<?php

use Illuminate\Http\Request;

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

Route::middleware(['auth:api', 'cors'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/order/store', 'OrderController@store')->name('order.store');
Route::post('/order/cross/store', 'OrderController@storeCrossSale')->name('order.store');