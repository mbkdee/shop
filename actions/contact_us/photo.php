<?php
	$photoid = json_encode($data->photo, JSON_PRETTY_PRINT);
	$photoidd = json_encode($photoid, JSON_PRETTY_PRINT); 
	$photoidd = str_replace('"[\n    {\n        \"file_id\": \"','',$photoidd);
	$pos = strpos($photoidd, '",\n');
	$pos = $pos -1;
	$substtr = substr($photoidd, 0, $pos);
	
	if($data->caption=="" or $data->caption==null)
	{
		$telegram->sendPhoto([
		'chat_id' => $data->rpto,
		'photo' =>  $substtr,
		"parse_mode" =>"HTML",
		'reply_markup' => $keyboard->key_start()
		]);
	}
	else
	{
		$telegram->sendPhoto([
		'chat_id' => $data->rpto,
		'photo' =>  $substtr,
		'caption' =>  $data->caption,
		"parse_mode" =>"HTML",
		'reply_markup' => $keyboard->key_start()
		]);
	}
