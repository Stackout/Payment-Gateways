<?php


namespace Stackout\PaymentGateways\Contracts;


interface ChargeContract{


    /**
     * All methods pertaining to charges
     */
    public function createCharge();

    public function retrieveCharge();

    public function updateCharge();

    public function deleteCharge();   

}
?>