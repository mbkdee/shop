<?php
	require_once dirname(__FILE__) . '/../../../autoload.php';
	
	if(in_array($data->user_id, $auth->admin_list))
	{
		if ( $data->text == $keyboard->buttons['go_back'] ) 
		{
			$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'text' => "گزینه مورد نظر را انتخاب نمایید:",
			'reply_markup' => $keyboard->key_start_admin()
			]);
		}
		else if($data->callback_query)
		{
			$data_inline = explode("-", $data->text);	
			
			$result = $database->query("SELECT * FROM orders WHERE status != 0 ORDER BY id DESC")->fetchAll();
			
			if($data_inline[0] == "nextb")
			{
				$i = $data_inline[2] + 1;
				if($result[$i]['id'] == null)
				{
					$text = "🔚 سفارش دیگری یافت نشد!";
					$key=$keyboard->key_back_1("b-".$data_inline[1]."-".$i."-".$data_inline[3]);
				}
				else 
				{
					
					if($result[$i]['status']=="0")
					{
						$status="🔘 سفارش هنوز پرداخت نشده است.";
					}
					else if($result[$i]['status']=="1")
					{
						$status="🔘 سفارش در حال بررسی می باشد.";
					}
					else if($result[$i]['status']=="2")
					{
						$status="🔘 سفارش تایید شده است و آماده انجام می باشد.";
					}
					else if($result[$i]['status']=="3")
					{
						$status="🔘 سفارش شما انجام شده است.";
					}
					else
					{
						$status="🔘 سفارش رد شده است در صورت مشکل با پشتیبانی در ارتباط باشید.";
					}
					
					$text="#".($i+1)."/".sizeof($result)."\n\n".
					"🔹 نام خریدار:"."\n".$result[$i]['name']."\n\n".
					"🔸 لیست سفارش:"."\n".$result[$i]['cart_list']."\n\n".
					"🔹 تاریخ ثبت سفارش: "."\n".$result[$i]['date']."\n\n".
					"🔹 کدپیگیری: "."\n".$result[$i]['codePeygiri']."\n\n".
					"🔸 وضعیت: "."\n".$status;
					
					$key=$keyboard->key_status_3("b-".$result[$i]['id'],"b-".$data_inline[1]."-".$i."-".$data_inline[3],"b-".$data_inline[1]."-".$i."-".$data_inline[3]);
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
			else if($data_inline[0] == "backb")
			{
				$i = $data_inline[2] - 1;
				if(sizeof($result) > 0)
				{
					if($result[$i]['status']=="0")
					{
						$status="🔘 سفارش هنوز پرداخت نشده است.";
					}
					else if($result[$i]['status']=="1")
					{
						$status="🔘 سفارش در حال بررسی می باشد.";
					}
					else if($result[$i]['status']=="2")
					{
						$status="🔘 سفارش تایید شده است و آماده انجام می باشد.";
					}
					else if($result[$i]['status']=="3")
					{
						$status="🔘 سفارش شما انجام شده است.";
					}
					else
					{
						$status="🔘 سفارش رد شده است در صورت مشکل با پشتیبانی در ارتباط باشید.";
					}
					
					$text="#".($i+1)."/".sizeof($result)."\n\n".
					"🔹 نام خریدار:"."\n".$result[$i]['name']."\n\n".
					"🔸 لیست سفارش:"."\n".$result[$i]['cart_list']."\n\n".
					"🔹 تاریخ ثبت سفارش: "."\n".$result[$i]['date']."\n\n".
					"🔹 کدپیگیری: "."\n".$result[$i]['codePeygiri']."\n\n".
					"🔸 وضعیت: "."\n".$status;
					
					if($i == 0)
					{
						$key=$keyboard->key_status_2("b-".$result[$i]['id'],"b-".$data_inline[1]."-".$i."-".$data_inline[3]);
					} 
					else
					{
						$key=$keyboard->key_status_3("b-".$result[$i]['id'],"b-".$data_inline[1]."-".$i."-".$data_inline[3],"b-".$data_inline[1]."-".$i."-".$data_inline[3]);
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
					'text' => "متاسفانه لیست سفارشات خالی می باشد!"
					]);
					
					$telegram->answerCallbackQuery([
					'callback_query_id' => $data->callback_query_id,
					'show_alert' => false,
					'text'=>""
					]);
				}
			}
			else if($data_inline[0] == "statusb")
			{
				$database->update("users", [ 'last_query' => 'change-status','last_request' => $data_inline[1] ], [ 'id' => $data->user_id ]);
				
				$telegram->sendMessage([
				'chat_id' => $data->chat_id,
				'text' => "وضعیت جدید را انتخاب نمایید:",
				'reply_markup' => $keyboard->key_status_change()
				]);
				
				$telegram->answerCallbackQuery([
				'callback_query_id' => $data->callback_query_id,
				'show_alert' => false,
				'text'=>""
				]);
			}
			} 
			else 
			{		
				$result = $database->query("SELECT * FROM orders WHERE status != 0 ORDER BY id DESC")->fetchAll();
				
				if(sizeof($result) > 0)
				{
					
					if($result['0']['status']=="0")
					{
						$status="🔘 سفارش هنوز پرداخت نشده است.";
					}
					else if($result['0']['status']=="1")
					{
						$status="🔘 سفارش در حال بررسی می باشد.";
					}
					else if($result['0']['status']=="2")
					{
						$status="🔘 سفارش تایید شده است و آماده انجام می باشد.";
					}
					else if($result['0']['status']=="3")
					{
						$status="🔘 سفارش شما انجام شده است.";
					}
					else
					{
						$status="🔘 سفارش رد شده است در صورت مشکل با پشتیبانی در ارتباط باشید.";
					}
					
					$text="#1"."/".sizeof($result)."\n\n".
					"🔹 نام خریدار:"."\n".$result['0']['name']."\n\n".
					"🔸 محصولات خریداری شده:"."\n".$result['0']['cart_list']."\n\n".
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
					'reply_markup' => $keyboard->key_status_2("b-".$result['0']['id'],"b-".$json['result']['message_id']."-0"."-".$search_id)
					]);
				} 
				else 
				{
					$telegram->sendMessage([
					'chat_id' => $data->chat_id,
					'parse_mode' => 'Markdown', 
					'text' => "متاسفانه هنوز سفارشی ثبت نشده است.!"
					]);
				}
			}
		}
		else
		{
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'text' =>  "متاسفانه شما اجازه دسترسی به این بخش را ندارید.",
			"parse_mode" =>"HTML",
			'reply_markup' => $keyboard->key_start()
			]);
		}												