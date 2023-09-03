<?php
	require_once dirname(__FILE__) . '/../../../../autoload.php';
	
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
				'text' => "⚠️ متاسفانه در حال حاضر دسته بندی ای وجود ندارد."."\n"."🛑 برای افزودن محصول ابتدا می بایست دسته بندی اضافه نمایید.",
				'reply_markup' => $keyboard->key_start_admin()
				]);
			}
		}
		elseif ( $constants->last_message == 'categoryProAdd' ) 
		{
			if ( $data->text == $keyboard->buttons['go_back'] ) 
			{
				$database->update("users", [ 'last_query' => null,'last_request' => null ], [ 'id' => $data->user_id ]);
				
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "گزینه مورد نظر را انتخاب نمایید:",
				'reply_markup' => $keyboard->key_start_admin()
				]);
			}
			else 
			{
				if($database->has("category", ["name" => $data->text]))
				{
					$catID = $database->select('category', ['id'], ['name' => $data->text]);
					$proID = $database->insert("product", ['c_id' => $catID[0]['id']]);
					$database->update("users", ['last_query' => 'nameProAdd','last_request' => $proID], ['id' => $data->user_id]);
					
					$telegram->sendMessage([
					'chat_id' => $data->user_id,
					'text' => "✅ لطفا نام محصول را به صورت دقیق نوشته و ارسال کنید.",
					'reply_markup' => $keyboard->go_back_one_step()
					]);
				}
				else
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
						'text' => "⚠️ متاسفانه در حال حاضر دسته بندی ای وجود ندارد."."\n"."🛑 برای افزودن محصول ابتدا می بایست دسته بندی اضافه نمایید.",
						'reply_markup' => $keyboard->key_start_admin()
						]);
					}
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
