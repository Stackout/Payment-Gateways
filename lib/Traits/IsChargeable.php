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
        if($this->paymentGateway == null)
            $this->initPaymentGateway($request);

        // Set the customer to 'this' being the user or customer
        $this->paymentGateway->customer = $this;

        // Set the amount to charge the customer
        $this->paymentGateway->amount = $amount;

        // Charge the Customer
        $this->gatewayResponse =  $this->paymentGateway->charge();

        // Return Charge Object
        return $this->gatewayResponse;

    }

    /**
     * 
     * 
     * @var Request
     * 
     * @return Gateway
     */
    public function creditcard($request = null){

        // If gatewy isn't initlized, then we initilize it.
        if($this->paymentGateway == null)
            $this->initPaymentGateway($request);

        $this->gatewayResponse = $this->paymentGateway->creditcard();

        return $this->gatewayResponse;

    }

    /**
     * @var Request
     * 
     * @return Gateway
     */
    public function initPaymentGateway($request = null){

        // Arbirarily pass in the request
        if($request == null)
            $request = request();

        // Get and Set Payment Gateway
        $this->paymentGateway = GatewayProcessor::get($request);

        // Return Gateway object 
        return $this->paymentGateway;

    }

    /**
     * @var Request
     * 
     * @return Gateway
     */
    
    public function subscribe($request = null)
    {
        // If the payment gateway is not setup, set it up
        if($this->paymentGateway == null)
            $this->initPaymentGateway();

        // Set this customer
        $this->paymentGateway->customer = $this;

        // Create subscription
        $this->paymentGateway->createSubscription();

        return $this->paymentGateway;

    }


}