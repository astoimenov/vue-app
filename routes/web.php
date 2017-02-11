<?php

use App\Models\Product;

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
    return view('home');
})->name('home.index');

Route::resource('products', 'ProductsController', [
    'only' => ['index'],
]);

Route::resource('plans', 'SubscriptionsController', [
    'only' => ['index', 'store'],
]);

Route::resource('projects', 'ProjectsController', [
    'only' => ['create'],
]);

Route::post('purchases', 'PurchasesController@store');

Auth::routes();

Route::post('stripe/webhook', 'WebHooksController@handle');
