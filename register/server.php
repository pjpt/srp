<?php

session_start();

$name = "";
$email = "";
$errors = array();

$db = mysqli_connect('localhost', 'k', '', 'event_web');

if (isset($_POST['submit'])) {
	echo "hi";
	$name = mysqli_real_escape_string($db, $_POST['name']);
	$email = mysqli_real_escape_string($db, $_POST['email']);

	if (empty(($name))) { array_push($errors, "Name is required"); };
	if (empty(($email))) { array_push($errors, "Email is required"); };

	$email_check_query = "SELECT * FROM users WHERE email=$email LIMIT 1";
	$result = mysqli_query($db, $email_check_query);
	$user = mysqli_fetch_assoc($result);

	if ($user) {
		if ($user['email'] === $email) {
			array_push($errors, "Email already exists");
		}
	}

	if (count($errors) == 0) {
		$query = "INSERT INTO users (name, email) VALUES ('$name', '$email')";
		mysqli_query($db, $query);
		$_SESSION['name'] = $name;
		$_SESSION['email'] = $email;
		$_SESSION['success'] = "You are logged in";
		header('location: index.php');
	}
}

?>