<?php


$namespace = 'Stackout\Http\Controllers';


/**
 * Route Group for Testing Payment Gateway
 */
Route::group([
    'namespace' => $namespace,
    'prefix' => 'gateway',
], function (){

    
    Route::get('/', 'GatewayController@index')->name('stackout.gateway.index');
    Route::post('/', 'GatewayController@postCheckout')->name('stackout.gateway.checkout.post');

    
    Route::get('/test', 'GatewayController@test')->name('gateway.test');

});