Please note! This package is a work in progress. I was in dire need of something with respect to a payment processor gateway for scaleability purposes. Please feel free to use and help build the library! 

# Stackout's Payment Gateway Processor

This project is meant to provide ease of access to connect to multiple payment gateways. 

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

What things you need to install the software and how to install them

```
Laravel
```
Navigate to your desired project folder, and create a new laravel project.
```
laravel new MyProject
```


### Installing

Installing the package is easy, simply require via composer.

```
composer require stackout/payment-gateways
```
After installing, if you want to use the example checkout views to test your connections, place this line inside your config/app.php

Please note, this step is not required. The service provider was used for testing.
```php
providers = [

    // app providers

        Stackout\PaymentGateways\PaymentGatewaysServiceProvider::class,

    // ...

]
```

Next, after requiring the package, run the artisan command to require the config and migration files. The migration file simply adds a few columns to your user table.
```
php artisan vendor:publish
```
If you use NoSQL migrating the files are not required. If there are any properties and data inside of a collection, the data will be appended and used automatically. 

### Usage
Here is basic usage of the package. You can add a 'charge' method to your user.

```php

use Stackout\PaymentGateways\Traits\IsChargeable;

class User extends Model
{
    use IsChargeable;

}


```
To charge the user after you've added the IsChargeable trait to the user or customer model 
```php
use App\User;

class CheckoutController extends Controller{

    public function postCheckout(Request $request){

        // Charge the Customer
        $user = User::find(1);

        /**
         * Charge the Customer $50.00
         */
        $response = $user->charge(5000); 

        /**
         * If there was a problem or an issue that arose when we tried to charge the card
         * the 'valid()' method let's us know if the charge worked.
         * 
         * Some of the issues that can arise after chargine the card are as follows:
         * - Too many API calls to server
         * - Card was Declined
         * - ect...
         * 
         * If the response here is not valid we can redirect back with errors.
         */
        if(!$response->valid()){
            return redirect()->back()->withErrors($response->errors);
        }

        // Get the Customer's Credit Card Details from the response
        $creditcard = $response->creditcard();

        /**
         * Accessing the Credit Card Data
         * ------------------------------------------------
         * We can access the credit card data from the response we got
         * when we charged the customer.
         * 
         * Depending on application requirements, it is not reccomended to store any creditcard data
         * other than the last 4.
         * 
         * @var String last4
         * @var String brand (i.e. Visa, Master Card ect..)
         * @var String exp_year
         * @var String exp_month
         */
        $creditcard->last4;
        $creditcard->brand;
        $creditcard->exp_year;
        $creditcard->exp_month;

        /**
         * Retrieve the Billing Address
         * 
         * @return Array
         */
        $billingAddress = $creditcard->getAddress();

    }

}
```

## Interrupting Charges
Before charging the customer, we can interrupt the charge and perform some business logic by overriding the interruptCharge() method in the User Model.

```php

use Stackout\PaymentGateways\Traits\IsChargeable;

class User extends Model{

    use IsChargeable;

    public function interruptCharge(){

        // Perform some business logic before charging the customer.

    }


}
```
Some more examples of interrupting the charge of customer could be creating software installation keys, or createing the customer's download link before charging them.

## Built With

* [Laravel](https://laravel.com/docs/5.6/) - PHP Web Framework
* [Stripe](https://stripe.com/docs/api) - The Stripe Payment Processor
* [Authorize.Net](https://github.com/AuthorizeNet/sdk-php) - Authorize.Net Payment Processor (PHP-SDK)

## Authors

* **Ryan Hein** - *Initial work* - [Stackout](https://github.com/Stackout)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Inspiration provided by ProYard.com
* etc
