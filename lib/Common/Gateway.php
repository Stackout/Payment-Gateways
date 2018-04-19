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
class Gateway extends Contracts\PaymentGatewayContract{

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
     *  Allow overloading of constructor if we want to set our own private and public keys
     */
    public function __construct($privateKey = null, $publicKey = null){

        // Set the isDevelopment Property
        $this->idDevelopment = \Loader::get('');

        // Check to see if we are loading the keys from 


        if($privateKey != null)
            $this->privateKey = $privateKey;
        else
            $this->privateKey = ($privateKey != null) ? $privateKey : \Config::get();

        if($publicKey != null)
            $this->publicKey = $publicKey;


    }

    /**
     * 
     * 
     * @return void
     */
    public function __get(string $name){


    }

}