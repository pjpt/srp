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
		$email = mysqli_real_escape_string($db, $_POST['email']);

		if (empty($firstname)) {
			array_push($errors, "Firstname is requred.");
		}

		if (empty($lastname)) {
			array_push($errors, "Lastname is requred.");
		}

		if (empty($email)) {
			array_push($errors, "Email is requred.");
		}

		if (validate_email($email) == false) {
			array_push($errors, "Email is not valid");
		}

		if (is_user_exist_DB($email)) {
			array_push($errors, "User already exists");
		}

		if (count($errors) == 0) {
			send_mail($email);
			$_SESSION['firstname'] = $firstname;
			$_SESSION['lastname'] = $lastname;
			$_SESSION['email'] = $email;
			header('location: verification.php');
		}
	}

	if (isset($_POST['otp_submit'])) {
		$otp_input = mysqli_real_escape_string($db, $_POST['otp']);
		$firstname = $_SESSION['firstname'];
		$lastname = $_SESSION['lastname'];
		$email = $_SESSION['email'];
		
		echo $email;
		if (check_otp($otp_input)) {
			add_user_DB($firstname, $lastname, $email);
			$_SESSION['user'] = get_user($email);
			header('location: ticket.php');
		} else {
			array_push($errors, "Wrong OTP");
		}
	}

	if (isset($_POST['login'])) {
		$email = mysqli_real_escape_string($db, $_POST['email']);
		if (empty($email)) {
			array_push($errors, "email is reqired");
		}
		if (validate_email($email) == false) {
			array_push($errors, "Email is not valid");
		}
		
		if (count($errors) == 0) {
			if (is_user_exist_DB($email)) {
				$_SESSION['user'] = get_user($email);
				header('location: ticket.php');
			} else {
				array_push($errors, "User not exists");
			}
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

		if ($pass_hash == 'ca49139f6afb3b1ac1ff22657a4223172f8d2f53dd6f8005750dd0a83b10376d' && $email_hash == '7932b2e116b076a54f452848eaabd5857f61bd957fe8a218faf216f24c9885bb') {
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