<?php
	include('server.php');

	use Razorpay\Api\Api;

	if (!isset($_SESSION['user'])) {
		header('location: index.php');
	}

	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['user']);
		header("location: index.php");
	}

	$user = $_SESSION['user'];
	$tickets = $user['tickets'];
	$events = get_events();
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Buy Ticket</title>
        <link href="index.css" rel="stylesheet">
    </head>
    <body>
    	<header>
    		<h4><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h4>
	    	<a href="ticket.php?logout='1'" style="color:red">LOGOUT</a>
    	</header>
    	<h1>My Tickets</h1>
    	<div class="tickets">
    		<div class="box">
    			<h2>Your Event Tickets</h2>
  				<table width="100%">
					<tr>
						<th>Code</th>
						<th>Event Name</th>
						<th>Amount</th>
						<th>Payment_id</th>
					</tr>
				<?php
					$tickets = $user['tickets'];
					for ($i = 0; $i < sizeof($tickets); $i++) {
						echo '<tr>';
						echo '<td>' . $tickets[$i]['event_code'] . $tickets[$i]['ticket_id'] . '</td>';
						echo '<td>' . $events[$tickets[$i]['event_code']]['event_name'] . '</td>';
						echo '<td>' . $tickets[$i]['amount'] . '</td>';
						echo '<td>' . $tickets[$i]['payment_id'] . '</td>';
  						echo '</tr>';
					}
  				?>
				</table>
				<h2>&nbsp;</h2>
				<h2>Your Vibcon Ticket</h2>
  				<table width="100%">
					<tr>
						<th>Workshop A</th>
						<th>Workshop B</th>
						<th>Case Presentation</th>
						<th>Paper Presentation</th>
						<th>Quiz</th>
						<th>Symposium</th>
						<th>Amount</th>
						<th>Payment_id</th>
					</tr>
				<?php
					$user_id = $user['user_id'];
					$q = "SELECT * FROM vibcon_tickets WHERE user_id = '$user_id'";
					$r = mysqli_query($db, $q);
					$vibcon_ticket = mysqli_fetch_assoc($r);
					if ($vibcon_ticket) {
						echo '<tr>';
						echo '<td>' . $vibcon_ticket['workshop_a'] . '</td>';
						echo '<td>' . $vibcon_ticket['workshop_b'] . '</td>';
						echo '<td>' . ($vibcon_ticket['case_presentation'] ? "✓" : "✕") . '</td>';
						echo '<td>' . ($vibcon_ticket['paper_presentation'] ? "✓" : "✕") . '</td>';
						echo '<td>' . ($vibcon_ticket['quiz'] ? "✓" : "✕") . '</td>';
						echo '<td>' . ($vibcon_ticket['symposium'] ? "✓" : "✕") . '</td>';
						echo '<td>' . $vibcon_ticket['amount'] . '</td>';
						echo '<td>' . $vibcon_ticket['payment_id'] . '</td>';
						echo '</tr>';
					}
  				?>
				</table>
				<h2>&nbsp;</h2>
				<h2>Your Delcards</h2>
  				<table width="100%">
					<tr>
						<th>Delcard Id</th>
						<th>Numbers of Delcards</th>
						<th>Amount</th>
						<th>Payment Id</th>
					</tr>
				<?php
					$user_id = $user['user_id'];
					$q = "SELECT * FROM delcards WHERE user_id = '$user_id'";
					$r = mysqli_query($db, $q);
					$delcard = mysqli_fetch_assoc($r);
					if ($delcard) {
						echo '<tr>';
						echo '<td>' . 'DEL_' . $delcard['delcard_id'] . '</td>';
						echo '<td>' . $delcard['count'] . '</td>';
						echo '<td>' . $delcard['amount'] . '</td>';
						echo '<td>' . $delcard['payment_id'] . '</td>';
						echo '</tr>';
					}
  				?>
				</table>
    		</div>
    		<div class="links">
    			<h2>New Ticket</h2>
				<form action="" method="GET">
					<div class="form-submit">
							<input type="submit" value="VIBCON" name="vibcon_proceed">
					</div>
					<div class="form-submit">
							<input type="submit" value="DELCARD" name="delcard_proceed">
					</div>
						<div class="form-submit">
							<input type="submit" value="EVENT" name="event_proceed">
					</div>
				</form>
				<?php if(!(isset($_POST['event_pay']) && count($errors) == 0) && isset($_GET['event_proceed'])) : ?>
					<h1>Event Ticket</h1>
					<form action="" method="POST">
						<?php include('errors.php'); ?>
						<div class="form-select">
							<select name="events" id="event">
								<option value="">Please Select</option>
								<?php foreach ($events as $e => $e_val) : ?>
									<option value="<?php echo $e; ?>"><?php echo $e_val['event_name']; ?></option>
								<?php endforeach ?>
							</select>
						</div>
						<div class="total">
							<h3>Total: <span class="rupees" id="price"></span></h3>
						</div>
	                    <div class="form-submit">
	                        	<input type="submit" value="PROCEED" name="event_pay">
	                    </div>
					</form>
					<script type="text/javascript" src="ticket.js"></script>		
				<?php endif ?>
				<?php
	                if (isset($_POST['event_pay'])) {
						$selected_event = $_POST['events'];
						$event = $events[$selected_event];

						if (empty($event)) {
							array_push($errors, "Please select event");
						}

						$user_id = $user['user_id'];
						$event_code = $event['event_code'];
						$check_query = "SELECT * FROM tickets WHERE user_id='$user_id' AND event_code='$event_code'";
						$result = mysqli_query($db, $check_query);
						if (mysqli_num_rows($result) > 0) {
							array_push($errors, 'Already booked');
						}
						
						if (get_ticket_count($event_code) >= (int)$event['max_entries']) {
							array_push($errors, 'All tickets are booked');
						}

						if (count($errors) == 0) {
							require('config.php');
							$price = -1;
							if ($user['college_name'] === 'Baroda Medical College') {
								$price = $event['price_bmc'];
							} else {
								$price = $event['price_oth'];
							}
							$api = new Api($keyId, $keySecret);
							$orderData = [
							    'receipt'         => 3456,
							    'amount'          => $price*100, // 2000 rupees in paise
							    'currency'        => 'INR',
							    'payment_capture' => 1 // auto capture
							];

							$razorpayOrder = $api->order->create($orderData);

							$razorpayOrderId = $razorpayOrder['id'];

							$_SESSION['razorpay_order_id'] = $razorpayOrderId;

							$displayAmount = $amount = $orderData['amount'];

							if ($displayCurrency !== 'INR')
							{
							    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
							    $exchange = json_decode(file_get_contents($url), true);

							    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
							}

							$checkout = 'automatic';

							$data = [
							    "key"               => $keyId,
							    "amount"            => $amount,
							    "name"              => $event['event_name'],
							    "description"       => "",
							    "image"             => "",
							    "prefill"           => [
							    "name"              => "",
							    "email"             => "",
							    "contact"           => "",
							    ],
							    "theme"             => [
							    "color"             => "#F37254"
							    ],
							    "order_id"          => $razorpayOrderId,
							];

							if ($displayCurrency !== 'INR')
							{
							    $data['display_currency']  = $displayCurrency;
							    $data['display_amount']    = $displayAmount;
							}

							$json = json_encode($data);
							$_SESSION['event_code'] = $event_code;
							$_SESSION['amount'] = $price;
							$_SESSION['user_id'] = $user_id;
							
							echo '<h3>Event: ' . $event['event_name'] . '</h3>';
							echo '<h3>Total: ' . $price . '₹</h3>';
							require("checkout/{$checkout}.php");
						}
					}
				?>
				<?php if(!(isset($_POST['delcard_pay']) && count($errors) == 0) && isset($_GET['delcard_proceed'])) : ?>
					<h1>Delcard</h1>
					<form action="" method="POST">
						<?php include('errors.php'); ?>
						<div class="form-email">
							<h3>Number of Delcards</h3>
							<input type="number" id="num_card" value="1" min="1" max="20" name="ncard" required>
						</div>
						<div class="total">
							<h3>Total: <span class="rupees" id="price"></span></h3>
						</div>
	                    <div class="form-submit">
	                        	<input type="submit" value="PROCEED" name="delcard_pay">
	                    </div>
	                </form>
					<script type="text/javascript" src="delcard.js"></script>
            	<?php endif ?>
				<?php
					if (isset($_POST['delcard_pay'])) {
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

						$count = (int)$_POST['ncard'];
						$cost = 0;
						if ($count <= 0) {
							array_push($errors, "Ivalide numbers of card selected");
						}

						if ($count <= 9) {
							$cost += 500 * $count;
						}

						if ($count >= 10) {
							$cost += 450 * $count;
						}

						$user_id = $user['user_id'];
						$check_query = "SELECT * FROM delcards WHERE user_id='$user_id'";
						$result = mysqli_query($db, $check_query);
						if (mysqli_num_rows($result) > 0) {
							array_push($errors, 'Already booked');
						}

						$delcard['count'] = $count;
						$delcard['amount'] = $cost;
						$delcard['user_id'] = $user['user_id'];
						if (count($errors) == 0) {
							require('config.php');
							$price = $cost;
							
							$api = new Api($keyId, $keySecret);
							$orderData = [
							    'receipt'         => 3456,
							    'amount'          => $price*100, // 2000 rupees in paise
							    'currency'        => 'INR',
							    'payment_capture' => 1 // auto capture
							];

							$razorpayOrder = $api->order->create($orderData);

							$razorpayOrderId = $razorpayOrder['id'];

							$_SESSION['razorpay_order_id'] = $razorpayOrderId;

							$displayAmount = $amount = $orderData['amount'];

							if ($displayCurrency !== 'INR')
							{
							    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
							    $exchange = json_decode(file_get_contents($url), true);

							    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
							}

							$checkout = 'delcard_auto';

							$data = [
							    "key"               => $keyId,
							    "amount"            => $amount,
							    "name"              => $event['event_name'],
							    "description"       => "",
							    "image"             => "",
							    "prefill"           => [
							    "name"              => "",
							    "email"             => "",
							    "contact"           => "",
							    ],
							    "theme"             => [
							    "color"             => "#F37254"
							    ],
							    "order_id"          => $razorpayOrderId,
							];

							if ($displayCurrency !== 'INR')
							{
							    $data['display_currency']  = $displayCurrency;
							    $data['display_amount']    = $displayAmount;
							}

							$json = json_encode($data);
							$_SESSION['amount'] = $price;
							$_SESSION['user_id'] = $user_id;
							$_SESSION['delcard'] = $delcard;

							echo '<h3>Total: ' . $price . '₹</h3>';
							require("checkout/{$checkout}.php");
						}
					}
				?>
				<?php if(!(isset($_POST['vibcon_pay']) && count($errors) == 0) && isset($_GET['vibcon_proceed'])) : ?>
					<h1>Vibcon Ticket</h1>
					<h4>Note: <span style='font-weight:normal;'>Team should be Registered by leader only but all members must hold delegation of conference</span></h4>
					<form action="" method="POST">
						<?php include('errors.php'); ?>
						<h3>Delegation Fee: ₹400</h3>
						<h3>Workshop Category A</h3>
						<div class="form-select">
							<select name="workshop_a" id="workshop_a">
								<option value="">None</option>
								<option value="basic surgical skills">Basic Surgical Skills</option>
								<option value="stroke management">Stroke Management</option>
							</select>
						</div>
						<h3>Workshop Category B</h3>
						<div class="form-select">
							<select name="workshop_b" id="workshop_b">
								<option value="">None</option>
								<option value="basics of oncology">Basics of Oncology</option>
								<option value="basics of radiology">Basics of Radiology</option>
								<option value="ecg implementation and red flag shine">ECG Implements and Red Flag Shine</option>
							</select>
						</div>
						<div class="form-check">
							<h3>
								<input type="checkbox" id="case_p" name="case_presentation" value="Case Presentation">
								<label for="case_p">Case Presentation (100₹)</label><br>
							</h3>
						</div>
						<div class="form-check">
							<h3>
								<input type="checkbox" id="paper" name="paper_presentation" value="Paper Presentation">
								<label for="paper">Paper Presentation (100₹)</label><br>
								</h3>
						</div>
						<div class="form-check">
							<h3>
								<input type="checkbox" id="quiz" name="quiz" value="Quiz">
								<label for="quiz">Quiz (300₹/team)</label><br>
								</h3>
						</div>
						<div class="form-check">
							<h3>
								<input type="checkbox" id="symposium" name="symposium" value="symposium">
								<label for="symposium">symposium (800₹/team)</label><br>
							</h3>
						</div>
						<div class="form-check">
							<h3>
								<input type="checkbox" id="vib_del" name="delcard" value="delcard">
								<label for="vib_del">Delcard (400₹/team)</label><br>
							</h3>
						</div>
						<div class="total">
							<h3>Total: <span class="rupees" id="price"></span></h3>
						</div>
	                    <div class="form-submit">
	                        	<input type="submit" value="PROCEED" name="vibcon_pay">
	                    </div>
					</form>
					<script type="text/javascript" src="vibcon.js"></script>
				<?php endif ?>
				<?php
					if (isset($_POST['vibcon_pay'])) {
						
						$vibcon_ticket = array(
							'ticket_id' => '',
							'user_id' => '',
							'delcard_id' => '',
							'amount' => '',
							'workshop_a' => '',
							'workshop_b' => '',
							'case_presentation' => '',
							'paper_presentation' => '',
							'quiz' => '',
							'symposium' => '',
							'delcard' => '',
							'payment_id' => '',
							'payment_email' => '',
							'payment_contact' => '',
							'booking_date' => ''
						);

						$cost = 400;

						if (!empty($_POST['workshop_a'])) {
							$cost += 300;
							$vibcon_ticket['workshop_a'] = $_POST['workshop_a'];
						}

						if (!empty($_POST['workshop_b'])) {
							$cost += 300;
							$vibcon_ticket['workshop_b'] = $_POST['workshop_b'];
						}

						if (!empty($_POST['workshop_a']) && !empty($_POST['workshop_b'])) {
							$cost -= 100;
						}

						if ($_POST['case_presentation'] === 'Case Presentation') {
							$cost += 100;
							$vibcon_ticket['case_presentation'] = 'case_presentation';
						}

						if ($_POST['paper_presentation'] === 'Paper Presentation') {
							$cost += 100;
							$vibcon_ticket['paper_presentation'] = 'paper_presentation';
						}

						if ($_POST['quiz'] === 'Quiz') {
							$cost += 300;
							$vibcon_ticket['quiz'] = 'quiz';
						}

						if ($_POST['symposium'] === 'symposium') {
							$cost += 800;
							$vibcon_ticket['symposium'] = 'symposium';
						}

						if ($_POST['delcard'] === 'delcard') {
							$cost += 400;
							$vibcon_ticket['delcard'] = 'delcard';
						}

						$vibcon_ticket['amount'] = $cost;
						
						$user_id = $user['user_id'];
						$check_query = "SELECT * FROM vibcon_tickets WHERE user_id='$user_id'";
						$result = mysqli_query($db, $check_query);
						if (mysqli_num_rows($result) > 0) {
							array_push($errors, 'Already booked');
						}

						$check_query = "SELECT * FROM delcards WHERE user_id='$user_id'";
						$result = mysqli_query($db, $check_query);
						if (mysqli_num_rows($result) > 0) {
							array_push($errors, 'Delcard Already booked');
						}

						if (count($errors) == 0) {
							require('config.php');
								$price = $cost;
								$api = new Api($keyId, $keySecret);
								$orderData = [
									'receipt'         => 3456,
									'amount'          => $price*100, // 2000 rupees in paise
									'currency'        => 'INR',
									'payment_capture' => 1 // auto capture
								];

								$razorpayOrder = $api->order->create($orderData);
								$razorpayOrderId = $razorpayOrder['id'];
								$_SESSION['razorpay_order_id'] = $razorpayOrderId;
								$displayAmount = $amount = $orderData['amount'];

								if ($displayCurrency !== 'INR')
								{
									$url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
									$exchange = json_decode(file_get_contents($url), true);
									$displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
								}

								$checkout = 'vibcon_auto';

								$data = [
									"key"               => $keyId,
									"amount"            => $amount,
									"name"              => 'vibcon',
									"description"       => "",
									"image"             => "",
									"prefill"           => [
										"name"              => "",
										"email"             => "",
										"contact"           => "",
									],
									"theme"             => [
										"color"             => "#F37254"
									],
									"order_id"          => $razorpayOrderId,
								];

								if ($displayCurrency !== 'INR')
								{
									$data['display_currency']  = $displayCurrency;
									$data['display_amount']    = $displayAmount;
								}

								$json = json_encode($data);
								$_SESSION['vibcon_ticket'] = $vibcon_ticket;
								$_SESSION['user_id'] = $user_id;

								echo '<h3>Total: ' . $price . '₹</h3>';
								require("checkout/{$checkout}.php");
						}
					}
				?>
    		</div>
    	</div>
    </body>
</html>