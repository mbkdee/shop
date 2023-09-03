<?php
	require_once dirname(__FILE__) . '/../../../autoload.php';
	
	function convertNumbers($srting,$toPersian=false)
	{
		$en_num = array('0','1','2','3','4','5','6','7','8','9');
		$fa_num = array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
		if($toPersian)
		return str_replace($en_num, $fa_num, $srting);
        else 
		return str_replace($fa_num, $en_num, $srting);
	}
	
	if ( $constants->last_message === null ) 
	{
		$database->update("users", [ 'last_query' => 'peygiri' ], [ 'id' => $data->user_id ]);
		$telegram->sendMessage([
		'chat_id' => $data->user_id,
		'text' => "لطفا کد پیگیری خود را به صورت دقیق وارد نمایید:",
		'reply_markup' => $keyboard->go_back()
		]);
	}
	elseif ( $constants->last_message == 'peygiri' ) 
	{
		$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
		
		if ( $data->text == $keyboard->buttons['go_back'] ) 
		{
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'text' => "گزینه مورد نظر را انتخاب نمایید:",
			'reply_markup' => $keyboard->key_start()
			]);
		} 
		else 
		{
			if(is_numeric(convertNumbers($data->text)) && $database->has("orders", ["AND" => ["codePeygiri" => convertNumbers($data->text),"user_id" => $data->user_id]]))
			{
				$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
				$orderInfo = $database->select('orders', ['status'],["AND" => ["codePeygiri" => convertNumbers($data->text),"user_id" => $data->user_id]]);
				
				if($orderInfo[0]['status']=="0")
				{
					$status="🔘 سفارش شما هنوز پرداخت نشده است.";
				}
				else if($orderInfo[0]['status']=="1")
				{
					$status="🔘 سفارش شما در حال بررسی می باشد.";
				}
				else if($orderInfo[0]['status']=="2")
				{
					$status="🔘 سفارش شما تایید شده است و آماده انجام می باشد.";
				}
				else if($orderInfo[0]['status']=="3")
				{
					$status="🔘 سفارش شما انجام شده است.";
				}
				else
				{
					$status="🔘 سفارش شما رد شده است در صورت مشکل با پشتیبانی در ارتباط باشید.";
				}
				
				$telegram->sendMessage([
				'chat_id' => $data->chat_id,
				'parse_mode' => 'Markdown',
				'text' => $status,
				'reply_markup' => $keyboard->key_start()
				]);
			}
			else
			{
				$database->update("users", [ 'last_query' => 'peygiri' ], [ 'id' => $data->user_id ]);
				$telegram->sendMessage([
				'chat_id' => $data->chat_id,
				'text' => "کد وارد شده صحیح نمی باشد."."\n\n"."لطفا کد پیگیری خود را به صورت دقیق وارد نمایید:",
				'reply_markup' => $keyboard->go_back()
				]);
			}
		}
	}						