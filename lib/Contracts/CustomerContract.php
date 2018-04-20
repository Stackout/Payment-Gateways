<?php


namespace Stackout\PaymentGateways\Contracts;


interface CustomerContract{

    /**
     * All methods pertaining to customer profiles
     */
    public function createCustomer($data);

    public function retrieveCustomer($id);

    public function updateCustomer($id, $data);

    public function deleteCustomer($id);


}