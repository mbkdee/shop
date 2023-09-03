<?php
	
	$telegram->sendMessage([
	'chat_id' => $data->rpto,
	'text' =>  $data->text,
	"parse_mode" =>"HTML",
	'reply_markup' => $keyboard->key_start()
	]);
