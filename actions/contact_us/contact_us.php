<?php
	require_once dirname(__FILE__) . '/../../autoload.php';
	
	if ( $constants->last_message === null ) 
	{
		$database->update("users", [ 'last_query' => 'contact_us' ], [ 'id' => $data->user_id ]);
		$content = [
        'chat_id' => $data->chat_id,
        'text' => file_get_contents("config/text/contact_us.txt"),
        'reply_markup' => $keyboard->go_back()
		];
		$telegram->sendMessage($content);
	}
	elseif ( $constants->last_message == 'contact_us' ) 
	{
		$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
		
		if ( $data->text == $keyboard->buttons['go_back'] ) 
		{
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'text' => "گزینه مورد نظر را انتخاب نمایید:",
			'reply_markup' => $keyboard->key_start()
			]);
		} 
		else 
		{
			$telegram->forwardMessage([
			'chat_id' => $auth->admin_list[0],
			'from_chat_id' => $data->user_id,
			'message_id' => $data->message_id,
			'text' => $data->text,
			'reply_markup' => $keyboard->key_start_admin()
			]);
			
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'text' => "ممنون از شما. پیام شما باموفقیت برای تیم پشتیبانی ارسال شد.",
			'reply_markup' => $keyboard->key_start()
			]);
		}
	}
