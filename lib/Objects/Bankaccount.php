<?php

namespace Stackout\Objects;

class Bankaccount extends Source {


    /**
     * Bankname
     * 
     * @var String
     */
    protected $bankName; 


    /**
     * Specific Account ID (Used with Stripe)
     * 
     * @var String
     */
    protected $account_id; 

    /**
     * Account Holder Name
     * 
     * @var String
     */
    protected $accountHolderName;

    /**
     * Account Holder Type (Business or Individual)
     * 
     * @var String
     */
    protected $accountHolderType; // Business or Individual

    /**
     * Fingerprint
     * 
     * @var String
     */
    protected $fingerPrint; // Business or Individual   

    public function __construct($last4 = null, $exp_month = null, $exp_year = null){

        $this->object = \Objects::CREDITCARD;

        if($last4 != null)
            $this->last4 = $last4;

        if($exp_month != null)
            $this->exp_month = $exp_month;

        if($exp_year != null)
            $this->exp_year = $exp_year;

        parent::__construct();

    }


}