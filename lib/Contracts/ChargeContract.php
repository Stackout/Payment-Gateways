<?php


namespace Stackout\PaymentGateways;


interface ChargeContract{


    /**
     * All methods pertaining to charges
     */
    public function createCharge(array $amount);

    public function retrieveCharge();

    public function updateCharge();

    public function deleteCharge();   

}
?>