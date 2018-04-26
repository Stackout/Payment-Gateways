<?php


$namespace = 'Stackout\Http\Controllers';


/**
 * Route Group for Testing Payment Gateway
 */
Route::group([
    'namespace' => $namespace,
    'prefix' => 'gateway',
    'middleware' =>'web',
], function (){

    
    Route::get('/', 'GatewayController@index')->name('gateway.index');
    Route::post('/', 'GatewayController@postCheckout')->name('checkout.post');

    
    Route::get('/test', 'GatewayController@test')->name('gateway.test');

});