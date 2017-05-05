# Shippo PHP API wrapper
[![Build Status](https://travis-ci.org/goshippo/shippo-php-client.svg?branch=master)](https://travis-ci.org/goshippo/shippo-php-client)

Shippo is a shipping API that connects you with [multiple shipping carriers](https://goshippo.com/carriers/) (such as USPS, UPS, DHL, Canada Post, Australia Post, UberRUSH and many others) through one interface.

Print a shipping label in 10 mins using our default USPS and DHL Express accounts. No need to register for a carrier account to get started.

## Requirements

* PHP 5.6 or later.

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

In Laravel you can install the library as normal. Then within you `app/Providers/AppServiceProvider.php` file's `boot()` method add the following:

```php
\Shippo::setApiKey(env('SHIPPO_API_KEY'));
```

To take advantage of configuration caching, you can set a config parameter in `config/services.php` and retrieve your API key through the configuration.

```php
\Shippo::setApiKey($this->app['config']['services.shippo.key']);
```

From here you can use the Shippo library anywhere in your application without setting the key when accessing it.

### Testing
After installing the dependencies above, the test suite may be run:

        ./vendor/bin/phpunit

You may also run individual tests:

        ./vendor/bin/phpunit AddressTest.php


## Getting Started

Simple usage looks like:
```php
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
```        

We've created a number of examples to cover the most common use cases. You can find the sample code files in the [examples folder](examples/).
Some of the use cases we covered include:

* [Basic domestic shipment](examples/basic-shipment.php)
* [International shipment](examples/international-shipment.php)  - Custom forms, interntational destinations
* [Price estimation matrix](examples/estimate-shipping-prices.php)
* [Retrieve rates, filter by delivery time and purchase cheapest label](examples/filter-by-delivery-time.php)
* [Retrieve rates, purchase label for fastest delivery option](examples/purchase-fastest-service.php)
* [Retrieve rates so customer can pick preferred shipping method, purchase label](examples/get-rates-to-show-customer.php)

## Documentation

Please see [https://goshippo.com/docs](https://goshippo.com/docs) for up-to-date documentation.

## About Shippo

Connect with multiple different carriers, get discounted shipping labels, track parcels, and much more with just one integration. You can use your own carrier accounts or take advantage of our discounted rates with the USPS and DHL Express. Using Shippo makes it easy to deal with multiple carrier integrations, rate shopping, tracking and other parts of the shipping workflow. We provide the API and dashboard for all your shipping needs.

## Supported Features

The Shippo API provides in depth support of carrier and shipping functionalities. Here are just some of the features we support through the API:

## Supported Features

The Shippo API provides in depth support of carrier and shipping functionalities. Here are just some of the features we support through the API:

* Shipping rates & labels - [Docs](https://goshippo.com/docs/first-shipment)
* Tracking for any shipment with just the tracking number - [Docs](https://goshippo.com/docs/tracking)
* Batch label generation - [Docs](https://goshippo.com/docs/batch)
* Multi-piece shipments - [Docs](https://goshippo.com/docs/multipiece)
* Manifests and SCAN forms - [Docs](https://goshippo.com/docs/manifests)
* Customs declaration and commercial invoicing - [Docs](https://goshippo.com/docs/international)
* Address verification - [Docs](https://goshippo.com/docs/address-validation)
* Consolidator support including:
	* DHL eCommerce
	* UPS Mail Innovations
	* FedEx Smartpost
* Additional services: cash-on-delivery, certified mail, delivery confirmation, and more - [Docs](https://goshippo.com/docs/reference#shipment-extras)
