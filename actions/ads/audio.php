<?php	
	if($data->caption=="" or $data->caption==null)
	{	
		for($y=0;$y<=sizeof($userInfo);$y++)
		{
			$telegram->sendAudio([
			'chat_id' => $userInfo[$y]['id'],
			'audio' =>  $data->audio_file_id,
			"parse_mode" =>"HTML",
			'reply_markup' => $keyboard->key_start()
			]);
			sleep(1);//sleep(3) == usleep(3 * 1000000) ==> 3 seconds
		}
	}
	else
	{
		for($y=0;$y<=sizeof($userInfo);$y++)
		{
			$telegram->sendAudio([
			'chat_id' => $userInfo[$y]['id'],
			'audio' =>  $data->audio_file_id,
			'caption' =>  $data->caption,
			"parse_mode" =>"HTML",
			'reply_markup' => $keyboard->key_start()
			]);
			sleep(1);//sleep(3) == usleep(3 * 1000000) ==> 3 seconds
		}
	}
