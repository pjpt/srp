<?php
    include('server.php');

    require 'vendor/autoload.php';

    if (isset($_POST['id_token'])) {
        $id_token = $_POST['id_token'];
        $client = new Google_Client();
        $client->setAuthConfig('credentials.json');
        $payload = $client->verifyIdToken($id_token);
        if ($payload) {
            $_SESSION['temp_email'] = $payload['email'];
            $_SESSION['temp_firstname'] = $payload['given_name'];
            $_SESSION['temp_lastname'] =$payload['family_name'];
            echo 'DONE';
        }
    }
?>