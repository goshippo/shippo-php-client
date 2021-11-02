<?php

// Tested on PHP 5.2, 5.3
if (!function_exists('curl_init')) {
    throw new Exception('Shippo needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
    throw new Exception('Shippo needs the JSON PHP extension.');
}
if (!function_exists('mb_detect_encoding')) {
    throw new Exception('Shippo needs the Multibyte String PHP extension.');
}

// Shippo singleton
require(dirname(__FILE__) . '/Shippo/Shippo.php');

// Utilities
require(dirname(__FILE__) . '/Shippo/Util.php');
require(dirname(__FILE__) . '/Shippo/Util/Set.php');

// Errors
require(dirname(__FILE__) . '/Shippo/Error.php');
require(dirname(__FILE__) . '/Shippo/ApiError.php');
require(dirname(__FILE__) . '/Shippo/ApiConnectionError.php');
require(dirname(__FILE__) . '/Shippo/AuthenticationError.php');
require(dirname(__FILE__) . '/Shippo/InvalidRequestError.php');
require(dirname(__FILE__) . '/Shippo/RateLimitError.php');

// Plumbing
require(dirname(__FILE__) . '/Shippo/Object.php');
require(dirname(__FILE__) . '/Shippo/ApiRequestor.php');
require(dirname(__FILE__) . '/Shippo/ApiResource.php');
require(dirname(__FILE__) . '/Shippo/CurlClient.php');
require(dirname(__FILE__) . '/Shippo/SingletonApiResource.php');
require(dirname(__FILE__) . '/Shippo/AttachedObject.php');
require(dirname(__FILE__) . '/Shippo/List.php');

// Shippo API Resources
require(dirname(__FILE__) . '/Shippo/Address.php');
require(dirname(__FILE__) . '/Shippo/Parcel.php');
require(dirname(__FILE__) . '/Shippo/Shipment.php');
require(dirname(__FILE__) . '/Shippo/Rate.php');
require(dirname(__FILE__) . '/Shippo/Transaction.php');
require(dirname(__FILE__) . '/Shippo/CustomsItem.php');
require(dirname(__FILE__) . '/Shippo/CustomsDeclaration.php');
require(dirname(__FILE__) . '/Shippo/Refund.php');
require(dirname(__FILE__) . '/Shippo/Manifest.php');
require(dirname(__FILE__) . '/Shippo/CarrierAccount.php');
require(dirname(__FILE__) . '/Shippo/Track.php');
require(dirname(__FILE__) . '/Shippo/Batch.php');
require(dirname(__FILE__) . '/Shippo/Order.php');
require(dirname(__FILE__) . '/Shippo/Pickup.php');
