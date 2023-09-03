<?php

	require_once dirname(__FILE__).'/../autoload.php';
	
	$link="t.me/".$auth->bot_Username."?start=";
	$text=str_replace($link,"",$data->text);
	$text=str_replace("/start","",$text);
	$text=str_replace(" ","",$text);
	
	if ($data->callback_query)
	{
		$telegram->answerCallbackQuery([
		'callback_query_id' => $data->callback_query_id,
		'show_alert' => false,
		'text'=>"عضویت شما در کانال تایید شد."
		]);
		
		$requestID = $database->select('users', ['last_request'], ['id' => $data->user_id]);
		if($requestID[0]['last_request']!=null)
		{
			if (is_numeric($requestID[0]['last_request']))
			{//invite
				if ($requestID[0]['last_request'] != $data->chat_id && $constants->user('team_leader_id') == 0 && $database->has("users", ["id" => $requestID[0]['last_request']])) 
				{	
					$database->update("users", ['subgroups[+]' => 1,'score[+]' => 1], ['id' => $requestID[0]['last_request']]);
					$database->update("users", ['team_leader_id' => $requestID[0]['last_request']], ['id' => $data->user_id]);
					
					$text = $database->select('users', ['subgroups'], ['id' => $requestID[0]['last_request']]);
			
					if($text[0]['subgroups'] %10 == 0 and $text[0]['subgroups']>0)
					{
						$database->update("users", ['subMoney[+]' => 500], ['id' => $requestID[0]['last_request']]);
					}
					
					$telegram->sendMessage([
					'chat_id' => $requestID[0]['last_request'],
					'parse_mode' => 'Markdown',
					'disable_web_page_preview' => 'true',
					'text' => "✅ کاربر جدیدی از طریق لینک شما وارد ربات شد."."\n"."شما تاکنون ".$text[0]['subgroups']." نفر رو به ربات دعوت کردید. 👌"
					]);
				}
				else
				{
					if ($requestID[0]['last_request'] == $data->chat_id) 
					{
						$telegram->sendMessage([
						'chat_id' => $data->chat_id,
						'parse_mode' => 'Markdown',
						'disable_web_page_preview' => 'true',
						'text' => "کاربر عزیز شما نمی توانید با لینک خودتان وارد ربات شوید."
						]);
					}
					else if (!$database->has("users", ["id" => $requestID[0]['last_request']])) 
					{
						$telegram->sendMessage([
						'chat_id' => $data->chat_id,
						'parse_mode' => 'Markdown',
						'disable_web_page_preview' => 'true',
						'text' => "لینک مورد استفاده معتبر نمی باشد."
						]);
					}
					else
					{
						$telegram->sendMessage([
						'chat_id' => $data->chat_id,
						'parse_mode' => 'Markdown',
						'disable_web_page_preview' => 'true',
						'text' => "شما قبلا توسط کاربر دیگری به ربات دعوت شده اید."
						]);
					}
				}
			}		
			$database->update("users", ['last_request' => null], ['id' => $data->user_id]);
		}
	}
	
	$database->update("users", ['status' => 1, 'last_query' => null, 'last_request' => null], ['id' => $data->user_id]);
	
	$id = str_replace("/start ","",$data->text);	
	if ($id != "" && is_numeric($id))
	{//invite
		if ($id != $data->chat_id && $constants->user('team_leader_id') == 0 && $database->has("users", ["id" => $id])) 
		{	
			$database->update("users", ['subgroups[+]' => 1,'score[+]' => 1], ['id' => $id]);
			$database->update("users", ['team_leader_id' => $id], ['id' => $data->user_id]);
			
			$text = $database->select('users', ['subgroups'], ['id' => $id]);
			
			$telegram->sendMessage([
			'chat_id' => $id,
			'parse_mode' => 'Markdown',
			'disable_web_page_preview' => 'true',
			'text' => $data->first_name." عزیز تبریک 🌹😍"."\n"."کاربر جدیدی از طریق لینک شما وارد ربات شد."."\n"."شما تاکنون ".$text[0]['subgroups']." نفر رو به ربات دعوت کردید. 👌"
			]);
		}
		else
		{
			if ($id == $data->chat_id) 
			{
				$telegram->sendMessage([
				'chat_id' => $data->chat_id,
				'parse_mode' => 'Markdown',
				'disable_web_page_preview' => 'true',
				'text' => "شما نمی توانید با لینک خودتان وارد ربات شوید."
				]);
			}
			else if (!$database->has("users", ["id" => $id])) 
			{
				$telegram->sendMessage([
				'chat_id' => $data->chat_id,
				'parse_mode' => 'Markdown',
				'disable_web_page_preview' => 'true',
				'text' => "لینک مورد استفاده معتبر نمی باشد."
				]);
			}
			else
			{
				$telegram->sendMessage([
				'chat_id' => $data->chat_id,
				'parse_mode' => 'Markdown',
				'disable_web_page_preview' => 'true',
				'text' => "شما قبلا توسط کاربر دیگری به ربات دعوت شده اید."
				]);
			}
		}
	}
	
	if($data->text=="/start" or $data->callback_query=="joinChannel")
	{
		$telegram->sendMessage([
		'chat_id' => $data->chat_id,
		'parse_mode' => 'Markdown',
		'disable_web_page_preview' => 'true',
		'text' => file_get_contents("config/text/start.txt"),
		'reply_markup' => $keyboard->key_start() 
		]);
	} 
	else
	{
		$telegram->sendMessage([
		'chat_id' => $data->chat_id,
		'parse_mode' => 'Markdown',
		'disable_web_page_preview' => 'true',
		'text' => "بخش مورد نظر خود را انتخاب نمایید:",
		'reply_markup' => $keyboard->key_start() 
		]);
	}