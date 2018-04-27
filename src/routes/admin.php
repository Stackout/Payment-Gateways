<?php


$namespace = 'Stackout\Http\Controllers';


/**
 * Route Group for Testing Payment Gateway
 */
Route::group([
    'namespace' => $namespace,
    'prefix' => 'admin/gateway',
    'middleware' =>'web',
], function (){

    
    Route::get('/', 'GatewayController@settings')->name('settings.index');
    Route::post('/', 'GatewayController@postSettings')->name('settings.post');


});
