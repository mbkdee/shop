<?php	
	if($data->caption=="" or $data->caption==null)
	{	
		$telegram->sendDocument([
		'chat_id' => $data->rpto,
		'document' =>  $data->document_file_id,
		"parse_mode" =>"HTML",
		'reply_markup' => $keyboard->key_start()
		]);
	}
	else
	{
		$telegram->sendDocument([
		'chat_id' => $data->rpto,
		'document' =>  $data->document_file_id,
		'caption' =>  $data->caption,
		"parse_mode" =>"HTML",
		'reply_markup' => $keyboard->key_start()
		]);
	}	