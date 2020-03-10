<?php

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	use Razorpay\Api\Api;

	require 'vendor/autoload.php';
	include('config.php');

	/*
		$user = array(
			'user_id' => '',
			'first_name' => '',
			'last_name' => '',
			'email' => '',
			'college_name' => '',
			'mobile_number' => '',
			'tickets' => $tickets

		);
	*/
	
	function add_user_DB($firstname, $lastname, $college, $mobile, $email) {
		global $db;

		$query = "INSERT INTO users(first_name, last_name, college_name, mobile_number, email) VALUES('$firstname', '$lastname', '$college', '$mobile', '$email')";
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
		$tickets = get_tickets($temp_user['user_id']);

		$user = array(
			'user_id' => $temp_user['user_id'],
			'first_name' => $temp_user['first_name'],
			'last_name' => $temp_user['last_name'],
			'college_name' => $temp_user['college_name'],
			'mobile_number' => $temp_user['mobile_number'],
			'email' => $temp_user['email'],
			'tickets' => $tickets
		);

		return $user;
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

	/*
		$ticket = array(
			'ticket_id' => '',
			'event_code' => '',
			'amount' => '',
			'user_id' => '',
			'payment_id' => '',
			'payment_email' => '',
			'payment_contact' => '',
			'booking_date' => ''
		);
		$tickets = [];
	*/

	function add_ticket($ticket, $user) {
		global $db;

		$event_code = $ticket['event_code'];
		$amount = $ticket['amount'];
		$user_id = $user['user_id'];
		$payment_id = $ticket['payment_id'];
		$payment_email = $ticket['payment_email'];
		$payment_contact = $ticket['payment_contact'];
		$booking_date = date('Y-m-d H:i:s');

		$_SESSION['event_code'] = $event_code;
		$_SESSION['payment_id'] = $payment_id;
		$query = "INSERT INTO tickets
				  (event_code, amount, user_id, payment_id, payment_email, payment_contact, booking_date)
				  VALUES('$event_code', '$amount', '$user_id', '$payment_id', '$payment_email', '$payment_contact', '$booking_date')";
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
				'payment_contact' => $row['payment_contact'],
				'booking_date' => $row['booking_date']
			));
		}
		
		return $tickets;
	}

	function get_ticket_count($event_code) {
		global $db;
		$q = "SELECT * FROM tickets WHERE event_code='$event_code'";
		$r = mysqli_query($db, $q);
		return mysqli_num_rows($r);
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
			'payment_contact' => $row['payment_contact'],
			'booking_date' => $row['booking_date']
		);
		return $ticket;
	}

	/*
	$vibcon_ticket = array(
		'ticket_id' => '',
		'user_id' => '',
		'amount' => '',
		'workshop_a' => '',
		'workshop_b' => '',
		'case_presentation' => '',
		'paper_presentation' => '',
		'quiz' => '',
		'symposium' => '',
		'payment_id' => '',
		'payment_email' => '',
		'payment_contact' => '',
		'booking_date' => ''
	);
	$tickets = [];
	*/
	function add_vibcon_ticket($ticket, $user) {
		global $db;

		$event_code = $ticket['event_code'];
		$amount = $ticket['amount'];
		$user_id = $user['user_id'];
		$payment_id = $ticket['payment_id'];
		$payment_email = $ticket['payment_email'];
		$payment_contact = $ticket['payment_contact'];
		
		$workshop_a = $ticket['workshop_a'];
		$workshop_b = $ticket['workshop_b'];
		$case_presentation = $ticket['case_presentation'];
		$paper_presentation = $ticket['paper_presentation'];
		$quiz = $ticket['quiz'];
		$symposium = $ticket['symposium'];
		$delcard = $ticket['delcard'];
		$delcard_id = $ticket['delcard_id'];

		$booking_date = date('Y-m-d H:i:s');

		$query = "INSERT INTO vibcon_tickets
				  (user_id, delcard_id, workshop_a, workshop_b, case_presentation, paper_presentation, quiz, symposium, delcard, amount, payment_id, payment_email, payment_contact, booking_date)
				  VALUES('$user_id', '$delcard_id', '$workshop_a', '$workshop_b', '$case_presentation', '$paper_presentation', '$quiz', '$symposium', '$delcard', '$amount', '$payment_id', '$payment_email', '$payment_contact', '$booking_date')";
		mysqli_query($db, $query);
	}

	/*
		$delcard = array(
			'delcard_id' => '',
			'user_id' => '',
			'count' => '',
			'amount' => '',
			'payment_id' => '',
			'payment_email' => '',
			'payment_contact' => '',
			'booking_date' => ''
		);
	*/

	function add_delcard($delcard, $user) {
		global $db;

		$user_id = $delcard['user_id'];
		$count = $delcard['count'];		
		$amount = $delcard['amount'];
		$payment_id = $delcard['payment_id'];
		$payment_email = $delcard['payment_email'];
		$payment_contact = $delcard['payment_contact'];
		$booking_date = date('Y-m-d H:i:s');

		$_SESSION['log'] = $delcard;
		$query = "INSERT INTO delcards
				  (user_id, count, amount, payment_id, payment_email, payment_contact, booking_date)
				  VALUES('$user_id', '$count', '$amount', '$payment_id', '$payment_email', '$payment_contact', '$booking_date')";
		mysqli_query($db, $query);

		$q = "SELECT * FROM delcards WHERE payment_id='$payment_id'";
		$r = mysqli_query($db, $q);
		$row = mysqli_fetch_assoc($r);

		return (int)$row['delcard_id'];
	}
	
	/*
		$event = array(
			'event_code' => '',
			'event_name' => '',
			'price_bmc' => ''
			'price_oth' => ''
			'max_entries' => ''
		);
		$events = [ 'event_code' => $event ];
	*/
	
	function get_events() {
		global $db;

		$query = "SELECT * FROM events";
		$result = mysqli_query($db, $query); 

		$events = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$events[$row['event_code']] = array(
					'event_code' => $row['event_code'],
					'event_name' => $row['event_name'],
					'price_bmc' => $row['price_bmc'],
					'price_oth' => $row['price_oth'],
					'max_entries' => $row['max_entries']
			);
		}
		return $events;
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