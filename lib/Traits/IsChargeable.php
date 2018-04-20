<?php


namespace Stackout\PaymentGateways\Traits;
use Stackout\PaymentGateways\GatewayProcessor;



trait IsChargeable{

    public function charge($request, $amount){

        $payment_attributes = ['statement_descriptor' => 'PROYARD.COM ORDER'];

        $gateway = GatewayProcessor::get($request);
        $gateway->customer = $this;
        $gateway->amount = $amount;

        $gateway->charge();

    }


    

}