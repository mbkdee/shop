<?php

	session_start();
	ini_set("log_errors", 0);
	require_once dirname(__FILE__).'/../autoload.php';
	
	$userInfo = $database->select('users', ['order_id'], ['id' => $_SESSION['id']]);
	
    if ($_GET['Status'] == 'OK') 
	{
        $client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);
		
		$ordersInfo = $database->select('orders', ['amount'], ['id' => $userInfo[0]['order_id']]);
		
        $result = $client->PaymentVerification([
		'MerchantID'     => $auth->MerchantID,
		'Authority'      => $_GET['Authority'],
		'Amount'         => $ordersInfo[0]['amount']
        ]);
		
        if ($result->Status == 100) 
		{	
			$database->update("orders", ['codePeygiri' => $result->RefID,'paymentTime' => jdate("H:i:s | l, Y/n/d"), 'status' => 1 ], [ 'id' => $userInfo[0]['order_id']]);
				
			$database->delete("cart", ["user_id" => $_SESSION['id']]);
			
			$telegram->sendMessage([
			'chat_id' => $_SESSION['id'],
			'parse_mode' => 'MarkDown',
			'disable_web_page_preview' => 'true',
			'text' => "🙏 باتشکر از اعتماد شما دوست عزیز "."\n\n"."🔸 سفارش شما با موفقیت پرداخت شد و در اسرع وقت انجام میگیرد."."\n\n"."📅 زمان پرداخت:"."\n`".jdate("H:i:s | l, Y/n/d")."`\n\n"."🏧 شماره تراکنش: `".$result->RefID."`"
			]);
			//////////////////
			$userData = $database->select('users', ['name','mobile'], ['id' => $_SESSION['id']]);
			$cart_list = $database->select('orders', ['cart_list','cart_list_for_send_admin'], ['id' => $userInfo[0]['order_id']]);
			$telegram->sendMessage([
			'chat_id' => $auth->admin_list['0'],
			'parse_mode' => 'MarkDown',
			'disable_web_page_preview' => 'true',
			'text' => 
			"🔸 یک سفارش جدید ثبت شد!"."\n\n".
			"🆔 شناسه کاربری: `".$_SESSION['id']."`\n".
			"👤 نام و نام خانوادگی: `".$userData[0]['name']."`\n".
			"🔢 شماره تماس: `".$userData[0]['mobile']."`\n".
			"📅 زمان ثبت سفارش:"."\n`".jdate("H:i:s | l, Y/n/d")."`\n\n".
			"🏧 کد پیگیری: `".$result->RefID."`"."\n\n".
			"🛒 لیست سفارش: "."\n".$cart_list[0]['cart_list']
			]);
			
			$telegram->sendMessage([
			'chat_id' => $auth->adminSend,
			'parse_mode' => 'MarkDown',
			'disable_web_page_preview' => 'true',
			'text' => 
			"🔸 یک سفارش جدید ثبت شد!"."\n\n".
			"🆔 شناسه کاربری: `".$_SESSION['id']."`\n".
			"📅 زمان ثبت سفارش:"."\n`".jdate("H:i:s | l, Y/n/d")."`\n\n".
			"🛒 لیست سفارش: "."\n".$cart_list[0]['cart_list_for_send_admin'],
			'reply_markup' => $keyboard->key_confirm($_SESSION['id'],$userInfo[0]['order_id'])
			]);
			//////////////////
			
			$GetINFObot = json_decode(file_get_contents("https://api.telegram.org/bot".$auth->bot_id."/getMe"));
			$UserNameBot = $GetINFObot->result->username;
			
			echo '<div style="width: 100%;direction: rtl;text-align:center;background-color: #dff0d8;border-color: #d6e9c6;color: #3c763d;padding: 15px;border: 1px solid transparent;border-radius: 4px;">🙏 باتشکر از اعتماد شما دوست عزیز<br/>لطفا برای ادامه کار به ربات مراجعه نمایید و یا <a href="https://t.me/'.$UserNameBot.'">اینجا</a> کلیک نمایید.</div>';
			
			$database->update("users", ['order_id' => 0], [ 'id' => $_SESSION['id']]);
			unset($_SESSION['id']);
		} 
		else 
		{
			if($result->Status==-1)
			{
				$Status="اطلاعات ناقص وارد شده است.";
			}
			elseif($result->Status==-2)
			{
				$Status="آی پی و یا مرچنت کد پذیرنده صحیح نیست.";
			}
			elseif($result->Status==-3)
			{
				$Status="رقم باید بالای 100 تومان باشد.";
			}
			elseif($result->Status==-4)
			{
				$Status="سطح تایید پذیرنده پایین تر از سطح نقره ای است.";
			}
			elseif($result->Status==-11)
			{
				$Status="درخواست مورد نظر یافت نشد";
			}
			elseif($result->Status==-21)
			{
				$Status="هیچ گونه عملیات مالی برای تراکنش یافت نشد.";
			}
			elseif($result->Status==-22)
			{
				$Status="تراکنش ناموفق می باشد.";
			}
			elseif($result->Status==-33)
			{
				$Status="رقم تراکنش با رقم پرداخت شده مطابقت ندارد.";
			}
			elseif($result->Status==-54)
			{
				$Status="درخواست مورد نظر آرشیو شده است.";
			}
			elseif($result->Status==100)
			{
				$Status="عملیات باموفقیت انجام شد.";
			}
			elseif($result->Status==101)
			{
				$Status="عملیات باموفقیت انجام شده ولی قبلا عملیات اعتبارسنجی بر روی این تراکنش صورت گرفته است.";
			}
			else
			{
				$Status="خطایی ناشناخته رخ داده است";
			}
			
			$telegram->sendMessage([
			'chat_id' => $_SESSION['id'],
			'parse_mode' => 'MarkDown',
			'disable_web_page_preview' => 'true',
			'text' => $Status
			]);
			
			echo '<div style="width: 100%;direction: rtl;text-align:center;background-color: #fcf8e3;border-color: #faebcc;color: #8a6d3b;padding: 15px;border: 1px solid transparent;border-radius: 4px;">'.$Status.'</div>';
			unset($_SESSION['id']);
		}
	} 
	else 
	{
		$telegram->sendMessage([
		'chat_id' => $_SESSION['id'],
		'parse_mode' => 'MarkDown',
		'disable_web_page_preview' => 'true',
		'text' => "پرداخت توسط کاربر لغو گردیده است."
		]);
		
		echo '<div style="width: 100%;direction: rtl;text-align:center;background-color: #f2dede;border-color: #ebccd1;color: #a94442;padding: 15px;border: 1px solid transparent;border-radius: 4px;">پرداخت توسط کاربر لغو گردیده است</div>';
		unset($_SESSION['id']);
	}
