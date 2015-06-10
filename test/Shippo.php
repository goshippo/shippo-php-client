<?php

echo "Running the Shippo PHP bindings test suite.\n" . "If you're trying to use the Shippo PHP bindings you'll probably want " . "to require('lib/Shippo.php'); instead of this file\n";

$testURI = '/simpletest/autorun.php';

$ok = @include_once(dirname(__FILE__) . $testURI);
if (!$ok) {
    $ok = @include_once(dirname(__FILE__) . '/../vendor/simpletest' . $testURI);
}
if (!$ok) {
    echo "MISSING DEPENDENCY: The Shippo API test cases depend on SimpleTest. " . "Download it at <http://www.simpletest.org/>, and either install it " . "in your PHP include_path or put it in the test/ directory.\n";
    exit(1);
}

// Throw an exception on any error
// @codingStandardsIgnoreStart
function exception_error_handler($errno, $errstr, $errfile, $errline)
{
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
// @codingStandardsIgnoreEnd
set_error_handler('exception_error_handler');
error_reporting(E_ALL | E_STRICT);

// Shippo Library
require_once(dirname(__FILE__) . '/../lib/Shippo.php');
// Base Class that all tests extend, extends UnitTestCase
require_once(dirname(__FILE__) . '/Shippo/ShippoTest.php');

require_once(dirname(__FILE__) . '/Shippo/ApiRequestorTest.php');
require_once(dirname(__FILE__) . '/Shippo/AuthenticationErrorTest.php');
require_once(dirname(__FILE__) . '/Shippo/Error.php');
require_once(dirname(__FILE__) . '/Shippo/InvalidRequestErrorTest.php');
require_once(dirname(__FILE__) . '/Shippo/ObjectTest.php');
require_once(dirname(__FILE__) . '/Shippo/UtilTest.php');
//
// // // Shippo API Tests
require_once(dirname(__FILE__) . '/Shippo/AddressTest.php');
require_once(dirname(__FILE__) . '/Shippo/ParcelTest.php');
require_once(dirname(__FILE__) . '/Shippo/ShipmentTest.php');
require_once(dirname(__FILE__) . '/Shippo/RateTest.php');
require_once(dirname(__FILE__) . '/Shippo/TransactionTest.php');
require_once(dirname(__FILE__) . '/Shippo/CustomsItemTest.php');
require_once(dirname(__FILE__) . '/Shippo/CustomsDeclarationTest.php');
require_once(dirname(__FILE__) . '/Shippo/RefundTest.php');
require_once(dirname(__FILE__) . '/Shippo/ManifestTest.php');
require_once(dirname(__FILE__) . '/Shippo/CarrierAccountTest.php');

