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
			$database->update('users', ['last_query' => 'categoryProAdd'], ['id' => $data->user_id]);	
			
			$catInfo = $database->query("SELECT name FROM `category` where status=1 order by name ASC");
			while($item_type=$catInfo->fetch(PDO::FETCH_ASSOC))
			{
				$keys[] = $item_type['name'];
			}
			
			$count=count($keys);
			if($count%2==0)
			{
				array_push($keys,"",$keyboard->buttons['go_back']);
			}
			else
			{
				array_push($keys,$keyboard->buttons['go_back']);
			}
			$j=0;
			$i=1;
			for($d=0;$d<=$count/2;$d++)
			{
				$options[]=array($keys[$i],$keys[$j]);
				$j=$j+2;
				$i=$i+2;
			}
			
			if( $options[0][0] !=null && $options[0][1] !=null )
			{
				$keyboard = Array(
				'keyboard' => $options ,
				'resize_keyboard' => true ,
				'one_time_keyboard' => false ,
				'selective' => true
				);
				
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'parse_mode' => 'Markdown', 
				'disable_web_page_preview' => 'true',
				'text' => "✅ لطفا دسته بندی مورد نظر خود را انتخاب نمایید:",
				'reply_markup' => json_encode($keyboard)
				]);
			}
			else
			{
				$database->update('users', ['last_query' => null], ['id' => $data->user_id]);	
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'parse_mode' => 'Markdown', 
				'disable_web_page_preview' => 'true',
				'text' => "⚠️ متاسفانه در حال حاضر دسته بندی ای وجود ندارد.",
				'reply_markup' => $keyboard->key_start_admin()
				]);
			}
		}
		elseif ( $constants->last_message == 'countProAdd' ) 
		{
			if ( $data->text == $keyboard->buttons['go_back'] ) 
			{
				$proID = $database->select('users', ['last_request'], ['id' => $data->user_id]);
				$database->delete("product", ["id" => $proID[0]['last_request']]);
				$database->update("users", [ 'last_query' => null,'last_request' => null ], [ 'id' => $data->user_id ]);
				
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "لطفا یک گزینه را انتخاب کنید:",
				'reply_markup' => $keyboard->key_start_admin()
				]);
			}
			else if ( $data->text == $keyboard->buttons['go_back_one_step'] ) 
			{
				$database->update("users", ['last_query' => 'priceProAdd'], ['id' => $data->user_id]);
				
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "✅ لطفا قیمت محصول مورد نظر را به تومان وارد کنید.",
				'reply_markup' => $keyboard->go_back_one_step()
				]);
			} 
			else 
			{
				if (convertNumbers($data->text)>=0 && is_numeric(convertNumbers($data->text)))
				{
					$proID = $database->select('users', ['last_request'], ['id' => $data->user_id]);
					$database->update("product", ['count' => convertNumbers($data->text)], ['id' => $proID[0]['last_request']]);
					$database->update("users", ['last_query' => 'descriptionProAdd'], ['id' => $data->user_id]);
					
					$telegram->sendMessage([
					'chat_id' => $data->user_id,
					'text' => "✅ لطفا توضيحات محصول مورد نظر را وارد کنيد.",
					'reply_markup' => $keyboard->go_back_one_step()
					]);
				} 
				else 
				{
					$database->update("users", ['last_query' => 'countProAdd'], ['id' => $data->user_id]);
					
					$telegram->sendMessage([
					'chat_id' => $data->user_id,
					'text' => "⚠️ تعداد را به صورت عدد وارد کنید."."\n"."✅ لطفا کمترین مقدار مجاز که کاربر می تواند سفارش دهد را وارد نمایید.",
					'reply_markup' => $keyboard->go_back_one_step()
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