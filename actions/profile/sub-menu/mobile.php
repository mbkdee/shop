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
		$database->update("users", [ 'last_query' => 'register' ], [ 'id' => $data->user_id ]);
		$telegram->sendMessage([
		'chat_id' => $data->chat_id,
		'text' => "✅ لطفا نام و نام خانوادگی خود را به فارسی و به صورت کامل ارسال کنید.",
		'reply_markup' => $keyboard->go_back()
		]);
	}
	elseif ( $constants->last_message == 'mobile' ) 
	{
		if ( $data->text == $keyboard->buttons['go_back'] ) 
		{
			$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
			
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'text' => "لطفا یک گزینه را انتخاب کنید:",
			'reply_markup' => $keyboard->key_start()
			]);
		}
		else if ( $data->text == $keyboard->buttons['go_back_one_step'] ) 
		{
			$database->update("users", [ 'last_query' => 'register' ], [ 'id' => $data->user_id ]);
			
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'text' => "✅ لطفا نام و نام خانوادگی خود را به فارسی و به صورت کامل ارسال کنید.",
			'reply_markup' => $keyboard->go_back()
			]);
		} 
		else 
		{
			if (isset($data->phone_number))
			{
				$database->update('users', ['mobile' => $data->phone_number,'last_query' => null,'reg_status' =>1], ['id' => $data->user_id]);	
				
				$text = $database->select('users', ['last_request'], ['id' => $data->user_id]);
				if($text[0]['last_request']=="cartReg")
				{
					$database->update('users', ['last_request' => null], ['id' => $data->user_id]);
					$telegram->sendMessage([
					'chat_id' => $data->user_id,
					'text' => "✅ اطلاعات شما باموفقیت ثبت گردید.",
					'reply_markup' => $keyboard->key_start()
					]);
					require_once 'actions/cart/cart.php';
				}
				else
				{
					$telegram->sendMessage([
					'chat_id' => $data->user_id,
					'text' => "✅ اطلاعات شما باموفقیت ثبت گردید.",
					'reply_markup' => $keyboard->key_start()
					]);
				}
				
			}
			else if (is_numeric(convertNumbers($data->text)) && preg_match("/^09[0-9]{9}$/", $data->text))
			{
				$database->update('users', ['mobile' => convertNumbers($data->text),'last_query' => null,'reg_status' =>1], ['id' => $data->user_id]);	
				
				$text = $database->select('users', ['last_request'], ['id' => $data->user_id]);
				if($text[0]['last_request']=="cartReg")
				{
					$database->update('users', ['last_request' => null], ['id' => $data->user_id]);
					$telegram->sendMessage([
					'chat_id' => $data->user_id,
					'text' => "✅ اطلاعات شما باموفقیت ثبت گردید.",
					'reply_markup' => $keyboard->key_start()
					]);
					require_once 'actions/cart/cart.php';
				}
				else
				{
					$telegram->sendMessage([
					'chat_id' => $data->user_id,
					'text' => "✅ اطلاعات شما باموفقیت ثبت گردید.",
					'reply_markup' => $keyboard->key_start()
					]);
				}
			} 
			else 
			{
				$database->update("users", [ 'last_query' => 'mobile' ], [ 'id' => $data->user_id ]);	
				$reply_markup=json_encode(
				["keyboard"=>
				[
				[["text"=>"📱دریافت شماره همراه📱" , "request_contact"=>true]],
				[$keyboard->buttons['go_back'],$keyboard->buttons['go_back_one_step']]				
				],
				"resize_keyboard"=>true
				]);
				
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "✅ لطفا شماره موبایل خود را با استفاده از دکمه زیر ویا به صورت دقیق نوشته و ارسال کنید.",
				'reply_markup' => $reply_markup
				]);
			}
		}
	}			