<?php
	$fb = new Facebook\Facebook([
		'app_id' => '469754800164961',
		'app_secret' => 'b567c9caaad4d4eceda96d6caba11fd0',
		'default_graph_version' => 'v3.1',
	]);

	$helper = $fb->getRedirectLoginHelper();

	$permissions = ['email', 'user_likes'];
	$loginUrl = $helper->getLoginUrl('https://192.168.137.219/fb-callback');

	echo '<a href="' .htmlspecialchars($loginUrl) . '">Login in with Facebook!</a>';
?>
