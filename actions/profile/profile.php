<?php
	require_once dirname(__FILE__) . '/../../autoload.php';
	
	$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
	$telegram->sendMessage([
	'chat_id' => $data->chat_id,
	'text' => "بخش مورد نظر خود را انتخاب نمایید:",
	'reply_markup' => $keyboard->key_profile()
	]);
