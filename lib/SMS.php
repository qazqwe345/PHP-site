<?php

class SMS{
	private $content;
	private $phoneNumber;
	private $username;
	private $password;
	private $responseUrl = '';	
	
	function __construct($username, $password){
		$this->username = $username;
		$this->password = $password;
		
	}

	function setContent($content){
		$this->content = $content;
	}

	function setPhoneNumber($phoneNumber){
		$this->phoneNumber = $phoneNumber;
	}

	function setResponseUrl($responseUrl){
		$this->responseUrl = $responseUrl;
	}

	function send(){
		if ($this->content=='' || !preg_match('/^09([0-9]{8})$/', $this->phoneNumber) || $this->username=='' || $this->password==''){
			return 'ERROR Params.';
		}

		$data = array(
			'username'	=> $this->username,
			'password'	=> $this->password,
			'dstaddr'	=> $this->phoneNumber,
			'smbody'	=> iconv("UTF-8","big5//TRANSLIT",$this->content));
		print_r($data);

		$querystring = http_build_query($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.kotsms.com.tw/kotsmsapi-1.php?".$querystring);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);

		$this->phoneNumber = '';
		$this->content = '';

		echo $result;
		echo '</br>'.$querystring;
	}
}

?>
