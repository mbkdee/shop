<?php
	for($y=0;$y<=sizeof($userInfo);$y++)
	{
		$telegram->sendMessage([
		'chat_id' => $userInfo[$y]['id'],
		'text' =>  $data->text,
		"parse_mode" =>"HTML",
		'reply_markup' => $keyboard->key_start()
		]);
		sleep(1);//sleep(3) == usleep(3 * 1000000) ==> 3 seconds
	}	