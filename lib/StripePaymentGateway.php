<?php


namespace Stackout\PaymentGateways;

use Stackout\Objects\Creditcard;
use Stackout\Objects\Bankaccount;

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
        
        $this->setupApplication();
    }


    public function setupApplication(){

        // Store values for the private and publish keys from the config file.
        if(!Cache::has('payment_gateway:stripe:publicKey') || is_null($this->privateKey) || is_null($this->publicKey))
        {
            // Store the private key as a protected variable.
            $this->setPrivateKey(\Config::get($this->attributes['config'] . '.private'));

            // Store the public key
            Cache::set('payment_gateway:stripe:publicKey', Config::get($this->attributes['config'] . '.public'));
            $this->publicKey = \Config::get($this->attributes['config'] . '.public');
            
        }

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


                if($this->request->has('email'))
                    $data['email'] = $this->request->input('email');

            }else{

                $data = ['email' => $this->customer->email];

            }

            $this->stripeCustomer = \Stripe\Customer::create($data);

            // Save the stripe id to our customer's details.
            $this->customer->stripe_id = $this->stripeCustomer->id;
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

        $data = [
            'amount'    => $amount,
            'currency'  => $this->getAttribute('currency'),
            'customer'  => $this->customer->stripe_id,
        ];

        $this->charge = \Stripe\Charge::create($data);

        // Make Creditcard or Bankaccount Object from Source
        $this->source = $this->charge['source'];

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

        // Try to charge customer
        try{
            // create and/or retrieve our customer if he exists or not.
            $this->createCustomer();

            // Add the new credit card to the customer.
            $this->createCard();

            // Interrupt Charge
            if($this->interruptible == true && method_exists($this->customer, 'interruptCharge'))
            {
                $this->customer->interruptCharge($this->request, $this->stripeCustomer);
            }

            // Create our stripe charge
            $this->createCharge();  

        } catch(\Stripe\Error\Card $e) {

            // Since it's a decline, \Stripe\Error\Card will be caught
            $this->errors[] = $e->getMessage();
            
        } catch (\Stripe\Error\RateLimit $e) {

            // Too many requests made to the API too quickly
            $this->errors[] = $e->getMessage();

        } catch (\Stripe\Error\InvalidRequest $e) {

            // Invalid parameters were supplied to Stripe's API
            $this->errors[] = $e->getMessage();

        } catch (\Stripe\Error\Authentication $e) {

            // Authentication with Stripe's API failed
            $this->errors[] = $e->getMessage();

        } catch (\Stripe\Error\ApiConnection $e) {

            // Network communication with Stripe failed
            $this->errors[] = $e->getMessage();

        } catch (\Stripe\Error\Base $e) {

            // Display a very generic error to the user, and maybe send
            // yourself an email
            $this->errors[] = $e->getMessage();

        } catch (\Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $this->errors[] = $e->getMessage();

        }

        return $this;

    }


    public function creditcard(){

        if($this->creditcard != null)
            return $this->creditcard;

        // If default card isn't set, set it
        if($this->default_card == null)
            $this->retrieveCard();

        $default_card = $this->default_card;

        $creditcard = new Creditcard;
        $creditcard->last4 = $default_card->last4;
        $creditcard->exp_year = $default_card->exp_year;
        $creditcard->exp_month = $default_card->exp_month;
        $creditcard->id = $default_card->id;
        $creditcard->country = $default_card->country;
        $creditcard->funding = $default_card->funding;
        $creditcard->fingerprint = $default_card->fingerprint;
        $creditcard->owner = $this->stripeCustomer->id;
        $creditcard->brand = $default_card->brand;

        $creditcard->metadata = $default_card->metadata->__toArray(true);

        $creditcard->setAddress($default_card->line1, $default_card->line2, $default_card->city, $default_card->state, $default_card->zip);
        
        $this->creditcard = $creditcard;

        return $creditcard;

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

        return $this->default_card;

    }

    public function saveCard(){

    }

    public function deleteCard(){

    }

    public function getAllCards(){

    }

    /**
     * This method allows us to interrupt a charage. However, it is not implemented here, so we throw an error
     * if the user wanted to implmenent it, but didn't d o it.
     */
    public function interruptCharge(Request $request){

        throw new \Exception('Interrupt Charge Method not Implemented');

    }


    /**
     * Create a Plan, Product and Subscription
     */

    public function createProduct(array $data){

        if(!array_key_exists('name', $data))
            throw new \Exception('A product name is required.');
        
        if(!array_key_exists('type', $data))
            throw new \Exception('A product type is required.');

        $this->response = \Stripe\Product::create($data);

        return $this;

    }


    public function createPlan(array $data){

        if(!array_key_exists('name', $data))
            throw new \Exception('A plan name is required.');
        
        if(!array_key_exists('interval', $data))
            throw new \Exception('A plan requires an interval.');

        if(!array_key_exists('product', $data))
            throw new \Exception('A product is required to create a plan.');

        if(!array_key_exists('currency', $data))
            $data['currency'] = $this->attributes['currency'];

        $this->response = \Stripe\Plan::create($data);

        return $this;

    }

    /**
     * A subscription registers a user to periodic billing.
     */
    public function createSubscription(array $data){

        // Check if customer is availible.
        if($this->customer == null)
            throw new \Exception('Please assign a customer to this subscription.');
         
        if(!array_key_exists('items', $data) && !array_key_exists('plan', $data['items']))
            throw new \Exception('A plan is required in order to subscribe a customer.');
        
        if(array_key_exists('source', $data)){

            if(!array_key_exists($data['source'], 'object'))
                throw new \Exception('A plan is required in order to subscribe a customer.');

            if(!array_key_exists($data['source'], 'number'))
                throw new \Exception('The card number, as a string, without any separators is required.');
            
            if(!array_key_exists($data['source'], 'exp_month'))
                throw new \Exception('Two-digit number representing the card\'s expiration month. is required.');
            
            if(!array_key_exists($data['source'], 'exp_year'))
                throw new \Exception('Two- or four-digit number representing the card\'s expiration year. is required.');
                              
            if(!array_key_exists($data['source'], 'cvc'))
                throw new \Exception('Card security code is required.');
            
            if(!array_key_exists($data['source'], 'currency'))
                $data['source']['currency'] = $this->attributes['currency'];                
 
        }

        $this->response = \Stripe\Subscription::create($data);

        return $this;

    }


}