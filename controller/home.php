<?php
	if (UserVelidator::isLogin(isset($_SESSION['username'])?$_SESSION['username']:'')){
		include 'view/header.php';
		include 'view/home.php';
		include 'view/footer.php';
	}else{
		header('Location: logout');
	}

?>
