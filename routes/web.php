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


use KhairulImam\ROSWrapper\Wrapper;


Route::view('/', 'welcome');


Route::prefix('ip')->middleware('auth')->group(function() {
    Route::resource('dhcp-server', 'DhcpServerController')->except(['update', 'edit']);
    Route::get('dhcp-server/{id}/{toggle}/toggle', 'DhcpServerController@toggle')->name('dhcp-server.toggle');
    Route::resource('pool', 'PoolController')->except(['create', 'store', 'destroy']);
    Route::resource('network', 'NetworkController')->except(['create', 'store', 'destroy']);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
