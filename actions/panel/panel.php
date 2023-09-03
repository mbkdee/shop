<?php

	require_once dirname(__FILE__) . '/../../autoload.php';
	if(in_array($data->user_id, $auth->admin_list))
	{
		$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
		$telegram->sendMessage([
		'chat_id' => $data->chat_id,
		'text' => "بخش مورد نظر خود را انتخاب نمایید:",
		'reply_markup' => $keyboard->key_start_admin()
		]);
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
