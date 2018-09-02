<?php
	require 'vendor/autoload.php';
	echo "Config::SMS_USERNAME".Config::SMS_USERNAME;
	$SMS = new SMS(Config::SMS_USERNAME, Config::SMS_PASSWORD);
	$SMS->setContent('hello, this is my test message');
	$SMS->setPhoneNumber('0933471701');
	$SMS->send();
?>
