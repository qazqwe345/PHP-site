<?php
	if (!UserVelidator::isLogin(isset($_SESSION['id'])?$_SESSION['id']:'')){
		header('Location: login');
		exit;
	}

	$order = new Order();
	$cart = new Cart($_SESSION['cartQty'], $_SESSION['cartPrice'], $_SESSION['cartName']);

	if ($order->orderTotal == 0){
		
		$order->setOrderComplete();
		$order->save();
		$_SESSION['order'] = base64_encode(serialize($order));
		header('Location: thankyou');
		exit;
	}else{
		$_SESSION['order'] = base64_encode(serialize($order));

		try{
			$obj = new ECPay_AllInOne();
			$obj->ServiceURL = Config::ECPAY_API_URL;
			$obj->HashKey	 = Config::ECPAY_HASH_KEY;
			$obj->HashIV	 = Config::ECPAY_HASH_IV;
			$obj->MerchantID = Config::ECPAY_MERCHANT_ID;
			$obj->Send['ReturnURL'] = Config::ECPAY_CALLBACk_URL;

			$obj->Send['MerchantTradeNo']   = $order->getOrederID();
			$obj->Send['MerchantTradeDate'] = date('Y/m/s H:i:s');
			$obj->Send['TotalAmount']	= (int)$order->orderTotal;
			$obj->Send['TradeDesc']		= $cart->toStirng();
			$obj->Send['NeedExtraPaidInfo'] = 'Y';
			$obj->Send['OrderResultURL']	= Config::ECPAY_CALLBAck_URL;
			$obj->Send['ChoosePayment']	= ECPAY_PaymentMethod::Credit;
			$obj->Send['EncryptType']	= 1;

			$productIdArray = $cart->getAllProductID();
			foreach($productIdArray as $productID){
				array_push($obj->Send['Items'], array(
					'Name' => $cart->getProductNameInCart($productID),
					'Price' => (int)$cart->getProductPriceInCart($productID),
					'Currency' => 'dollars',
					'Quantity' => (int)$cart->getPrudoctQtyInCart($productID),
					'URL' => ''
				));
			}
			$obj->CheckOut();
		}catch(Exception $e){
			echo $e->getMessage();
		}
		exit;
	}
?>
