<?php
	require_once dirname(__FILE__) . '/../../../../autoload.php';
	
	if(in_array($data->user_id, $auth->admin_list))
	{
		if ( $constants->last_message === null ) 
		{
			$database->update('users', ['last_query' => 'nameProEdit'], ['id' => $data->user_id]);
			
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'text' => "✅ لطفا نام محصول را به صورت دقیق نوشته و ارسال کنید.",
			'reply_markup' => $keyboard->go_back()
			]);
		}
		elseif ( $constants->last_message == 'nameProEdit' ) 
		{
			if ( $data->text == $keyboard->buttons['go_back'] ) 
			{
				$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
				
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "بخش مورد نظر برای ویرایش محصول را انتخاب نمایید:",
				'reply_markup' => $keyboard->key_product_edit()
				]);
			}
			else 
			{
				$proID = $database->select('users', ['last_request'], ['id' => $data->user_id]);
				$database->update("product", ['name' => $data->text], ['id' => $proID[0]['last_request']]);
				$database->update("users", ['last_query' => null], ['id' => $data->user_id]);
				
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "✅ تغییرات باموفقیت انجام شد!"."\n"."بخش مورد نظر برای ویرایش محصول را انتخاب نمایید:",
				'reply_markup' => $keyboard->key_product_edit()
				]);
			}
		}
	}
	else
	{
		$telegram->sendMessage([
		'chat_id' => $data->user_id,
		'text' =>  "متاسفانه شما اجازه دسترسی به این بخش را ندارید.",
		"parse_mode" =>"HTML",
		'reply_markup' => $keyboard->key_start()
		]);
	}							