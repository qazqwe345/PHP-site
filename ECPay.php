<?php

	try{
		$obj = new ECPay_AllInOne();
		$obj->ServiceURL = Config::ECPAY_API_URL;
		$obj->HashKey	 = Config::ECPAY_HASH_KEY;
		$obj->HashIV	 = Config::EcPAY_HASH_IV;
		$obj->MerchantID = Config::ECPAY_MERCHANT_ID;
		$obj->Send['RetrunURL'] = Config::ECPAY_CALLBACK_URL;

		$obj->Send['MerchantTradeNo']   = $order_id;
		$obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');
		$obj->Send['TotalAmount']	= (int)$order_total;
	        $obj->Send['TradeDesc']		= 'TradeNumber'.$order_id;

		$obj->Send['NeedExtraPaidInfo'] = 'Y';
		if ($order_payment_option == 'credit_card'){
			$obj->Send['OrderResultURL'] = $returnUrl;
			$obj->Send['ChoosePayment']  = ECPay_PaymentMethod::Credit;
		}else if ($order_payment_option == 'atm'){
			$ClientRedirectURL = Config::BASE_URL;
			$obj->SendExtend['ExpireDate'] = 7;
			$obj->SendExtend['ClientRedirectURL'] = $ClientRedirectURL;
			$obj->Send['ChoosePayment'] = ECPay_PaymentMethod::ATM;
		}else{
			return null;
		}

		$obj->Send['EncryptType'] = 1;

		array_push($obj->Send['Items'], array(
			'Name'	=> 'Goods name',
			'Price' => (int)1000,
			'Currency' => 'dollars',
			'Quantity' => (int) '1',
			'URL'	=> ''
		));

		$obj->CheckOut();
	}catch(Exception $e){
		echo $e->getMessage();
	}

?>
