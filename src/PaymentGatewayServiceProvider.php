<?php

namespace Stackout\PaymentGateways;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class PaymentGatewaysServiceProvider extends ServiceProvider
{

    public function boot()
    {

        Schema::defaultStringLength(191);
        
        $this->loadRoutesFrom(__DIR__ . './routes/web.php');
        $this->loadRoutesFrom(__DIR__ . './routes/admin.php');
        $this->loadViewsFrom(__DIR__ . './resources/views', 'sgateway');


    }

    /**
     * Register
     * 
     * @return void
     */
    public function register()
    {
        $this->registerPublishables();
    }

    /**
     * Register Publishables
     * 
     * @return void
     */
    public function registerPublishables()
    {

        $basePath = dirname(__DIR__);

        $publishable = [
            'migrations' => [
                $basePath . "/publishable/database/migrations" => database_path('migrations'),
            ],
            'config' =>[
                $basePath . "/publishable/config/payment_gateways.php" => config_path('payment_gateways.php'),
            ]
        ];

        foreach($publishable as $group => $path){
            $this->publishes($path, $group);
        }
    }

}