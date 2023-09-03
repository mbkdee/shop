<?php
	require_once dirname(__FILE__) . '/../../../autoload.php';
	
	if ( $constants->last_message == 'getType' ) 
	{
		if ( $data->text == $keyboard->buttons['go_back'] ) 
		{
			$database->update('users', ['last_query' => 'getCount'], ['id' => $data->user_id]);
					
			$telegram->sendMessage([
			'chat_id' => $data->chat_id,
			'parse_mode' => 'HTML',
			'text' => "✅ تعداد مورد نیاز خود را به صورت عدد بزرگتر از صفر وارد نمایید.",
			'reply_markup' => $keyboard->go_back()
			]);
		}
		else 
		{
			$cartID = $database->select('users', ['last_request'], ['id' => $data->user_id]);
			
			if ($data->text == $keyboard->buttons['GroupType'] or $data->text == $keyboard->buttons['channelType'])
			{
				if($data->text == $keyboard->buttons['GroupType'])
				{
					$type=1;
					$text="گروه";
				}
				else
				{
					$type=2;
					$text="کانال";
				}
					
				$database->update("cart", ['type' => $type], ['id' => $cartID[0]['last_request']]);
				$database->update("users", ['last_query' => 'getLink'], ['id' => $data->user_id]);
				
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "✅ لینک جوین ".$text." مورد نظر خود را ارسال نمایید.",
				'reply_markup' => $keyboard->go_back()
				]);
			} 
			else 
			{
				$database->update("users", ['last_query' => 'getType'], ['id' => $data->user_id]);
				
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "⚠️ می بایست یکی از دکمه های زیر را انتخاب نمایید."."\n"."✅ این محصول را برای کانال می خواهید یا گروه؟",
				'reply_markup' => $keyboard->key_getType()
				]);
			}
		}
	}								