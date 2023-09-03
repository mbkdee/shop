<?php
	/* /usr/bin/php -q /home/sajadhaj/public_html/falBot/checkStatus.php */
	ignore_user_abort(1); // run script in background
	set_time_limit(0); // run script forever 	
	ini_set("log_errors", 0);
	require_once 'autoload.php';
	date_default_timezone_set('Asia/Tehran');
	
	$userInfo = $database->query("SELECT id FROM users WHERE status = 1")->fetchAll();

	for($i=0;$i<=sizeof($userInfo);$i++) 
	{
		$json=@file_get_contents("https://api.telegram.org/bot".$auth->bot_id."/sendChatAction?chat_id=".$userInfo[$i]['id']."&action=typing");
		$userStatus=json_decode($json, true);
		if($userStatus['ok']==false)
		{
			$database->update("users", ['status' => 0], ['id' => $userInfo[$i]['id']]);
		}
	}	  
	
	file_put_contents("config/lastUpdate.txt",jdate('H:i:s | l, Y/n/j'));
?>