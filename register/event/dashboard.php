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
    		<p></p>
 			<form action="" method="POST">
				<div class="form-select">
					<select name="events" id="event">
						<option value="">Please Select</option>
						<option value="event1">Event1</option>
						<option value="event2">Event2</option>
						<option value="event3">Event3</option>
						<option value="event4">Event4</option>
					</select>
				</div>
                   <div class="form-submit">
                       	<input type="submit" value="PROCEED" name="admin_event">
                   </div>
               </form>
    		</form>
    		<?php if(isset($_POST['admin_event'])) :?>
    			<?php
    				$selected_event = $_POST['events'];
						$event_id = null;
						switch ($selected_event) {
							case 'event1':
								$event_id = 1;
								break;
							case 'event2':
								$event_id = 2;
								break;
							case 'event3':
								$event_id = 3;
								break;
							case 'event4':
								$event_id = 4;
								break;
							default:
								array_push($errors, "Please Select Event");
								break;
						}

						$query = "SELECT * FROM ticket WHERE event_id='$event_id'";
						$results = mysqli_query($db, $query);
						echo '<h2>' . $event_id . '</h2>';
						while ($ticket = mysqli_fetch_assoc($results)) {
							echo '<div class="inner-box">';
							echo '<h4>' . $ticket['ticket_id'] . '</h4>';
							echo '<h4>' . $ticket['event_id'] . '</h4>';
							echo '<h4>' . $ticket['amount'] . '</h4>';
							echo '<h4>' . $ticket['ticket_id'] . '</h4>';
			  				echo '</div>';
			  				array_push($final_array, $ticket);
			  			}
			  			
    			?>
    		<?php endif ?>
	    	<h2>Tickets</h2>
	    	<div class="inner-box">
				<h3>Code</h3>
				<h3>Event Name</h3>
	   			<h3>Ammount</h3>
	   			<h3>Print</h3>
			</div>
	    	<?php
	  			$tickets_result = mysqli_query($db, "SELECT * FROM ticket");
				$final_array = [];
	  			while ($ticket = mysqli_fetch_assoc($tickets_result)) {
					echo '<div class="inner-box">';
					echo '<h4>' . $ticket['ticket_id'] . '</h4>';
					echo '<h4>' . $ticket['event_id'] . '</h4>';
					echo '<h4>' . $ticket['amount'] . '</h4>';
					echo '<h4>' . $ticket['ticket_id'] . '</h4>';
	  				echo '</div>';
	  				array_push($final_array, $ticket);
	  			}
	  		?>
	  		<a href="dashboard.php?file='csv'">Download</a>
	    	<h2>Users</h2>
	    	<div class="inner-box">
				<h3>Firstname</h3>
				<h3>Lastname</h3>
	   			<h3>Email</h3>
			</div>
	    	<?php
	  			$users_result = mysqli_query($db, "SELECT * FROM users");
	  			while ($user = mysqli_fetch_assoc($users_result)) {
					echo '<div class="inner-box">';
					echo '<h4>' . $user['first_name'] . '</h4>';
					echo '<h4>' . $user['last_name'] . '</h4>';
					echo '<h4>' . $user['email'] . '</h4>';
	  				echo '</div>';
	  			}
	  		?>
    	</main>
    </body>
</html>