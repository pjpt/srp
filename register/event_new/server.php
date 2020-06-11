<?php
	session_start();

	include('util.php');
	include('config.php');

	$errors = array();

	require 'vendor/autoload.php';
/*
	$user = array(
		'user_id' => '',
		'first_name' => '',
		'last_name' => '',
		'email' => '',
		'groups' => $groups,
		'tickets' => $tickets 
	);

	$ticket = array(
		'ticket_id' => '',
		'event_code' => '',
		'amount' => '',
		'user_id' => '',
		'payment_id' => '',
		'payment_email' => '',
		'payment_contact' => ''
	);
	$tickets = [];

	$event = array(
		'event_code' => '',
		'event_name' => '',
		'registration_fee' => ''
	);
	$events = [];

	$group = array(
		'group_id' => '',
		'group_name' => '',
		'user' => ',',
	);
	$groups = [];
*/

	if (isset($_POST['signup'])) {
		$firstname = mysqli_real_escape_string($db, $_POST['firstname']);
		$lastname = mysqli_real_escape_string($db, $_POST['lastname']);
		$mobile = mysqli_real_escape_string($db, $_POST['mob']);
		$selected_clg = $_POST['colleges'];
		$clg_name = mysqli_real_escape_string($db, $_POST['college']);
		$college = '';

		if (empty($selected_clg)) {
			array_push($errors, "Please Select College");
		}

		if ($selected_clg === 'bmc') {
			$college = "Baroda Medical College";
		} else {
			if (empty($clg_name)) {
				array_push($errors, "College Name is reqired");
			} else {
				$college = $clg_name; 
			}
		}

		if (empty($firstname)) {
			array_push($errors, "Firstname is requred.");
		}

		if (empty($lastname)) {
			array_push($errors, "Lastname is requred.");
		}

		if (count($errors) == 0) {
			$email = $_SESSION['temp_email'];
			add_user_DB($firstname, $lastname, $college, $mobile, $email);
			$_SESSION['user'] = get_user($email);
			header('location: ticket.php');
		}

	}
	if (isset($_POST['admin_login'])) {
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$pass = mysqli_real_escape_string($db, $_POST['password']);
		
		if (empty($email)) {
			array_push($errors, "Email is reqired");
		}
		if (empty($pass)) {
			array_push($errors, "Password is requred");
		}
		
		$pass_hash = hash('sha256', $_POST['password']);
		$email_hash = hash('sha256', $_POST['email']);

		if ($pass_hash == '813a4d4c8839c7817d0c67165f3c550c34f0b7308ec0ee9659664344f0d29880' && $email_hash == '7932b2e116b076a54f452848eaabd5857f61bd957fe8a218faf216f24c9885bb') {
			;
		} else {
			array_push($errors, "Incorrect Email or Password");
		}

		if (count($errors) == 0) {
			$_SESSION['email'] = $email;
			header('location: dashboard.php');
		}
	}
?>