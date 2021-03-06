<?php

namespace Stackout\Objects;


class Creditcard extends Source {

    protected $last4;

    protected $exp_month;

    protected $exp_year;

    protected $brand;

    public function __construct($last4 = null, $exp_month = null, $exp_year = null, $brand = null){

        $this->object = Objects::CREDITCARD; 

        if($last4 != null)
            $this->last4 = $last4;

        if($exp_month != null)
            $this->exp_month = $exp_month;

        if($exp_year != null)
            $this->exp_year = $exp_year;

        if($brand != null)
            $this->brand = $brand;

        parent::__construct();

    }




}

