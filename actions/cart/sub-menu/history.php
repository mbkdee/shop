<?php
	require_once dirname(__FILE__) . '/../../../autoload.php';
	
	if ( $data->text == $keyboard->buttons['go_back'] ) 
	{
		$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
		$telegram->sendMessage([
		'chat_id' => $data->user_id,
		'text' => "گزینه مورد نظر را انتخاب نمایید:",
		'reply_markup' => $keyboard->key_start()
		]);
	}
	else if($data->callback_query)
	{
		$data_inline = explode("-", $data->text);	
		
		$result = $database->query("SELECT * FROM orders WHERE `user_id`='".$data->user_id."' and status != 0 ORDER BY id DESC")->fetchAll();
		
		if($data_inline[0] == "nexto")
		{
			$i = $data_inline[2] + 1;
			if($result[$i]['id'] == null)
			{
				$text = "🔚 سفارش دیگری یافت نشد!";
				$key=$keyboard->key_back_1("o-".$data_inline[1]."-".$i."-".$data_inline[3]);
			}
			else 
			{
				if($result[$i]['status']=="0")
				{
					$status="🔘 سفارش شما هنوز پرداخت نشده است.";
				}
				else if($result[$i]['status']=="1")
				{
					$status="🔘 سفارش شما در حال بررسی می باشد.";
				}
				else if($result[$i]['status']=="2")
				{
					$status="🔘 سفارش شما تایید شده است و آماده انجام می باشد.";
				}
				else if($result[$i]['status']=="3")
				{
					$status="🔘 سفارش شما انجام شده است.";
				}
				else
				{
					$status="🔘 سفارش شما رد شده است در صورت مشکل با پشتیبانی در ارتباط باشید.";
				}
				
				$text="#".($i+1)."/".sizeof($result)."\n\n".
				"🔹 نام خریدار:"."\n".$result[$i]['name']."\n\n".
				"🔸 لیست سفارش:"."\n".$result[$i]['cart_list']."\n\n".
				"🔹 تاریخ ثبت سفارش: "."\n".$result[$i]['date']."\n\n".
				"🔹 کدپیگیری: "."\n".$result[$i]['codePeygiri']."\n\n".
				"🔸 وضعیت: "."\n".$status;
				
				$key=$keyboard->key_history_back_2("o-".$data_inline[1]."-".$i."-".$data_inline[3],"o-".$data_inline[1]."-".$i."-".$data_inline[3]);
			}
			
			$telegram->editMessageText([
			'chat_id' => $data->chat_id,
			'message_id' => $data_inline[1],
			'parse_mode' => 'HTML',
			'text' => $text,
			'reply_markup' => $key
			]);
			
			$telegram->answerCallbackQuery([
			'callback_query_id' => $data->callback_query_id,
			'show_alert' => false,
			'text'=>""
			]);
		}
		else if($data_inline[0] == "backo")
		{
			$i = $data_inline[2] - 1;
			if(sizeof($result) > 0)
			{
				if($result[$i]['status']=="0")
				{
					$status="🔘 سفارش شما هنوز پرداخت نشده است.";
				}
				else if($result[$i]['status']=="1")
				{
					$status="🔘 سفارش شما در حال بررسی می باشد.";
				}
				else if($result[$i]['status']=="2")
				{
					$status="🔘 سفارش شما تایید شده است و آماده انجام می باشد.";
				}
				else if($result[$i]['status']=="3")
				{
					$status="🔘 سفارش شما انجام شده است.";
				}
				else
				{
					$status="🔘 سفارش شما رد شده است در صورت مشکل با پشتیبانی در ارتباط باشید.";
				}
				
				$text="#".($i+1)."/".sizeof($result)."\n\n".
				"🔹 نام خریدار:"."\n".$result[$i]['name']."\n\n".
				"🔸 لیست سفارش:"."\n".$result[$i]['cart_list']."\n\n".
				"🔹 تاریخ ثبت سفارش: "."\n".$result[$i]['date']."\n\n".
				"🔹 کدپیگیری: "."\n".$result[$i]['codePeygiri']."\n\n".
				"🔸 وضعیت: "."\n".$status;
				
				if($i == 0)
				{
					$key=$keyboard->key_history_back_1("o-".$data_inline[1]."-".$i."-".$data_inline[3]);
				} 
				else
				{
					$key=$keyboard->key_history_back_2("o-".$data_inline[1]."-".$i."-".$data_inline[3],"o-".$data_inline[1]."-".$i."-".$data_inline[3]);
				}
				
				$telegram->editMessageText([
				'chat_id' => $data->chat_id,
				'message_id' => $data_inline[1],
				'parse_mode' => 'HTML',
				'text' => $text,
				'reply_markup' => $key
				]);
				
				$telegram->answerCallbackQuery([
				'callback_query_id' => $data->callback_query_id,
				'show_alert' => false,
				'text'=>""
				]);
			} 
			else 
			{
				$telegram->editMessageText([
				'chat_id' => $data->chat_id,
				'message_id' => $data_inline[1],
				'parse_mode' => 'HTML',
				'text' => "متاسفانه شما هنوز سفارشی انجام نداده اید!"
				]);
				
				$telegram->answerCallbackQuery([
				'callback_query_id' => $data->callback_query_id,
				'show_alert' => false,
				'text'=>""
				]);
			}
		}
	} 
	else 
	{		
		$result = $database->query("SELECT * FROM orders WHERE `user_id`='".$data->user_id."' and status != 0 ORDER BY id DESC")->fetchAll();
		
		if(sizeof($result) > 0)
		{
			if($result['0']['status']=="0")
			{
				$status="🔘 سفارش شما هنوز پرداخت نشده است.";
			}
			else if($result['0']['status']=="1")
			{
				$status="🔘 سفارش شما در حال بررسی می باشد.";
			}
			else if($result['0']['status']=="2")
			{
				$status="🔘 سفارش شما تایید شده است و آماده انجام می باشد.";
			}
			else if($result['0']['status']=="3")
			{
				$status="🔘 سفارش شما انجام شده است.";
			}
			else
			{
				$status="🔘 سفارش شما رد شده است در صورت مشکل با پشتیبانی در ارتباط باشید.";
			}
			
			$text="#1"."/".sizeof($result)."\n\n".
			"🔹 نام خریدار:"."\n".$result['0']['name']."\n\n".
			"🔸 لیست سفارش:"."\n".$result['0']['cart_list']."\n\n".
			"🔹 تاریخ ثبت سفارش: "."\n".$result['0']['date']."\n\n".
			"🔹 کدپیگیری: "."\n".$result['0']['codePeygiri']."\n\n".
			"🔸 وضعیت: "."\n".$status;
			
			$json = $telegram->sendMessage([
			'chat_id' => $data->chat_id,
			'parse_mode' => 'HTML', 
			'text' => $text
			]);
			
			$telegram->editMessageText([
			'chat_id' => $data->chat_id,
			'message_id' => $json['result']['message_id'],
			'parse_mode' => 'HTML',
			'text' => $text,
			'reply_markup' => $keyboard->key_history_back_1("o-".$json['result']['message_id']."-0"."-".$search_id)
			]);
		} 
		else 
		{
			$telegram->sendMessage([
			'chat_id' => $data->chat_id,
			'parse_mode' => 'Markdown', 
			'text' => "متاسفانه شما هنوز سفارشی انجام نداده اید!"
			]);
		}
	}								