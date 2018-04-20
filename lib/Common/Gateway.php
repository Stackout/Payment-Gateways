<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Gateway.php extends the contract 
 *
 * Long description for file (if any)...
 *
 * PHP version 7.0+
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   PaymentProcessing
 * @package    PaymentGateway
 * @author     Original Author <ryan@heimech.com>
 * @copyright  2018 Heimech Studios
 * @link       http://github.com/stackout/payment-gateways
 * @since      File available since Release 1.0.0
 */


namespace Stackout\PaymentGateways;


/**
 * This is a "Docblock Comment," also known as a "docblock."  The class'
 * docblock, below, contains a complete description of how to write these.
 */
class Gateway{

    /**
     * Stripe Constants
     *
     * @var constant[int] STRIPE = 0
     * @var constant[string] STRIPE_NAME = stripe_name
     */
    public const STRIPE = 0;
    public const STRIPE_NAME = 'stripe';
    
    /**
     * AUTHORIZE.NET Constants
     * @var constant[int] AUTHORIZENET = 1 
     * @var constant[string] AUTHORIZENET_NAME = authorize.net
     */
    public const AUTHORIZENET = 1;
    public const AUTHORIZENET_NAME = 'authorize.net';

    /**
     * PAYPAL PRO Constants
     * @var constant[int] PAYPAL = 2 
     * @var constant[string] PAYPAL_NAME = paypal
     */
    public const PAYPAL = 2;
    public const PAYPAL_NAME = 'paypal';

    /**
     * Every gateway can potentially contain public and private keys. These are not stored here.
     *
     * @var string
     */
    protected $publicKey;

    /**
     * Every gateway can potentiall contain a private key to talk to the API. These are not stored here.
     *
     * @var string
     */
    protected $privteKey;

      /**
     * Every gateway can potentiall contain a private key to talk to the API. These are not stored here.
     *
     * @var string
     */
    protected $isDevelopment;
    
    /**
     * This service tells us if we are in production or develpoment
     *
     * @var string
     */
    protected $service;
     
    /**
     * This service tells us waht cache driver we're using
     *
     * @var string
     */
    protected $cache;
    
    /**
     * This service tells us waht cache driver we're using
     *
     * @var string
     */
    protected $gatewayName;
     
     /**
     * This service tells us if we are in production or develpoment
     *
     * @var array
     */
    protected $attributes = array();
    

    /**
     *  Allow overloading of constructor if we want to set our own private and public keys
     */
    public function __construct($attributes = [], $privateKey = null, $publicKey = null){

        // Check if we are in develpoment or production
        $this->isDevlopment = Config::get('payment_gateways.development');

        // Load cache setting from config
        $this->cache = Config::get('payment_gateways.cache');

        // Set which service to provide
        $this->service = ($this->isDevelopment) ? 'development' : 'production';

        if($privateKey != null)
            $this->privateKey = $privateKey;
        
        if($publicKey != null)
            $this->publicKey = $publicKey;

        // Define Attributes
        $this->attributes = $attributes;
    }

    /**
     * Magic Getter Method
     * @return attribute
     */
    public function __get($key){
        if (!array_key_exists($key, $this->attributes)) 
            throw new Exception ("Property {$key} is not defined.");

        return $this->attributes[$key];
    }

    /**
     * Magic Setter Method
     * @return void
     */
    public function __set($key, $value){$this->attributes[$key] = $value;}

    /**
     * @return attribute
     */
    public function getAttribute($key){
        return $this->attributes[$key];
    }

    /**
     * Set all Attributes
     * @var array attributes 
     * 
     * @return void
     */
    public function setAttributes($attributes = []){

        foreach($attributes as $key => $value)
            $this->attributes[$key] = $value;
        

    }

    public static function getPublicKey(){



    }

}