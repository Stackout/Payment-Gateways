<?php


$namespace = 'Stackout\PaymentGateways\Http\Controllers';


/**
 * Route Group for Testing Payment Gateway
 */
Route::group([
    'namespace' => $namespace,
    'prefix' => 'gateway',
], function (){


    Route::get('/tests', '')->name('gateway.test');

});