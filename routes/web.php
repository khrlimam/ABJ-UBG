<?php


Route::view('/', 'welcome');

Route::prefix('ip')->middleware('auth')->group(function () {
    Route::resource('dhcp-server', 'DhcpServerController');
    Route::get('dhcp-server/{id}/{toggle}/toggle', 'DhcpServerController@toggle')->name('dhcp-server.toggle');
    Route::resource('pool', 'PoolController')->only(['index']);
    Route::resource('network', 'NetworkController')->only(['index']);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
