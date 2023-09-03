<?php
	session_start();
	ini_set("log_errors", 0);
	require_once dirname(__FILE__).'/../autoload.php';
	
	if(isset($_GET['id']) and is_numeric($_GET['id']) and $database->has("orders", ["AND" => ["user_id" => $_GET['id'],"status" => 0]]))
	{
		$_SESSION['id']=$_GET['id'];
		$userInfo = $database->select('users', ['order_id'], ['id' => $_GET['id']]);
		
		if($userInfo[0]['order_id']!=0)
		{
			$ordersInfo = $database->select('orders', ['amount'], ['id' => $userInfo[0]['order_id']]);
			
			$client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);
			
			$result = $client->PaymentRequest([
			'MerchantID'     => $auth->MerchantID,
			'Amount'         => $ordersInfo[0]['amount'],
			'Description'    => $auth->Description,
			'Email'          => '',
			'Mobile'         => $_SESSION['id'],
			'CallbackURL'    => $auth->path.'pay/verify.php',
			]);
			
			if ($result->Status == 100) 
			{
				header('Location: https://www.zarinpal.com/pg/StartPay/'.$result->Authority);
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
				'chat_id' => $_GET['id'],
				'parse_mode' => 'MarkDown',
				'disable_web_page_preview' => 'true',
				'text' => $Status
				]);
			}
		}
		else
		{
			echo '<div wit style="width: 100%;direction: rtl;text-align:center;background-color: #f2dede;border-color: #ebccd1;color: #a94442;padding: 15px;border: 1px solid transparent;border-radius: 4px;">متاسفانه لینک انتخاب شده معتبر نمی باشد!</div>';
		}
	}
	else
	{
		echo '<div wit style="width: 100%;direction: rtl;text-align:center;background-color: #f2dede;border-color: #ebccd1;color: #a94442;padding: 15px;border: 1px solid transparent;border-radius: 4px;">لینک انتخاب شده صحیح نمی باشد</div>';
	}
