<?php

	unset($_SESSION['id']);
	unset($_SESSION['username']);

	$msg->success('Logout Successful.');
	header('Location: login');


?>
