<?php
	include('server.php');

	use Razorpay\Api\Api;

	if (!isset($_SESSION['user'])) {
		header('location: login.php');
	}

	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['user']);
		header("location: login.php");
	}

	$user = $_SESSION['user'];
	echo $_SESSION['log'];

	$tickets = $user['tickets'];
	$groups = $user['groups'];
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
    			<h2>Booked ticket</h2>
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
						echo '<td>' . $tickets[$i]['ticket_id'] . '</td>';
						echo '<td>' . $tickets[$i]['event_code'] . '</td>';
						echo '<td>' . $tickets[$i]['amount'] . '</td>';
						echo '<td>' . $tickets[$i]['payment_id'] . '</td>';
  						echo '</tr>';
					}
  				?>
				</table>
    		</div>
			<div class="box">
    			<h2>Booked ticket</h2>
  				<table width="100%">
					<tr>
  					<th>Group Name</th>
	  				<th>Member1</th>
	    			<th>Member2</th>
	    			<th>Member3</th>
					<th>Event Code</th>
					</tr>
				<?php
					$groups = $user['groups'];
					for ($i = 0; $i < sizeof($groups); $i++) {
						echo '<tr>';
						echo '<td>' . $groups[$i][1] . '</td>';
						echo '<td>' . $groups[$i][3][0] . '</td>';
						echo '<td>' . $groups[$i][3][1] . '</td>';
						echo '<td>' . $groups[$i][3][2] . '</td>';
						echo '<td>' . $groups[$i][2] . '</td>';  
						echo '</tr>';
					}
  				?>
				</table>
    		</div>
    		<div class="links">
    			<h2>New Ticket</h2>
                <?php
	                if (isset($_POST['proceed'])) {
						$selected_event = $_POST['events'];
						$event = $events[(int)$selected_event-1];

						if (empty($event)) {
							array_push($errors, "Please select event");
						}
						
						if ($event['group_event'] == 1) {
							$group_name = mysqli_real_escape_string($db, $_POST['group_name']);
							$member1 = mysqli_real_escape_string($db, $_POST['member1']);
							$member2 = mysqli_real_escape_string($db, $_POST['member2']);

							if (empty($group_name)) {
								array_push($errors, "Please fill Group name");
							}
							if (empty($member1)) {
								array_push($errors, "email of member1 is required");
							}
							if (empty($member2)) {
								array_push($errors, "email of member2 is required");
							}
							if (!is_user_exist_DB($member1)) {
								array_push($errors, "member 1 not exists");
							}
							if (!is_user_exist_DB($member2)) {
								array_push($errors, "member 2 not exists");
							}
						}

						$user_id = $user['user_id'];
						$event_code = $event['event_code'];
						$check_query = "SELECT * FROM tickets WHERE user_id='$user_id' AND event_code='$event_code'";
						$result = mysqli_query($db, $check_query);
						if (mysqli_num_rows($result) > 0) {
							array_push($errors, 'Already booked');
						}

						if (count($errors) == 0) {
							add_group($group_name, $user['email'], $member1, $member2, $event_code);
							require('config.php');
							$api = new Api($keyId, $keySecret);
							$orderData = [
							    'receipt'         => 3456,
							    'amount'          => $event['registration_fee']*100, // 2000 rupees in paise
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
							$_SESSION['amount'] = $event['registration_fee'];
							$_SESSION['user_id'] = $user_id;
							echo '<h3>Event: ' . $event['event_name'] . '</h3>';
							echo '<h3>Total: ' . $event['registration_fee'] . '₹</h3>';
							require("checkout/{$checkout}.php");
						}
					}
				?>
				<?php if(!(isset($_POST['proceed']) && count($errors) == 0)) : ?>
					<form action="" method="POST">
						<?php include('errors.php'); ?>
						<div class="form-select">
							<select name="events" id="event">
								<option value="">Please Select</option>
								<option value="1">Event1</option>
								<option value="2">Event2</option>
								<option value="3">Event3</option>
							</select>
						</div>
						<div id="group_detail">
							<h3>Group</h3>
							<div class="form-email">
								<label for="gname">Group Name:</label>
								<input type="text" id="gname" name="group_name" required>
							</div>
							<div class="form-email">
								<label for="email1">Member1:</label>
								<input type="email" id="email1" name="member1" required>
							</div>
							<div class="form-email">
								<label for="email2">Member2:</label>
								<input type="email" id="email2" name="member2" required>
							</div>
						</div>
						<div class="total">
							<h3>Total: <span class="rupees" id="price"></span></h3>
						</div>
	                    <div class="form-submit">
	                        	<input type="submit" value="PROCEED" name="proceed">
	                    </div>
	                </form>
            	<?php endif ?>
    		</div>
    	</div>
    	<script type="text/javascript" src="ticket.js"></script>
    </body>
</html>