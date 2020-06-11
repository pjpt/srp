<?php

include('server.php');
require('config.php');

require 'vendor/autoload.php';
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$success = true;
$error = "Payment Failed";

$ticket = $_SESSION['vibcon_ticket'];

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
        $ticket['payment_id'] = $payment['id'];
        $ticket['payment_email'] = $payment['email'];
        $ticket['payment_contact'] = $payment['contact'];
        $ticket['amount'] = $payment['amount']/100;
        $api->utility->verifyPaymentSignature($attributes);
    }
    catch(SignatureVerificationError $e)
    {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }
}

if ($success === true) {
    if ($ticket['delcard'] === 'delcard') {
        $delcard = array(
			'user_id' => $_SESSION['user']['user_id'],
			'count' => '1',
			'amount' => '400',
			'payment_id' => $ticket['payment_id'],
			'payment_email' => $ticket['payment_email'],
            'payment_contact' => $ticket['payment_contact'],
        );
        $ticket['delcard_id'] = add_delcard($delcard, $_SESSION['user']);
    }

    add_vibcon_ticket($ticket, $_SESSION['user']);
    header('location: ticket.php');
} else {
    $html = "<p>Your payment failed</p>
             <p>{$error}</p>";
}

echo $html;
