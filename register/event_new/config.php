<?php

define('DB_SERVER', '');
define('DB_USERNAME', '');
define('DB_PASSWORD', '');
define('DB_NAME', '');

define('EMAIL', '');
define('EMAIL_PASS', '');

$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

$keyId = '';
$keySecret = '';
$displayCurrency = 'INR';

//These should be commented out in production
// This is for error reporting
// Add it to config.php to report any errors
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
