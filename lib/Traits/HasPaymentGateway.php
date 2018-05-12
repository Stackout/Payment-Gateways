<?php


namespace Stackout\PaymentGateways\Traits;
use Stackout\PaymentGateways\GatewayProcessor;
use Stackout\PaymentGateways\Gateway;



trait HasPaymentGateway{

    /**
     * @var Gateway
     */
    protected $paymentGateway;

    /**
     * @var Gateway
     */
    protected $gatewayResponse;

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
     * @return Gateway
     */
    public function getPaymentGateway(){

        if($this->paymentGateway == null)
            $this->initPaymentGateway();

        return $this->paymentGateway;
    }

    /**
     * @return GatewayResponse
     */
    public function getGatewayResponse(){

        return $this->gatewayResponse;

    }

}