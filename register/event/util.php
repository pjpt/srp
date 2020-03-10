<?php

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	use Razorpay\Api\Api;

	require 'vendor/autoload.php';
	include('config.php');

	/*
		$event = array(
			'event_code' => '',
			'event_name' => '',
			'registration_fee' => ''
		);
		$events = [];

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

		$group = array(
			'group_id' => '',
			'group_name' => '',
			'user' => ',',
		);
		$groups = [];

		$user = array(
			'user_id' => '',
			'first_name' => '',
			'last_name' => '',
			'email' => '',
			'groups' => $groups,
			'tickets' => $tickets 
		);
	*/
	
	function add_user_DB($firstname, $lastname, $email) {
		global $db;

		$query = "INSERT INTO users (first_name, last_name, email) VALUES('$firstname', '$lastname', '$email')";
		mysqli_query($db, $query);
	}

	function get_user_DB($email) {
		global $db;
		$user_check_query = "SELECT * FROM users WHERE email='$email'";
		$result = mysqli_query($db, $user_check_query);
		return $result;
	}

	function get_user($email) {
		$result = get_user_DB($email);
		$temp_user = mysqli_fetch_assoc($result);
		$groups = get_groups($temp_user['user_id']);
		$tickets = get_tickets($temp_user['user_id']);

		$user = array(
			'user_id' => $temp_user['user_id'],
			'first_name' => $temp_user['first_name'],
			'last_name' => $temp_user['last_name'],
			'email' => $temp_user['email'],
			'groups' => $groups,
			'tickets' => $tickets
		);

		return $user;
	}

	function add_group($group_name, $email1, $email2, $email3, $event_code) {
		global $db;

		$query = "INSERT INTO groups(g_name, event_code) VALUES ('$group_name', '$event_code')";
		mysqli_query($db, $query);

		$query = "SELECT * FROM groups WHERE g_name='$group_name'";
		$r = mysqli_query($db, $query);
		$row = mysqli_fetch_assoc($r);

		$g_id = $row['g_id'];
		$user_id = get_user($email1)['user_id'];
		$q = "INSERT INTO user_group(g_id, user_id) VALUES ('$g_id', '$user_id')";
		mysqli_query($db, $q);

		$user_id = get_user($email2)['user_id'];
		$q = "INSERT INTO user_group(g_id, user_id) VALUES ('$g_id', '$user_id')";
		mysqli_query($db, $q);

		$user_id = get_user($email3)['user_id'];
		$q = "INSERT INTO user_group(g_id, user_id) VALUES ('$g_id', '$user_id')";
		mysqli_query($db, $q);
	}

	function get_group($g_id) {
		global $db;

		$query = "SELECT * FROM groups WHERE g_id='$g_id'";
		$res = mysqli_query($db, $query);
		$group = mysqli_fetch_assoc($res);
		return $group;
	}

	function get_groups($user_id) {
		global $db;

		$query = "SELECT * FROM user_group WHERE user_id='$user_id'";
		$result = mysqli_query($db, $query);
		$groups = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$g_id = $row['g_id'];
			$group = get_group($g_id);
			$query = "SELECT * FROM users WHERE user_id IN (SELECT user_id FROM user_group WHERE g_id=$g_id)";
			$res = mysqli_query($db, $query);
			$users = [];
			while ($r = mysqli_fetch_assoc($res)) {
				array_push($users, $r['email']);
			}
			array_push($groups, array($g_id, $group['g_name'], $group['event_code'], $users));
		}
		return $groups;
	}

	function add_ticket($ticket, $user) {
		global $db;

		$event_code = $ticket['event_code'];
		$amount = $ticket['amount'];
		$user_id = $user['user_id'];
		$payment_id = $ticket['payment_id'];
		$payment_email = $ticket['payment_email'];
		$payment_contact = $ticket['payment_contact'];

		$_SESSION['event_code'] = $event_code;
		$_SESSION['payment_id'] = $payment_id;
		$query = "INSERT INTO tickets
				  (event_code, amount, user_id, payment_id, payment_email, payment_contact)
				  VALUES('$event_code', '$amount', '$user_id', '$payment_id', '$payment_email', '$payment_contact')";
		mysqli_query($db, $query);

		$query = "SELECT * FROM tickets WHERE payment_id='$payment_id'";
		$result = mysqli_query($db, $query);
		$row = mysqli_fetch_assoc($result);
		$ticket['ticket_id'] = $row['ticket_id'];
		array_push($_SESSION['user']['tickets'], $ticket);
	}

	function get_tickets($user_id) {
		global $db;

		$query = "SELECT * FROM tickets WHERE user_id='$user_id'";
		$result = mysqli_query($db, $query);
		$tickets = [];
		while ($row = mysqli_fetch_assoc($result)) {
			array_push($tickets, array(
				'ticket_id' => $row['ticket_id'],
				'event_code' => $row['event_code'],
				'amount' => $row['amount'],
				'payment_id' => $row['payment_id'],
				'payment_email' => $row['payment_email'],
				'payment_contact' => $row['payment_contact']
			));
		}
		
		return $tickets;
	}

	function get_ticket($ticket_id) {
		global $db;

		$query = "SELECT * FROM tickets WHERE ticket_id=$ticket_id";
		$result = mysqli_query($db, $query);

		$row = mysqli_fetch_assoc($result);
		$ticket = array(
			'ticket_id' => $row['ticket_id'],
			'event_code' => $row['event_code'],
			'amount' => $row['amount'],
			'payment_id' => $row['payment_id'],
			'payment_email' => $row['payment_email'],
			'payment_contact' => $row['payment_contact']
		);
		return $ticket;
	}

	function get_events() {
		global $db;

		$query = "SELECT * FROM events";
		$result = mysqli_query($db, $query); 

		$events = [];
		while ($row = mysqli_fetch_assoc($result)) {
			array_push($events, array(
					'event_code' => $row['event_code'],
					'event_name' => $row['event_name'],
					'registration_fee' => $row['registration_fee'],
					'group_event' => $row['group_event']
			));
		}
		return $events;
	}


	function gen_otp() {
		$otp = strval(rand(100000, 999999));
		$_SESSION['otp'] = $otp;
		return $otp;
	}

	function check_otp($otp) {
		if ($otp === $_SESSION['otp']) {
			unset($_SESSION['otp']);
			return true;
		}
		return false;
	}

	function is_user_exist_DB($email) {
		global $db;
		$result = get_user_DB($email);
		$user = mysqli_fetch_assoc($result);
		if ($user) {
			if ($user['email'] === $email) {
				return true;
			}
		}
		return false;
	}

	function send_mail($email) {
		$mail = new PHPMailer(true);
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
	    $mail->Username = EMAIL;
	    $mail->Password = EMAIL_PASS;
	    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	    $mail->Port = 587;
	    $mail->setFrom('kartikprajapati789@gmail.com', 'Mailer');
		$mail->addAddress($email, 'kartik prajapati');
		$mail->isHTML(true);
		$mail->Subject = 'OTP';
		$otp = gen_otp();
		$mail->Body = 'Your OTP is: ' . $otp;
   		$mail->send();
	}

	function validate_email($email) {
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		return false;
	}
?>