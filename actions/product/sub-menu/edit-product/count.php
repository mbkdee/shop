<?php
	require_once dirname(__FILE__) . '/../../../../autoload.php';
	
	function convertNumbers($srting,$toPersian=false)
	{
		$en_num = array('0','1','2','3','4','5','6','7','8','9');
		$fa_num = array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
		if($toPersian)
		return str_replace($en_num, $fa_num, $srting);
        else 
		return str_replace($fa_num, $en_num, $srting);
	}
	
	if(in_array($data->user_id, $auth->admin_list))
	{
		if ( $constants->last_message === null ) 
		{
			$database->update("users", ['last_query' => 'countProEdit'], ['id' => $data->user_id]);
			
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'text' => "✅ لطفا تعداد موجودی محصول مورد نظر را وارد کنید.",
			'reply_markup' => $keyboard->go_back()
			]);
		}
		elseif ( $constants->last_message == 'countProEdit' ) 
		{
			if ( $data->text == $keyboard->buttons['go_back'] ) 
			{
				$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
				
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "بخش مورد نظر برای ویرایش محصول را انتخاب نمایید:",
				'reply_markup' => $keyboard->key_product_edit()
				]);
			}
			else 
			{
				if (convertNumbers($data->text)>=0 && is_numeric(convertNumbers($data->text)))
				{
					$proID = $database->select('users', ['last_request'], ['id' => $data->user_id]);
					$database->update("product", ['count' => convertNumbers($data->text)], ['id' => $proID[0]['last_request']]);
					$database->update("users", ['last_query' => null], ['id' => $data->user_id]);
					
					$telegram->sendMessage([
					'chat_id' => $data->user_id,
					'text' => "✅ تغییرات باموفقیت انجام شد!"."\n"."بخش مورد نظر برای ویرایش محصول را انتخاب نمایید:",
					'reply_markup' => $keyboard->key_product_edit()
					]);
				} 
				else 
				{
					$database->update("users", ['last_query' => 'countProEdit'], ['id' => $data->user_id]);
					
					$telegram->sendMessage([
					'chat_id' => $data->user_id,
					'text' => "⚠️ تعداد را به صورت عدد وارد کنید."."\n"."✅ لطفا تعداد موجودی محصول مورد نظر را وارد کنید.",
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