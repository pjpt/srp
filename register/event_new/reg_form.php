<?php
    include('server.php');
    
    if (!isset($_SESSION['temp_email'])) {
		header('location: index.php');
	}

    if (is_user_exist_DB($_SESSION['temp_email'])) {
        $_SESSION['user'] = get_user($_SESSION['temp_email']);
        header('location: ticket.php');
    }

    $firstname = $_SESSION['temp_firstname'];
    $lastname = $_SESSION['temp_lastname'];
    $email = $_SESSION['temp_email'];

?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Buy Ticket</title>
        <link href="index.css" rel="stylesheet">
    </head>
    <body>
        <h1>Buy Ticket</h1>
        <div class="container">
            <div class="links">
                <?php include('errors.php') ?>
                <form action="" method="POST">
                    <div class="form-name">
                        <div class="form-firstname">
                            <label for="name">First Name</label>
                            <input type="text" name="firstname" id="name" value="<?php echo $firstname; ?>" required>
                        </div>
                        <div class="form-lastname">
                            <label for="name">Last Name</label>
                            <input type="text" name="lastname" id="name" value="<?php echo $lastname; ?>" required>
                        </div>
                    </div>
                    <p></p>
                    <div class="form-select">
                        <label for="college_selection">College</label>
                        <p></p>
						<select name="colleges" id="college_selection">
							<option value="">Select College</option>
							<option value="bmc">Barorda Medical College</option>
							<option value="oth">Others</option>
					    </select>
					</div>
                    <div class="form-email">
                        <input type="text" name="college" id="clg">
                    </div>
                    <div class="form-email">
                        <label for="mobile_number">Mobile Number</label>
                        <input type="tel" name="mob" id="mobile_number" max-length="10" required>
                    </div>
                    <div class="form-submit">
                        <input type="submit" value="SIGN UP" name="signup">
                    </div>
                    <div class="form-submit">
                        <a href="index.php" style="width: 90%;">Cancle</a>
                    </div>
                </form>
            </div>
        </div>
    </body>
    <script src="reg_form.js"></script>
</html>