<?php
	require_once dirname(__FILE__) . '/../../../autoload.php';
	
	if(in_array($data->user_id, $auth->admin_list))
	{
		if ( $constants->last_message == 'change-status' ) 
		{
			if ( $data->text == $keyboard->buttons['go_back'] ) 
			{
				$database->update("users", [ 'last_query' => null,'last_request' => null], [ 'id' => $data->user_id ]);
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "گزینه مورد نظر را انتخاب نمایید:",
				'reply_markup' => $keyboard->key_start_admin()
				]);
			}
			else
			{
				if($data->text==$keyboard->buttons['status-0'] or $data->text==$keyboard->buttons['status-1'] or $data->text==$keyboard->buttons['status-2'] or $data->text==$keyboard->buttons['status-3'] or $data->text==$keyboard->buttons['status-4'])
				{
					
					if($data->text==$keyboard->buttons['status-0'])
					{
						$status="0";
					}
					else if($data->text==$keyboard->buttons['status-1'])
					{
						$status="1";
					}
					else if($data->text==$keyboard->buttons['status-2'])
					{
						$status="2";
					}
					else if($data->text==$keyboard->buttons['status-3'])
					{
						$status="3";
					}
					else
					{
						$status="4";
					}
					
					$p_id = $database->select('users', ['last_request'], ['id' => $data->user_id]);
					$u_id = $database->select('orders', ['user_id'], ['id' => $p_id[0]['last_request']]);
					
					$database->update('orders',['status' => $status],["id" =>$p_id[0]['last_request']]);	
					$database->update("users", [ 'last_query' => null,'last_request' => null], [ 'id' => $data->user_id ]);
					
					$telegram->sendMessage([
					'chat_id' => $data->user_id,
					'text' => "✅ وضعیت جدید باموفقیت ثبت شد.",
					'reply_markup' => $keyboard->key_start_admin()
					]);
					
					$telegram->sendMessage([
					'chat_id' => $u_id[0]['user_id'],
					'text' => "✅ وضعیت سفارش شما توسط ادمین تغییر یافت!"."\n"."برای مشاهده وضعیت جدید از قسمت پیگیری سفارش اقدام نمایید.",
					'reply_markup' => $keyboard->key_start()
					]);
				}
				else
				{
					$database->update("users", [ 'last_query' => 'change-status' ], [ 'id' => $data->user_id ]);
					
					$telegram->sendMessage([
					'chat_id' => $data->user_id,
					'text' => "وضعیت جدید را انتخاب نمایید:",
					'reply_markup' => $keyboard->key_status_change()
					]);
				}	
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