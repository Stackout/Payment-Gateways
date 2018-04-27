<?php

namespace Stackout\PaymentGateways\Traits;

use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\KeyProtectedByPassword;

trait HasDefusableKeys{

    /**
     * Set defuseable key identifier
     */
    protected $defuseableKeyIdentifier = '_secret';

    /**
     * Defeuse Key
     */
    protected $defuse_key;

    /**
     * Secreat Data
     */
    protected $secret_data;

    /**
     * Encrypted Cipher Text
     */
    protected $cipher_text;

    /**
     * Cipher Errors
     */
    protected $cipher_errors;

    /**
     * Load Encrpytion Key from the Configuration File
     */
    public function loadEncryptionKeyFromConfig(){

        $this->defuse_key = Key::loadFromAsciiSafeString(\Config::get('payment_gateways.defuse_key'));

        return $this;

    }

    public function getDefuseKey(){

        if($this->defuse_key == null)
            $this->setDefuseKey();

        return $this->defuse_key;

    }

    public function setDefuseKey(){

        $this->loadEncryptionKeyFromConfig();

        return $this;
    }

    public function cipherText(){

        if($this->secret_data == null)
            throw new \Exception('Secret data not set.');

        if($this->defuse_key == null)
            throw new \Exception('Defeuse key not set.');

        $this->cipher_text = Crypto::encrypt($this->secret_data, $this->defuse_key);

        return $this;

    }

    public function encrypt($secret_data = null){

        if($secret_data != null)
            $this->secret_data = $secret_data;

        if($this->secret_data == null)
            throw new \Exception('Secret Data not set.');

        // Get Defuse Key
        $this->getDefuseKey();

        // Encrypt Secret Data and output defuse key.
        $this->cipherText();

        return $this;

    }

    public function decrypt($cipher_text = null){

        if($cipher_text != null)
            $this->cipher_text = $cipher_text;

        if($this->defuse_key == null)
            $this->getDefuseKey();

        if($this->cipher_text == null)
            throw new \Exception('Cipher text not set.');

        try {

            $this->secret_data = Crypto::decrypt($this->cipher_text, $this->defuse_key);

        } catch (\Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $e) {

            $this->cipher_errors[] = $e->getMessage();

        }

        return $this;

    }

    /**
     * Creating a Defuse key per user procted by their own password.
     */
     function createKeyProtectedByPassword($password){

        $protected_key = KeyProtectedByPassword::createRandomPasswordProtectedKey($password);
        $protected_key_encoded = $protected_key->saveToAsciiSafeString();

        return $protected_key_encoded;

     }

     /**
      * Unlock password key
      */
     function unlockKey($password, $protected_key_encoded){

        $protected_key = KeyProtectedByPassword::loadFromAsciiSafeString($protected_key_encoded);
        $key = $protected_key->unlockKey($password);
        $key_encoded = $key->saveToAsciiSafeString();

        return $key_encoded;

     }

    /**
     * Set the object's defuse key
     */
    public function setDefusedKey($password, $key_encoded){

        $key = $this->unlockKey($password, $key_encoded);

        session(['defusedKey' => $key]);
                
    }
    
    public function getDefusedKey(){

        return session('defusedKey');

    }

    public function defuseFails(){

        if(!empty($this->cipher_errors))        
            return true;

        return false;
        
    }

    public function getCipherErrors(){
        return $this->cipher_errors;
    }


    /**
     * Get the value of the key and decrpy if needed
     */
    public function getValueAttribute(){

        if(strpos($this->attributes['key'], $this->defuseableKeyIdentifier) !== false && $this->attributes['value'] != ''){

            $this->cipher_text = $this->attributes['value'];
            $this->decrypt();

            $this->attributes['value'] = $this->secret_data;        

        }

        return $this->attributes['value'];

    }

    /**
     * Set the value attribute, store the secret data in the database if it contains '_secret' or the DefuseableKeyIdentifier
     */
    public function setValueAttribute($value){

        if(strpos($this->attributes['key'], $this->defuseableKeyIdentifier) !== false && $value != ''){

            $this->secret_data = $value;
            $this->encrypt();

            $this->attributes['value'] = $this->cipher_text;



        }else{
            
            $this->attributes['value'] = $value;

        }


    }
    

}



?>