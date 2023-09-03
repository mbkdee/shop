<?php	
	
	for($y=0;$y<=sizeof($userInfo);$y++)
	{
		$telegram->sendContact([
		'chat_id' => $userInfo[$y]['id'],
		'phone_number' =>  $data->contact_phone_number,
		'first_name' =>  $data->contact_first_name,
		'Last_name' =>  $data->contact_last_name,
		"parse_mode" =>"HTML",
		'reply_markup' => $keyboard->key_start()
		]);
		sleep(1);//sleep(3) == usleep(3 * 1000000) ==> 3 seconds
	}
