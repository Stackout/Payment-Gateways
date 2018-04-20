<?php


namespace Stackout\PaymentGateways;

use \Config;
use \Cache;
use Illuminate\Http\Request;
use Stripe\Stripe as Stripe;


class StripePaymentGateway extends Gateway implements CustomerContract, ChargeContract{

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

        if($request != null && $this->request->has('stripeToken'))
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
        
        $this->stripeCustomer->delete();

        return true;

    }

    public function getAllCustomers(array $data){

        $customers = \Stripe\Customer::all($data);

        $this->attributes['all_customers'] =
        [
            'object'        => $customers->object,
            'url'           => $customers->url,
            'has_more'      => $customers->has_more,
            'data'          => $customers->data,
        ];

        return $customers->data;

    }


    public function createCharge(){

        if($this->request == null)
            throw new \Exception('Unable to charge customer, invalid request.');

        if(!$this->request->has('stripeToken'))
            throw new \Exception('Unable to charge card, token not found.');
        
        if($this->amount == null)
            throw new \Exception('Unable to charge card, amount not defined.');

        // Re calculate amount based on Stripe's correct amount;
        $amount = ($this->amount * 100);

        $this->charge = \Stripe\Charge::create([
            'amount'    => $amount,
            'currency'  => $this->getAttribute('currency'),
            'customer'  => $this->customer->stripe_id
        ]);
        
        return $this->charge;

    }

    public function captureCharge(){

        if($this->charge == null)
            throw new \Exception('Unable to capture charge, no charge object exists.');

        if(!array_key_exists('statement_descriptor', $this->attributes))
            throw new \Exception('Please define a statement descriptor.');
        
        $this->charge->capture();

    }

    public function charge(){

        // create and/or retrieve our customer if he exists or not.
        $this->createCustomer();

        // Add the new credit card to the customer.
        $this->createCard();

        // Create our stripe charge
        $this->createCharge();

        // Capture the charge we just created
        $this->captureCharge();

    }

    public function retrieveCharge(){

    }

    public function updateCharge(){

    }

    public function deleteCharge(){

    }      


    /**
     * All methods pertaining to customer profiles
     */
    public function createCard(){

        if($this->retrieveCustomer() === false)
            throw new \Exception('Customer does not exist.');

        if(!$this->request->has('stripeToken'))
            throw new \Exception('Invalid request, token not received.');
        
        $this->default_card = $this->stripeCustomer->sources->create([
            "source" => $this->request->input('stripeToken'),
        ]);
        
        // When we create a card, store it's ID as the new default card. 
        $this->customer->stripe_card_id = $this->default_card->id;
        $this->customer->save();

        return $this->default_card;
    }

    public function retrieveCard(){

        if($this->default_card != null)
            return $this->default_card;


        if($this->retrieveCustomer() === false)
            throw new \Exception('Customer does not exist.');

        if($this->customer->stripe_card_id == null && $this->customer->stripe_card_id == '')
            throw new \Exception('Customer does not have a default card to retrieve.');

        $this->default_card = $this->stripeCustomer->sources->retrieve($this->customer->stripe_card_id);

        return $this->stripeCustomer->sources->retrieve();

    }

    public function saveCard(){

    }

    public function deleteCard(){

    }

    public function getAllCards(){

    }


}