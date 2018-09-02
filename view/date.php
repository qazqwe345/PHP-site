<?php

	echo date("Y-m-d H:i:s")."</br>";
	echo date("Y-m-d H:i:s", strtotime("+1 day"))."</br>";
	echo date("Y-m-d H:i:s", strtotime("-7 day"))."</br>";
	
	date_default_timezone_set('Asia/Taipei');
	echo date("Y-m-d H:i:s")."</br>";
	echo date("Y-m-d H:i:s", strtotime("+1 day"))."</br>";
	echo date("Y-m-d H:i:s", strtotime("-7 day"))."</br>";
	

?>
