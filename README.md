#Shippo PHP API wrapper

Shippo is a shipping API that connects you with multiple shipping carriers (such as USPS, UPS, DHL, Canada Post, Australia Post, UberRUSH and many [others](https://goshippo.com/shipping-carriers/)) through one interface.

Our API provides in depth support of carrier functionality. Here are just some of the features we support for USPS, FedEx and UPS via the API.

For most major carriers (USPS, UPS, FedEx and most others) our API supports:

* Shipping rates & labels
* Tracking
	
* For USPS, the API additionally supports:
	* US Address validation
	* Scan forms
	* Additional services: signature, certified mail, delivery confirmation, and others

* For FedEx, the API additionally supports:
	* Signature and adult signature confirmation
	* FedEx Smartpost

* For UPS, the API additionally supports:
	* Signature and adult signature confirmation
	* UPS Mail Innovations
	* UPS SurePost

The complete list of carrier supported features is [here](https://goshippo.com/shipping-api/carriers).

###About Shippo
Connect with multiple different carriers, get discounted shipping labels, track parcels, and much more with just one integration. You can use your own carrier accounts or take advantage of our deeply discounted rates. Using Shippo makes it easy to deal with multiple carrier integrations,  rate shopping, tracking and other parts of the shipping workflow. We provide the API and dashboard for all your shipping needs.

The API is free to use. You only pay when you print a live label from a carrier.  Use test labels during development to avoid all fees.

You do need a Shippo account to use our API. Don't have an account? Sign up at [https://goshippo.com/](https://goshippo.com/).

## Requirements

* PHP 5.2 or later.

* [Shippo account](https://goshippo.com/) - free to sign up, free to use the API

## Installation

### Installing using Composer

* [Get Composer](http://getcomposer.org/)
* Create/append the following to your `composer.json` file

        {
          "require": {
            "shippo/shippo-php": "1.*"
          }
        }
    
* Install via:

        composer.phar install

* To use the bindings, either user Composer's autoload:

        require_once('vendor/autoload.php');
        
    Or manually:
    
        require_once('/path/to/vendor/shippo/shippo-php/lib/Shippo.php');

### Installing using Laravel 
[bmartus](https://github.com/bmartus) created an awesome Laravel 5.2 wrapper, [check it out here](https://github.com/bmartus/laravel-shippo).

## Getting Started

Simple usage looks like:

    // Replace <API-KEY> with your Shippo API Key
    Shippo::setApiKey("<API-KEY>");
    $address = Shippo_Address::
        create(
            array(
                 'object_purpose' => 'QUOTE',
                 'name' => 'John Smith',
                 'company' => 'Initech',
                 'street1' => 'Greene Rd.',
                 'street_no' => '6512',
                 'street2' => '',
                 'city' => 'Woodridge',
                 'state' => 'IL',
                 'zip' => '60517',
                 'country' => 'US',
                 'phone' => '123 353 2345',
                 'email' => 'jmercouris@iit.com',
                 'metadata' => 'Customer ID 234;234'
            ));
            
        var_dump($address);
    
        // Please check Example.php for more examples
        
## Documentation

Please see [https://goshippo.com/shipping-api/](https://goshippo.com/shipping-api/) for up-to-date documentation.
