<?php

namespace Stackout\PaymentGateways;

use \Config;
use \Cache;

use Illuminate\Http\Request;


abstract class GatewayProcessor{

    /**
     * Protected gateway property
     *
     * @var integer Gateway
     */
    const GATEWAY = null;


    public function __construct(){

        define();
        
    }

    public final static function get(Request $request = null, array $attributes = [], int $gateway = null){

        if($gateway == null)
            $gateway = Gateway::default();

        switch($gateway){
            case Gateway::STRIPE:
                return self::stripe($request, $attributes);
            break;

            case Gateway::PAYPAL:
                return new PayPalPaymentGateway();
            break;
            
            default:
                return new Gateway();
            break;
        }

    }

    public final static function stripe(Request $request = null, array $attributes = []){
        return new StripePaymentGateway($request, $attributes);
    }


}