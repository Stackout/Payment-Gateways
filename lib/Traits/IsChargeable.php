<?php


namespace Stackout\PaymentGateways\Traits;
use Stackout\PaymentGateways\GatewayProcessor;
use Stackout\PaymentGateways\Gateway;



trait IsChargeable{

    /**
     * @var Gateway
     */
    protected $paymentGateway;

    /**
     * @var Gateway
     */
    protected $gatewayResponse;

    /**
     * Charge this user
     * 
     * @var Request
     * @var int
     * 
     * @return Gateway
     */
    public function charge($request, $amount){

        $tihs->paymentGateway = GatewayProcessor::get($request);
        
        $gateway->customer = $this;
        $gateway->amount = $amount;

        // Get Charge object
        $this->gatewayResponse = $gateway->charge();

        // Return Charge Object
        return $this->gatewayResponse;

    }


}