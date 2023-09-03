<?php
	require_once dirname(__FILE__) . '/../../../autoload.php';
	
	function is_valid_url($url)
	{
		preg_match("'^https://t.me/[A-Za-z-_0-9]+'si",$url,$m1);
		preg_match("'^http://t.me/[A-Za-z-_0-9]+'si",$url,$m2);
		return (count($m1)>0 || count($m2) > 0);
	}
	
	function is_valid_url_international($uri)
	{
		if(preg_match( '/^(http|https):\\/\\/[a-z0-9]+([\\-\\.]{1}[a-z0-9]+)*\\.[a-zآ-ی]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i' ,$uri))
		{
			return $uri;
		}
		else
		{
			return false;
		}
	}
	
	if ( $constants->last_message == 'getLink' ) 
	{
		if ( $data->text == $keyboard->buttons['go_back'] ) 
		{
			$database->update("users", ['last_query' => 'getType'], ['id' => $data->user_id]);
			
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'text' => "⚠️ می بایست یکی از دکمه های زیر را انتخاب نمایید."."\n"."✅ این محصول را برای کانال می خواهید یا گروه؟",
			'reply_markup' => $keyboard->key_getType()
			]);
		}
		else 
		{
			$cartID = $database->select('users', ['last_request'], ['id' => $data->user_id]);
			
			if (is_valid_url($data->text))
			{
				$database->update("cart", ['link' => $data->text,'status' => 1], ['id' => $cartID[0]['last_request']]);
				$database->update("users", ['last_query' => null,'last_request' => null], ['id' => $data->user_id]);
				
				$json = $telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "✅ محصول مورد نظر باموفقیت به سبد خرید شما افزوده شد.",
				'reply_markup' => $keyboard->key_start()
				]);
				
				$telegram->deleteMessage([
				'chat_id' => $data->user_id,
				'message_id' => $json['result']['message_id'],
				]);
				
				$telegram->sendMessage([
				'chat_id' => $data->chat_id,
				'text' => "✅ محصول مورد نظر باموفقیت به سبد خرید شما افزوده شد."."\n\n"."🔸 گزینه مورد نظر خود را انتخاب نمایید:",
				'reply_markup' => $keyboard->key_continue()
				]);
			} 
			else 
			{
				$database->update("users", ['last_query' => 'getLink'], ['id' => $data->user_id]);
				
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "⚠️ لطفا یک لینک درست ارسال کنید."."\n"."لینک باید به صورت زیر باشد:"."\n"."https://t.me/test"."\n"."✅ این محصول را برای کانال می خواهید یا گروه؟",
				'reply_markup' => $keyboard->go_back()
				]);
			}
		}
	}									