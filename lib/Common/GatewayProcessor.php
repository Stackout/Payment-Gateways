<?php

namespace Stackout\PaymentGateways;


class GatewayProcessor extends Gateway{

    /**
     * Protected gateway property
     *
     * @var integer Gateway
     */
    protected $gateway;

    public function __constructor($gateway, array $attributes = [], string $privateKey = null, string $publicKey = null){

        parent::__constructor($attributes, $privateKey, $publicKey);
        
        $this->gateway = $gateway;


    }

    public function getGateway(){

        switch($this->gateway){
            case Gateway::STRIPE:
                return new StripePaymentGateway('token_egj0943-fwsvs');
            break;

            case Gateway::PAYPAL:
                return new PayPalPaymentGateway('token_egj0943-fwsvs');
            break;
            
            default:
                return new Gateway();
            break;

        }



    }


}