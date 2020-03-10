<?php include('server.php') ?>
<html>
	<head>
		<title>Registration</title>
	</head>
	<body>
		<form method="POST" action="register.php">
			<?php include('errors.php') ?>
			Name:<input type="text" name="name" value="<?php echo $name; ?>"><br>
			Email:<input type="email" name="email" value="<?php echo $email; ?>"><br>
			<input type="submit" value="submit" name="submit">
			<p>Already a member? <a href="login.php">sign in</a></p>
		</form>
	</body>
</html>