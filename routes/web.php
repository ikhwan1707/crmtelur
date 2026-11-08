<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth'])->group(function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::resource('customers', 'CustomerController');
    Route::resource('products', 'ProductController');
    Route::resource('orders', 'OrderController');
    Route::post('orders/{order}/payments', 'PaymentController@store')->name('payments.store');
    Route::delete('payments/{payment}', 'PaymentController@destroy')->name('payments.destroy');

    Route::get('followups', 'FollowupController@index')->name('followups.index');
    Route::get('followups/create/{customer?}', 'FollowupController@create')->name('followups.create');
    Route::post('followups', 'FollowupController@store')->name('followups.store');
    Route::delete('followups/{followup}', 'FollowupController@destroy')->name('followups.destroy');

    Route::get('repeat-orders', 'RepeatOrderController@index')->name('repeat.index');
    Route::get('reports', 'ReportController@index')->name('reports.index');
});