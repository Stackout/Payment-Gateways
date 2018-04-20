<?php


namespace Stackout\PaymentGateways\Traits;
use Stackout\PaymentGateways\GatewayProcessor;
use Stackout\PaymentGateways\Gateway;



trait IsChargeable{

    public function charge($request, $amount){

        $gateway = GatewayProcessor::get($request);
        
        $gateway->customer = $this;
        $gateway->amount = $amount;

        // Get Charge object
        $charge = $gateway->charge();

        // Return Charge Object
        return $charge;

    }

}