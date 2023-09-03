<?php
	require_once dirname(__FILE__) . '/../../autoload.php';
	
	if ($data->callback_query)
	{
		if($data->text=="cart-refresh" or $data->text=="endBuy")
		{
			$countCart  = $database->count("cart",["AND" => ["user_id" => $data->user_id,"status" => 1]]);
			
			if($countCart>0)
			{
				$text='';
				$cost=0;
				$cartInfo = $database->select('cart', '*', ["AND" => ["user_id" => $data->user_id,"status" => 1],"ORDER" => ["id" => "ASC"]]);
				
				for($i=0;$i<$countCart;$i++)
				{
					if($i%2) {$icon="🔸";} else {$icon="🔹";}
					$productInfo = $database->select('product', ['name','price','count'], ["id" => $cartInfo[$i]['product']]);
					
					
					$text.= $icon.' ' . "نام محصول: " . $productInfo[0]['name'] ."\n".
					"تعداد: " . $cartInfo[$i]['count'] ."\n".
					"قیمت: " . $productInfo[0]['price'] ." تومان"."\n".
					"قیمت نهایی: " . ($productInfo[0]['price']*$cartInfo[$i]['count']) ." تومان"."\n".
					"لینک ". ($cartInfo[$i]['type']==1 ? "گروه":"کانال") ." :\n " . $cartInfo[$i]['link'] ."\n".
					"ویرایش تعداد: /editItem_" . $cartInfo[$i]['id'] ."\n".
					"حذف: /deleteItem_" . $cartInfo[$i]['id'] ."\n\n";
					$cost+=$productInfo[0]['price']*$cartInfo[$i]['count'];
				}
				
				$userInfo = $database->select('users', ['cash'], ["id" => $data->user_id]);
				
				
				$telegram->editMessageText([
				'chat_id' => $data->chat_id,
				'message_id' => $data->message_id,
				'parse_mode' => 'HTML',
				'disable_web_page_preview' => 'true',
				'text' =>  "مشتری گرامی سبد خرید شما به شرح ذیل است :"."\n\n"
				.$text."\n".
				"➕ مجموع: ".$cost." تومان",
				'reply_markup' => $keyboard->key_cart_buy()
				]);
			}
			else
			{
				$telegram->editMessageText([
				'chat_id' => $data->chat_id,
				'message_id' => $data->message_id,
				'parse_mode' => 'Markdown',
				'text' => "⚠️ سبد خرید شما خالی است.",
				'reply_markup' => $keyboard->key_cart()
				]);
			}
			
			$telegram->answerCallbackQuery([
			'callback_query_id' => $data->callback_query_id,
			'show_alert' => false,
			'text'=>"🛒 سبد خرید شما به روز رسانی شد."
			]);
		}
		else if($data->text=="cart-buy")
		{
			$telegram->answerCallbackQuery([
			'callback_query_id' => $data->callback_query_id,
			'show_alert' => false,
			'text'=>""
			]);
			$status = $database->select('users', ['reg_status','name','mobile'], ['id' => $data->user_id]);
			if ( $status[0]['reg_status'] == "0" )
			{
				$database->update('users', ['last_request' => 'cartReg'], ['id' => $data->user_id]);	
				require_once 'actions/profile/sub-menu/register.php';
			}
			else
			{
				$telegram->editMessageText([
				'chat_id' => $data->chat_id,
				'message_id' => $data->message_id,
				'parse_mode' => 'HTML',
				'text' =>  "✍️ مشتری گرامی آخرین اطلاعات شما در سیستم بدین صورت است:"."\n\n".
				"👤 نام و نام خانوادگی: ".$status[0]['name']."\n"."📞 شماره موبایل: ".$status[0]['mobile']."\n\n"."⚠️ آیا این اطلاعات برای ارسال سفارش صحیح است؟",
				'reply_markup' => $keyboard->key_cart_continue()
				]);
				
			}
		}
		else if($data->text=="cart-no")
		{
			$telegram->answerCallbackQuery([
			'callback_query_id' => $data->callback_query_id,
			'show_alert' => false,
			'text'=>""
			]);
			$database->update('users', ['last_request' => 'cartReg'], ['id' => $data->user_id]);
			require_once 'actions/profile/sub-menu/register.php';
		}
		else if($data->text=="cart-yes")
		{
			$telegram->answerCallbackQuery([
			'callback_query_id' => $data->callback_query_id,
			'show_alert' => false,
			'text'=>""
			]);
			
			$database->update('users', ['last_query' => null], ['id' => $data->user_id]);	
			
			$telegram->deleteMessage([
			'chat_id' => $data->user_id,
			'message_id' => $data->message_id,
			]);
			//////////////////////////////////
			$text='';
			$text_for_send_admin='';
			$cost=0;
			$countCart  = $database->count("cart",["AND" => ["user_id" => $data->user_id,"status" => 1]]);
			$cartInfo = $database->select('cart', '*', ["AND" => ["user_id" => $data->user_id,"status" => 1],"ORDER" => ["id" => "ASC"]]);
			
			for($i=0;$i<$countCart;$i++)
			{
				$productInfo = $database->select('product', ['name','price','count'], ["id" => $cartInfo[$i]['product']]);
				
				$text.= "نام محصول: " . $productInfo[0]['name'] ."\n".
				"تعداد: " . $cartInfo[$i]['count'] ."\n".
				"قیمت: " . $productInfo[0]['price'] ." تومان"."\n".
				"لینک ". ($cartInfo[$i]['type']==1 ? "گروه":"کانال") ." :\n " . $cartInfo[$i]['link'] ."\n".
				"قیمت نهایی: " . ($productInfo[0]['price']*$cartInfo[$i]['count']) ." تومان"."\n\n";
				
				$text_for_send_admin.= "نام محصول: " . $productInfo[0]['name'] ."\n".
				"تعداد: " . $cartInfo[$i]['count'] ."\n".
				"لینک ". ($cartInfo[$i]['type']==1 ? "گروه":"کانال") ." :\n " . $cartInfo[$i]['link'] ."\n\n";
				
				$cost+=$productInfo[0]['price']*$cartInfo[$i]['count'];
			}
			
			$userInfo = $database->select('users', ['name','mobile'], ['id' => $data->user_id]);
			
			$cart_list=$text."\n"."مجموع: ".$cost." تومان";
			
			if(!$database->has("orders", ["AND" => ["user_id" => $data->user_id,"cart_list" => $cart_list,"status" => 0]]))
			{
				$order_id = $database->insert("orders", [
				"user_id" => $data->user_id,
				"cart_list" => $cart_list,
				"cart_list_for_send_admin" => $text_for_send_admin,
				"name" => $userInfo[0]['name'],
				"mobile" => $userInfo[0]['mobile'],
				'amount' => $cost,
				'date' => jdate("Y/n/d"),
				"status" => 0
				]);
				
				$database->update("users", [ 'order_id' => $order_id ], [ 'id' => $data->user_id ]);
			}
			else
			{
				$database->update("orders", [
				"cart_list" => $cart_list,
				"cart_list_for_send_admin" => $text_for_send_admin,
				"name" => $userInfo[0]['name'],
				"mobile" => $userInfo[0]['mobile'],
				'amount' => $cost
				], [ 'user_id' => $data->user_id ]);
			}
			
			$link=$auth->path."pay/?id=".$data->user_id;
			
			$telegram->sendMessage([
			'chat_id' => $data->chat_id,
			'parse_mode' => 'Markdown',
			'text' => "🔻 با استفاده از لینک زیر می توانید عملیات پرداخت را انجام دهید:"."\n".$link."\n\n"."⚠️ بدیهی است پس از انجام عملیات پرداخت وضعیت پرداخت از طریق ربات به شما اطلاع داده خواهد شد.",
			'reply_markup' => $keyboard->key_start()
			]);
		}
	}
	else
	{
		$countCart  = $database->count("cart",["AND" => ["user_id" => $data->user_id,"status" => 1]]);
		
		if($countCart>0)
		{
			$text='';
			$cost=0;
			$cartInfo = $database->select('cart', '*', ["AND" => ["user_id" => $data->user_id,"status" => 1],"ORDER" => ["id" => "ASC"]]);
			
			for($i=0;$i<$countCart;$i++)
			{
				if($i%2) {$icon="🔸";} else {$icon="🔹";}
				$productInfo = $database->select('product', ['name','price','count'], ["id" => $cartInfo[$i]['product']]);
				
				$text.= $icon.' ' . "نام محصول: " . $productInfo[0]['name'] ."\n".
				"تعداد: " . $cartInfo[$i]['count'] ."\n".
				"قیمت: " . $productInfo[0]['price'] ." تومان"."\n".
				"قیمت نهایی: " . ($productInfo[0]['price']*$cartInfo[$i]['count']) ." تومان"."\n".
				"لینک ". ($cartInfo[$i]['type']==1 ? "گروه":"کانال") ." :\n " . $cartInfo[$i]['link'] ."\n".
				"ویرایش تعداد: /editItem_" . $cartInfo[$i]['id'] ."\n".
				"حذف: /deleteItem_" . $cartInfo[$i]['id'] ."\n\n";
				$cost+=$productInfo[0]['price']*$cartInfo[$i]['count'];
			}
			
			$userInfo = $database->select('users', ['cash'], ["id" => $data->user_id]);
			
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'parse_mode' => 'HTML',
			'disable_web_page_preview' => 'true',
			'text' =>  "مشتری گرامی سبد خرید شما به شرح ذیل است :"."\n\n".
			$text."\n".
			"➕ مجموع: ".$cost." تومان",
			'reply_markup' => $keyboard->key_cart_buy()
			]);
		}
		else
		{
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'parse_mode' => 'Markdown',
			'text' => "⚠️ سبد خرید شما خالی است.",
			'reply_markup' => $keyboard->key_cart()
			]);
		}
	}
