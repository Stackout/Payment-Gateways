<?php


namespace Stackout\PaymentGateways;

use \Config;
use \Cache;
use Illuminate\Http\Request;
use Stripe\Stripe as Stripe;


class StripePaymentGateway extends Gateway implements CustomerContract{

    /**
     * Stripe charges a customer not by using their 'credit card' but by how are server interacts with stripe
     * by storing it in a PCI DSS compliant place on Stripe's server.
     * 
     * We then take this troken taht was returned from a response from stripe and charge the customer's credit card.
     */


    /**
     * @var StripeCustomerObject $stripeCustomer;
     */
    protected $stripeCustomer;

    public function __construct(Request $request = null, array $attributes = [], $publicKey = null, $privateKey = null, $currency = Currency::__default){

        // Set the gateway name for this instance
        $this->gatewayName = Gateway::STRIPE_NAME;

        parent::__construct($request, Gateway::STRIPE, $attributes, $privateKey, $publicKey, $currency);

        $this->init($request);

    }

    // Initilizes our Stripe instance
    private function init(Request $request = null){

        $this->request = $request;

        if($request != null)
            $this->attributes['stripeToken'] = $request->input('stripeToken');
        

        Stripe::SetAPIKey($this->getPrivateKey());

    }


    /**
     * All methods pertaining to customer profiles
     */
    public function createCustomer(){
        
        $data = [];

        // First see if customer exists as a 'stripe' csutomer.
        if($this->retrieveCustomer() === false)
        {

            // Build the customer's data array
            if($this->request != null){

                if($this->request->has('stripeToken'))
                   $data['source'] = $this->attributes['stripeToken']; 

                if($this->request->has('email'))
                    $data['email'] = $this->request->input('email');

            }else{

                $data = ['email' => $this->customer->email];

            }

            $this->stripeCustomer = \Stripe\Customer::create($data);

            // Save the stripe id to our customer's details.
            $this->customer->stripe_id = $customer->id;
            $this->customer->save();

            return $this->stripeCustomer;

        }

        // Retrieve customer if already exists
        return $this->stripeCustomer;

    }

    // Get customer from Stripe by ID
    public function retrieveCustomer(){

        // If we've already declared a stripe customer in this scope, we can access the object here.
        if($this->stripeCustomer != null)
            return $this->stripeCustomer;

        // If we haven't set our customer object, throw an error.
        if($this->customer == null)
            throw new Exception('Customer model or object not defined.');

        if($this->customer->stripe_id == '' || $this->customer->stripe_id == null)
            return false;


        // If we couldn't find the object after it's been retrieved, we can define the stripe customer object here.
        $this->stripeCustomer = \Stripe\Customer::retrieve($this->customer->stripe_id);

        return $this->stripeCustomer;

    }

    public function saveCustomer(){

        if($this->retrieveCustomer() === false)
            throw new \Exception('Customer does not have a stripe account.');


    }

    public function deleteCustomer(){

        if($this->retrieveCustomer() === false)
            throw new \Exception('Customer does not have a stripe account.');
        
        $this->stripeCustomer()->delete();

        return true;

    }

    public function getAllCustomers(array $data){

        $customers = \Stripe\Customer::all($data);

        return $customers;

    }

}