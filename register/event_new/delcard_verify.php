<?php

include('server.php');
require('config.php');

require 'vendor/autoload.php';
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$success = true;
$error = "Payment Failed";

$delcard = $_SESSION['delcard'];

if (empty($_POST['razorpay_payment_id']) === false)
{
    $api = new Api($keyId, $keySecret);

    try
    {
        // Please note that the razorpay order ID must
        // come from a trusted source (session here, but
        // could be database or something else)
        $attributes = array(
            'razorpay_order_id' => $_SESSION['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );

        $payment = $api->payment->fetch($_POST['razorpay_payment_id']);
        $delcard['payment_id'] = $payment['id'];
        $delcard['payment_email'] = $payment['email'];
        $delcard['payment_contact'] = $payment['contact'];
        $delcard['amount'] = $payment['amount']/100;
        $api->utility->verifyPaymentSignature($attributes);
    }
    catch(SignatureVerificationError $e)
    {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }
}

if ($success === true) {
    add_delcard($delcard, $_SESSION['user']);
    header('location: ticket.php');
} else {
    $html = "<p>Your payment failed</p>
             <p>{$error}</p>";
}

echo $html;
