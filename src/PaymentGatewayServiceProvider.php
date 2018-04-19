<?php

namespace Stackout\PaymentGateways;

use Illuminate\Support\ServiceProvider;


class PaymentGatewaysServiceProvider extends ServiceProvider
{

    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . './routes/web.php');

    }

    public function register()
    {


    }


}