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
     * @var int
     * @var Request
     * 
     * @return Gateway
     */
    public function charge($amount, $request = null){

        // If gatewy isn't initlized, then we initilize it.
        if($this->paymentGateway == null) $this->initGateway($request);
        else $gateway = $this->paymentGateway;

        // Set the customer to 'this' being the user or customer
        $gateway->customer = $this;

        // Set the amount to charge the customer
        $gateway->amount = $amount;

        // Get Charge object
        $this->gatewayResponse = $gateway->charge();

        // Return Charge Object
        return $this->gatewayResponse;

    }

    /**
     * @var Request
     * 
     * @return Gateway
     */
    public function creditcard($request = null){

        // If gatewy isn't initlized, then we initilize it.
        if($this->paymentGateway == null) $this->initGateway($request);
        else $gateway = $this->paymentGateway;

        $this->gatewayResponse = $gateway->creditcard();

        return $this->gatewayResponse;

    }

    /**
     * @var Request
     * 
     * @return Gateway
     */
    public function initGateway($request = null){

        // Arbirarily pass in the request
        if($request == null)
            $request = request();

        $this->paymentGateway = GatewayProcessor::get($request);

        return $this->paymentGateway;

    }



}