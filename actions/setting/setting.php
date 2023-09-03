<?php
	require_once dirname(__FILE__) . '/../../autoload.php';
	
	if ( $constants->last_message === null ) 
	{
		$database->update("users", [ 'last_query' => 'setting' ], [ 'id' => $data->user_id ]);
		$telegram->sendMessage([
		'chat_id' => $data->chat_id,
		'text' => "بخش مورد نظر خود را انتخاب نمایید:",
		'reply_markup' => $keyboard->key_setting()
		]);
	}
	elseif ( $constants->last_message == 'setting' ) 
	{
		$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
		if ( $data->text == $keyboard->buttons['go_back'] ) 
		{
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'text' => "گزینه مورد نظر را انتخاب نمایید:",
			'reply_markup' => $keyboard->key_start_admin()
			]);
		} 
		else if ( $data->text == $keyboard->buttons['startText'] ) 
		{
			$database->update("users", [ 'last_query' => 'startText' ], [ 'id' => $data->user_id ]);
			$telegram->sendMessage([
			'chat_id' => $data->chat_id,
			'text' => "📝 متن مورد نظر خود را برای بخش ابتدایی ربات (شروع) ارسال کنید:"."\n\n"."📃 متن فعلی : "."\n\n".file_get_contents("config/text/start.txt"),
			'reply_markup' => $keyboard->go_back()
			]);
		} 
		else if ( $data->text == $keyboard->buttons['helpText'] ) 
		{
			$database->update("users", [ 'last_query' => 'helpText' ], [ 'id' => $data->user_id ]);
			$telegram->sendMessage([
			'chat_id' => $data->chat_id,
			'text' => "📝 متن مورد نظر خود را برای بخش راهنما ارسال کنید:"."\n\n"."📃 متن فعلی : "."\n\n".file_get_contents("config/text/help.txt"),
			'reply_markup' => $keyboard->go_back()
			]);
		}
		else if ( $data->text == $keyboard->buttons['contact_usText'] ) 
		{
			$database->update("users", [ 'last_query' => 'contact_usText' ], [ 'id' => $data->user_id ]);
			$telegram->sendMessage([
			'chat_id' => $data->chat_id,
			'text' => "📝 متن مورد نظر خود را برای بخش پشتیبانی ارسال کنید:"."\n\n"."📃 متن فعلی : "."\n\n".file_get_contents("config/text/contact_us.txt"),
			'reply_markup' => $keyboard->go_back()
			]);
		}
		else
		{
			$database->update("users", [ 'last_query' => 'setting' ], [ 'id' => $data->user_id ]);
			$telegram->sendMessage([
			'chat_id' => $data->chat_id,
			'text' => "بخش مورد نظر خود را انتخاب نمایید:",
			'reply_markup' => $keyboard->key_setting()
			]);
		}
	}	
