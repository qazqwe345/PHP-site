<?php

$app_id = '469754800164961';
$app_secret = 'b567c9caaad4d4eceda96d6caba11fd0';

$fb = new Facebook\Facebook([
	'app_id' => $app_id,
	'app_secret' => $app_secret,
	'default_graph_version' => '2.10',
]);

$helper = $fb->getRedirectLoginHelper();

try{
	$accessToken = $helper->getAccessToken();
}catch(Facebook\Exceptions\FacebookResponseException $e){
	echo 'Graph returned an error: ' . $e->getMessage();
	exit;
}catch(Facebook\Exceptions\FacebookSDKException $e){
	echo 'Facebook SDK returned an error: '. $e->getMessage();
	exit;
}

if (! isset($accessToken)){
	if ($helper->getError()){
		header('HTTP/1.0 401 Unauthorized');
		echo "Error: ".$helper->getError()."\n";
		echo "Error Code: ".$helper->getErrorCode()."\n";
		echo "Error Reason ".$helper->getErrorReasion()."\n";
		echo "Error Description: ".$helper->getErrorDescription()."\n";

	}else{
		header('HTTP/1.0 400 Bad Request');
		echo 'Bad request';
	}
	exit;
}

echo '<h3>Access Token</h3>';

$oAuth2Client = $fb->getOAuth2Client();

$tokenMetadata = $oAuth2Client->debugToken($accessToken);
echo '<h3>Metadata</h3>';
var_dump($tokenMetadata);

$tokenMetadata->validateAppId($app_id);
$tokenMetadata->validdateExpiration();

if (! $accessToken->isLongLived()){
	try{
		$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
	}catch(Facebook\Exceptions\FacebookSDKException $e){
		echo "<p>Error getting long-lived access token: ".$helper->getMessage()."</p>\n\n";
		exit;
	}
	echo '<h3>Long-lived</h3>';
	var_dump($accessToken->getValue());
}

$_SESSION['fb_access_token'] = (string) $accessToken;

?>
