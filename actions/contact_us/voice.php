<?php	
	if($data->caption=="" or $data->caption==null)
	{
		$telegram->sendVoice([
		'chat_id' => $data->rpto,
		'voice' =>  $data->voice_file_id,
		"parse_mode" =>"HTML",
		'reply_markup' => $keyboard->key_start()
		]);
	}
	else
	{
		$telegram->sendVoice([
		'chat_id' => $data->rpto,
		'voice' =>  $data->voice_file_id,
		'caption' =>  $data->caption,
		"parse_mode" =>"HTML",
		'reply_markup' => $keyboard->key_start()
		]);
	}	