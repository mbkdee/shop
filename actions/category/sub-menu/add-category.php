<?php
	require_once dirname(__FILE__) . '/../../../autoload.php';
	if(in_array($data->user_id, $auth->admin_list))
	{
		if ( $constants->last_message === null ) 
		{
			$database->update("users", [ 'last_query' => 'add-category' ], [ 'id' => $data->user_id ]);
			$telegram->sendMessage([
			'chat_id' => $data->chat_id,
			'text' => "لطفا نام دسته بندی مورد نظر خود را به صورت فارسی ارسال نمایید:",
			'reply_markup' => $keyboard->go_back()
			]);
		}
		elseif ( $constants->last_message == 'add-category' ) 
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
			else 
			{
				if(!$database->has("category", ["AND" => ["name" => $data->text,"status" => 1]]))
				{
					$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
					
					$database->insert("category", ["name" => $data->text,"status" => 1]);
					
					$telegram->sendMessage([
					'chat_id' => $data->chat_id,
					'parse_mode' => 'Markdown',
					'text' => "دسته بندی ".$data->text." باموفقیت ثبت شد.",
					'reply_markup' => $keyboard->key_start_admin()
					]);
				}
				else
				{
					
					$database->update("users", [ 'last_query' => 'add-category' ], [ 'id' => $data->user_id ]);
					$telegram->sendMessage([
					'chat_id' => $data->chat_id,
					'text' => "متاسفانه این دسته بندی وجود دارد!"."\n"."لطفا نام دسته بندی مورد نظر خود را به صورت فارسی ارسال نمایید:",
					'reply_markup' => $keyboard->go_back()
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
