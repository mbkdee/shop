<?php
	require_once dirname(__FILE__) . '/../../../autoload.php';
	
	function convertNumbers($srting,$toPersian=false)
	{
		$en_num = array('0','1','2','3','4','5','6','7','8','9');
		$fa_num = array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
		if($toPersian)
		return str_replace($en_num, $fa_num, $srting);
        else 
		return str_replace($fa_num, $en_num, $srting);
	}
	
	if ( $constants->last_message === null ) 
	{
		$text=str_replace("/","",$data->text);
		$data_inline = explode("_", $text);	
		
		if($database->has("cart", ["AND" => ["id" => $data_inline[1],"user_id" => $data->user_id]]))
		{
			$database->update("users", [ 'last_query' => 'editCountProduct','last_request' => $data_inline[1]], [ 'id' => $data->user_id ]);
			$telegram->sendMessage([
			'chat_id' => $data->chat_id,
			'text' => "تعداد جدید را وارد کنید:",
			'reply_markup' => $keyboard->go_back()
			]);
		}
		else
		{
			$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
			$telegram->sendMessage([
			'chat_id' => $data->chat_id,
			'text' => "متاسفانه این محصول در سبد خرید شما نیست."
			]);
		}		
	}
	elseif ( $constants->last_message == 'editCountProduct' ) 
	{
		if ( $data->text == $keyboard->buttons['go_back'] ) 
		{
			$database->update("users", [ 'last_query' => null,'last_request' => null], [ 'id' => $data->user_id ]);
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'text' => "گزینه مورد نظر را انتخاب نمایید:",
			'reply_markup' => $keyboard->key_start()
			]);
		}
		else
		{
			$cartID   = $database->select('users', ['last_request'], ['id' => $data->user_id]);
			$proID    = $database->select('cart', ['product'], ['id' => $cartID[0]['last_request']]);
			$proCount = $database->select('product', ['count'], ['id' => $proID[0]['product']]);
			
			if (convertNumbers($data->text)>0 && convertNumbers($data->text)>=$proCount[0]['count'] && is_numeric(convertNumbers($data->text)))
			{				
				$database->update("cart", ['count' => convertNumbers($data->text)], ['id' => $cartID[0]['last_request']]);
				$database->update("users", [ 'last_query' => null,'last_request' => null], ['id' => $data->user_id]);
				
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "✅ تعداد جدید باموفقیت ثبت شد.",
				'reply_markup' => $keyboard->key_start()
				]);
				require_once 'actions/cart/cart.php';
			} 
			else 
			{
				$database->update("users", ['last_query' => 'editCountProduct'], ['id' => $data->user_id]);
				
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "⚠️ عدد وارد شده کمتر از حد مجاز است"."\n"."✅ تعداد مورد نیاز خود را به صورت عدد بزرگتر از صفر وارد نمایید.",
				'reply_markup' => $keyboard->go_back()
				]);
			}
		}
	}							