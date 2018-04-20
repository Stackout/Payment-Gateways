<?php


namespace Stackout\PaymentGateways;


interface CustomerContract{

    /**
     * All methods pertaining to customer profiles
     */
    public function createCustomer();

    public function retrieveCustomer();

    public function saveCustomer();

    public function deleteCustomer();

    public function getAllCustomers(array $data);

}