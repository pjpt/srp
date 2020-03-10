<?php
	session_start();
?>

<html>
	<head>
		<title>Home</title>
	</head>
	<body>
		<h1><?php echo $_SESSION['name'] ?></h1>
		<h3><?php echo $_SESSION['email'] ?></h3>
	</body>
</html>