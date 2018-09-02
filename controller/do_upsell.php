<?php
	if (!UserVelidator::isLogin(isset($_SESSION['id'])?$_SESSION['id']:'')){
		header('Location: login');
		exit;
	}

	$cart = new Cart($_SESSION['cartQty'], $_SESSION['cartPrice'], $_SESSION['cartName']);
	$cart->addTocartIfNotExist(2, 'CheapImages', 150, 1);

	$_SESSION['cartQty'] = $cart->getCartQty();
	$_SESSION['cartPrice'] = $cart->getCartPrice();
	$_SESSION['cartName'] = $cart->getCartName();

	header('Location: do_checkout');
	exit;
?>
