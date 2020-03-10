<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'k');
define('DB_PASSWORD', '');
define('DB_NAME', 'event_web');

define('EMAIL', 'kartikprajapati789@gmail.com');
define('EMAIL_PASS', 'yb#198643');

$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

$keyId = 'rzp_test_y8cFNdKazRzV7i';
$keySecret = 'QJh1TxR7DsJPzTVXnWlPLetT';
$displayCurrency = 'INR';

//These should be commented out in production
// This is for error reporting
// Add it to config.php to report any errors
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
