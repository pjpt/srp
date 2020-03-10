<?php
	session_start();

	$groups = array(
		'programmer' => '5star',
		'coder' => '5start'
	);

	$user = array(
		'first_name' => 'kartik',
		'last_name' => 'prajapati',
		'email' => 'ktk.prajapati@gmail.com',
		'group' => $groups 
	);

	$_SESSION['user'] = $user;

	echo 'Name: ' . $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name'] . '<br>';
	echo 'Email: ' . $_SESSION['user']['email'] . '<br>';
	echo 'Groups: ' . $_SESSION['user']['group']['programmer'] . ', ' . $_SESSION['user']['group']['coder'] . '<br>';
?>
