<?php
    include('server.php');

    require 'vendor/autoload.php';

    if (isset($_POST['id_token'])) {
        $id_token = $_POST['id_token'];
        $client = new Google_Client();
        $client->setAuthConfig('credentials.json');
        $payload = $client->verifyIdToken($id_token);
        if ($payload) {
            $email = $payload['email'];
            $firstname = $payload['given_name'];
            $lastname =$payload['family_name'];

			if (is_user_exist_DB($email) == false) {
				add_user_DB($firstname, $lastname, $email);
            }
            
            $_SESSION['user'] = get_user($email);
            echo 'DONE';
        }
    }
?>