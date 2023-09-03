<?php
	for($y=0;$y<=sizeof($userInfo);$y++)
	{
		$telegram->forwardMessage([
		'chat_id' => $userInfo[$y]['id'],
		'from_chat_id' => $data->user_id,
		'message_id' => $data->message_id,
		'reply_markup' => $keyboard->key_start()
		]);
		sleep(1);
	}	