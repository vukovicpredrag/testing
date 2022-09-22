<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('orders', 'OrderController');
Route::get('deletedOrders', 'OrderController@deletedOrders')->name('ordersDeleted');
Route::get('history/{phoneNumber}', 'OrderController@ordersHistory')->name('orders.history');
Route::post('orders/filterOrders', 'OrderController@filterOrders')->name('orders.get.product');


Route::post('orders/status', 'OrderController@changeStatus')->name('orders.changeStatus');
Route::post('orders/type', 'OrderController@changeType')->name('orders.changeType');
Route::post('orders/statuses', 'OrderController@changeStatuses')->name('orders.changeStatuses');
Route::post('orders/edit/modal', 'OrderController@editModal')->name('orders.edit.modal');
Route::post('orders/restore', 'OrderController@restore')->name('orders.restore');
Route::post('orders/deleteForever', 'OrderController@deleteForever')->name('orders.deleteForever');
Route::post('orders/table', 'OrderController@table')->name('orders.table');
Route::get('/chart', 'OrderController@chart')->name('orders.chart');
Route::post('orders/chart', 'OrderController@mainChart')->name('orders.mainChart');



Route::resource('products', 'ProductController');
Route::post('products/edit/modal', 'ProductController@editModal')->name('product.edit.modal');


Route::resource('status', 'StatusController');
Route::post('status/editStatus', 'StatusController@editStatus')->name('editStatus');


Route::get('users', 'HomeController@users');
Route::post('users/delete', 'HomeController@deleteUser')->name('deleteUser');
Route::post('users/create', 'HomeController@createUser')->name('createUser');
Route::post('users/edit', 'HomeController@editUser')->name('editUser');