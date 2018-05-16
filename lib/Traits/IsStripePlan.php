<?php


namespace Stackout\PaymentGateways\Traits;
use Stackout\PaymentGateways\GatewayProcessor;
use Stackout\PaymentGateways\Gateway;



trait IsStripePlan{

    /**
     * Stripe Product Resposne
     */
    protected $stripeProduct;

    /**
     * Stripe Plan Resposne
     */
    protected $stripePlan;

    // Extend 'Has Payment Gateway Trait'
    use HasPaymentGateway {
        HasPaymentGateway::getPaymentGateway as getParentPaymentGateway;
        HasPaymentGateway::getGatewayResponse as getParentGatewayResponse;
    }

    /**
     * Create Product First
     */
    public function createProduct(){

        $gateway = $this->getPaymentGateway();

        $data = [
            'name' => $this->attributes['plan_title'],
            'type' => $this->attributes['type'],
        ];

        $this->stripeProduct = $gateway->createProduct($data);

        // Return this controller or object
        return $this->stripeProduct;

    }

    public function createPlan(){

        $gateway = $this->getPaymentGateway();

        $data = [
            'amount' => $this->attributes['price'],
            'interval' => $this->attributes['interval'],
            'interval_count' => $this->attributes['interval_count'],
            'currency' => $gateway->getCurrency(), // Get the gateway's currency as defined by the user
        ];

        // If plan wasn't set, create plan by assigning it here.
        if($this->attrbutes['product'] == null || $this->attributes['product'] == ''){

            $data['product'] = [
                'name' => $this->attributes['plan_title'],
            ];

        }else{

            // If plan is set and is not an array, we create 
            $data['product'] = $this->stripeProduct->id;

        }
        
        // Save to the stripePlan
        $this->stripePlan = $gateway->createPlan($data);
        
        // Return this controller or object
        return $this->stripePlan;
    }

    public function getPaymentGateway(){

        return $this->getParentPaymentGateway();
        
    }

    public function getGatewayResponse(){

        return $this->getParentGatewayResponse();

    }


    public function getPlan(){



    }

    public function getProduct(){
        
    }
 
}