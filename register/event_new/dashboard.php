<?php
	include('server.php');

	if (!isset($_SESSION['email'])) {
		header('location: admin.php');
	}

	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['email']);
		header("location: admin.php");
	}

	$events = get_events();
?>

<?php
    if (isset($_POST['users'])) {
        $db = mysqli_connect('localhost', 'k', '', 'event_web');
        header('Content-Type: text/csv; charset=utf8');
        header('Content-Disposition: attachment; filename=users.csv');
        $output = fopen("php://output", "w");
        $query = "SELECT * FROM users";
        $result = mysqli_query($db, $query);
        fputcsv($output, array('user_id', 'first_name', 'last_name', 'college_name', 'moblie_number', 'email'));
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, $row);
        }
        header('Content-Length: '. ob_get_length());
		fclose($output);
		exit();
    }

    if (isset($_POST['events'])) {
        $db = mysqli_connect('localhost', 'k', '', 'event_web');
        header('Content-Type: text/csv; charset=utf8');
        header('Content-Disposition: attachment; filename=events.csv');
        $output = fopen("php://output", "w");
        $query = "SELECT * FROM events";
        $result = mysqli_query($db, $query);
        fputcsv($output, array('event_code', 'event_name', 'price_bmc', 'price_oth', 'max_entries'));
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, $row);
        }
        header('Content-Length: '. ob_get_length());
        fclose($output);
		exit();
	}

    if (isset($_POST['tickets'])) {
        $db = mysqli_connect('localhost', 'k', '', 'event_web');
        header('Content-Type: text/csv; charset=utf8');
        header('Content-Disposition: attachment; filename=tickets.csv');
        $output = fopen("php://output", "w");
        $query = "SELECT * FROM tickets";
		$result = mysqli_query($db, $query);
        fputcsv($output, array('ticket_id', 'event_code', 'amount', 'user_name', 'user_email','payment_id', 'payment_email', 'payment_contact', 'booking_date'));
        while ($row = mysqli_fetch_assoc($result)) {
			$user_id = $row['user_id'];
			$q = "SELECT * FROM users WHERE user_id='$user_id'";
			$r = mysqli_query($db, $q);
			$user = mysqli_fetch_assoc($r);
			$name = $user['first_name'] . ' ' . $user['last_name'];
			$new_row = array(
				'ticket_id' => $row['ticket_id'],
				'event_code' => $row['event_code'],
				'amount' => $row['amount'],
				'user_name' => $name,
				'user_email' => $user['email'],
				'payment_id' => $row['payment_id'],
				'payment_email' => $row['payment_email'],
				'payment_contact' => $row['payment_contact'],
				'booking_date' => $row['booking_date']
			);
			fputcsv($output, $new_row);
        }
        header('Content-Length: '. ob_get_length());
        fclose($output);
		exit();
	}

	if (isset($_POST['admin_event'])) {
		$event_code = $_POST['selected_event'];

        $db = mysqli_connect('localhost', 'k', '', 'event_web');
        header('Content-Type: text/csv; charset=utf8');
		header('Content-Disposition: attachment; filename='. $event_code .'.csv');

        $output = fopen("php://output", "w");
        $query = "SELECT * FROM tickets WHERE event_code='$event_code'";
        $result = mysqli_query($db, $query);
        fputcsv($output, array('ticket_id', 'event_code', 'amount', 'user_id', 'payment_id', 'payment_email', 'payment_contact', 'booking_date'));
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, $row);
        }
        header('Content-Length: '. ob_get_length());
        fclose($output);
		exit();
	}
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Vibrant Dashboard</title>
        <link href="index.css" rel="stylesheet">
    </head>
    <body>
    	<header>
    		<h4><?php echo 'Admin'; ?></h4>
	    	<a href="dashboard.php?logout='1'" style="color:red">LOGOUT</a>
    	</header>
    	<main>
			<form action="" method="POST">
				<div class="form-submit" style="display: inline-block;">
					<input type="submit" value="users" name="users" style="width: auto;">
				</div>
				<div class="form-submit" style="display: inline-block;">
					<input type="submit" value="events" name="events" style="width: auto;">
				</div>
				<div class="form-submit" style="display: inline-block;">
					<input type="submit" value="tickets" name="tickets" style="width: auto;">
				</div>
			</form>
    		<p></p>
 			<form action="" method="POST">
				<div class="form-select">
			    <select name="selected_event" id="event">
					<option value="">Please Select</option>
		    		<?php foreach ($events as $e => $e_val) : ?>
			    		<option value="<?php echo $e; ?>"><?php echo $e_val['event_name']; ?></option>
				    <?php endforeach ?>
				</select>
				</div>
                <div class="form-submit">
                  	<input type="submit" value="PROCEED" name="admin_event">
                </div>
            </form>
    	</main>
    </body>
</html>