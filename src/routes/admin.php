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

    
    Route::get('/', 'AdminGatewayController@settings')->name('settings.index');
    Route::post('/', 'AdminGatewayController@postSettings')->name('settings.post');


});
