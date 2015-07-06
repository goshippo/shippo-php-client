#Shippo PHP API wrapper

Shippo is a shipping API that connects you with multiple shipping carriers (such as USPS, UPS, and Fedex) through one interface and provides you with great discounts on shipping rates.

Don't have an account? Sign up at https://goshippo.com/

## Requirements

PHP 5.2 and later.

## Installation

### Installing using Composer

* Get Composer [http://getcomposer.org/]
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


## Getting Started

Simple usage looks like:

    // Replace <API-KEY> with your Shippo API Key
    Shippo::setApiKey("<API-KEY>");
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
        echo(var_dump($address));
    
        // Please check Example.php for more examples
        
## Documentation

Please see https://goshippo.com/docs/ for up-to-date documentation.
