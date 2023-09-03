<?php
	require_once dirname(__FILE__).'/../autoload.php';
	
	$database->update("users", ['status' => 0, 'last_query' => null, 'last_request' => null], ['id' => $data->user_id]);
	
	$telegram->sendMessage([
	'chat_id' => $data->user_id,
	'parse_mode' => 'Markdown',
	'disable_web_page_preview' => 'true',
	'text' => "عضويت شما در ربات لغو گرديد."."\n"."براي عضويت مجدد  مي توانيد از دکمه زير استفاده کنيد ويا /start را مجددا ارسال کنيد.",
	'reply_markup' => $keyboard->key_stop() 
	]);
	
