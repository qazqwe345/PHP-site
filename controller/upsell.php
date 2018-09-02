<?php
	if (!UserVelidator::isLogin(isset($_SESSION['username'])?$_SESSION['username']:'')){
		header('Location: login');
		exit;
	}
	
	$cart = new Cart($_SESSION['cartQty'], $_SESSION['cartPrice'], $_SESSION['cartName']);
	$cart->removeProductFromCart(2);
	$cart->addToCartIfNotExist(1, "Free images", 0, 1);

	$_SESSION['cartQty'] = $cart->getCartQty();
	$_SESSION['cartPrice'] = $cart->getCartPrice();
	$_SESSION['cartName'] = $cart->getCartName();

	include('view/header.php');
	include('view/upsell.php');
	include('view/footer.php');
?>
