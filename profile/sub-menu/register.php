<?php
	require_once dirname(__FILE__) . '/../../../autoload.php';
	
	if ( $constants->last_message === null ) 
	{
		$database->update("users", [ 'last_query' => 'register' ], [ 'id' => $data->user_id ]);
		$telegram->sendMessage([
		'chat_id' => $data->chat_id,
		'text' => "✅ لطفا نام و نام خانوادگی خود را به فارسی و به صورت کامل ارسال کنید.",
		'reply_markup' => $keyboard->go_back()
		]);
	}
	elseif ( $constants->last_message == 'register' ) 
	{
		if ( $data->text == $keyboard->buttons['go_back'] ) 
		{
			$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
			
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'text' => "لطفا یک گزینه را انتخاب کنید:",
			'reply_markup' => $keyboard->key_start()
			]);
		} 
		else 
		{
			$database->update('users', ['name' => $data->text,'last_query' => 'mobile'], ['id' => $data->user_id]);	
			
			$reply_markup333=json_encode(
			["keyboard"=>
				[
				[["text"=>"📱دریافت شماره همراه📱" , "request_contact"=>true]],
				[$keyboard->buttons['go_back'],$keyboard->buttons['go_back_one_step']]				
				],
				"resize_keyboard"=>true
			]);
				
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'text' => "✅ لطفا شماره موبایل خود را با استفاده از دکمه زیر ویا به صورت دقیق نوشته و ارسال کنید.",
			'reply_markup' => $reply_markup333
			]); 
		}
	}
