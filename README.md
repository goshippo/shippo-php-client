#Shippo PHP API wrapper

Shippo is a shipping API that connects you with [multiple shipping carriers](https://goshippo.com/carriers/) (such as USPS, UPS, DHL, Canada Post, Australia Post, UberRUSH and many others) through one interface.

Print a shipping label in 10 mins using our default USPS and DHL Express accounts. No need to register for a carrier account to get started.

## Requirements

* PHP 5.2 or later.

* [Shippo account](https://goshippo.com/) - free to sign up, free to use the API. Only pay to print a live label, test labels are free. 

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
                 'street1' => '6512 Greene Rd.',
                 'city' => 'Woodridge',
                 'state' => 'IL',
                 'zip' => '60517',
                 'country' => 'US',
                 'phone' => '773 353 2345',
                 'email' => 'jmercouris@iit.com',
                 'metadata' => 'Customer ID 23424'
            ));
            
        var_dump($address);
    
        // Please check Example.php for more examples
        
## Documentation

Please see [https://goshippo.com/docs](https://goshippo.com/docs) for up-to-date documentation.

## About Shippo

Connect with multiple different carriers, get discounted shipping labels, track parcels, and much more with just one integration. You can use your own carrier accounts or take advantage of our discounted rates with the USPS and DHL Express. Using Shippo makes it easy to deal with multiple carrier integrations, rate shopping, tracking and other parts of the shipping workflow. We provide the API and dashboard for all your shipping needs.

## Supported Features

The Shippo API provides in depth support of carrier and shipping functionalities. Here are just some of the features we support through the API:

* Shipping rates & labels
* Tracking for any shipment with just the tracking number
* Batch label generation
* Multi-piece shipments
* Manifests and SCAN forms
* Customs declaration and commercial invoicing
* Address verification
* Signature and adult signature confirmation
* Consolidator support including:
	* DHL eCommerce
	* UPS Mail Innovations
	* FedEx Smartpost
* Additional services: cash-on-delivery, certified mail, delivery confirmation, and more.
