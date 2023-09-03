<?php

	require_once dirname(__FILE__) . '/../../autoload.php';
	
	$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
	if(in_array($data->user_id, $auth->admin_list))
	{
		$telegram->sendMessage([
		'chat_id' => $data->user_id,
		'text' => "برای پاسخ به پیام های دریافتی کاربران می بایست پیام مورد نظر را ریپلای کرده سپس پاسخ  مورد نظر را ارسال کنید."
		]);
	}
	else
	{
		$telegram->sendMessage([
		'chat_id' => $data->user_id,
		'text' => file_get_contents("config/text/help.txt")
		]);
	}