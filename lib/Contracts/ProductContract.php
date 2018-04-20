<?php


namespace Stackout\PaymentGateways;


interface ProductContract{


    /**
     * All methods pertianing to products
     */
    public function createProduct($data);

    public function retrieveProduct($id);

    public function updateProduct($id, $data);

    public function deleteProduct($id);


}