<?php

	require_once dirname(__FILE__) . '/../../autoload.php';
	
	if(in_array($data->user_id, $auth->admin_list))
	{
		if ( $constants->last_message == 'startText' ) 
		{
			if ( $data->text == $keyboard->buttons['go_back'] ) 
			{
				$database->update("users", [ 'last_query' => 'setting' ], [ 'id' => $data->user_id ]);
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "گزینه مورد نظر را انتخاب نمایید:",
				'reply_markup' => $keyboard->key_setting()
				]);
			} 
			else 
			{
				$database->update("users", [ 'last_query' => 'setting' ], [ 'id' => $data->user_id ]);
				
				file_put_contents("config/text/start.txt",$data->text);
				
				$telegram->sendMessage([
				'chat_id' => $data->chat_id,
				'parse_mode' => 'Markdown',
				'text' => "✅ متن جدید باموفقیت ثبت شد",
				'reply_markup' => $keyboard->key_setting()
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
