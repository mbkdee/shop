<?php
	require_once dirname(__FILE__) . '/../../autoload.php';
	if ( $constants->last_message === null ) 
	{		
		$catInfo = $database->query("SELECT name FROM `category` where status=1 order by name ASC");
		
		$database->update('users', ['last_query' => 'category'], ['id' => $data->user_id]);	
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
			'reply_markup' => $keyboard->key_start()
			]);
		}
	}
	elseif ( $constants->last_message == 'category' ) 
	{
		if ( $data->text == $keyboard->buttons['go_back'] ) 
		{
			$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
			
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'text' => "گزینه مورد نظر را انتخاب نمایید:",
			'reply_markup' => $keyboard->key_start()
			]);
		}
		else 
		{
			if($database->has("category", ["name" => $data->text]))
			{
				$database->update("users", ['last_query' => 'category'], ['id' => $data->user_id]);
				
				$catID = $database->select('category', ['id'], ['name' => $data->text]);
				$result = $database->query("SELECT * FROM product WHERE status = 1 and price!=0 and c_id=".$catID[0]['id']." ORDER BY id DESC")->fetchAll();
				
				if(sizeof($result) > 0)
				{
					$text="<a href='".$auth->path."images/".$result['0']['image']."'>‌‌</a>".
					"#1"."/".sizeof($result)."\n\n".
					"🔸 نام محصول:"."\n".$result['0']['name']."\n\n".
					"🔹 قیمت: ".$result['0']['price']." تومان"."\n\n".
					"🔸 توضیحات: "."\n".$result['0']['description']."\n\n".
					"🆔 @".$auth->bot_Username;
					
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
					'reply_markup' => $keyboard->key_back_2("-".$result['0']['id']."-".$json['result']['message_id'],"-".$json['result']['message_id']."-0"."-".$catID[0]['id'])
					]);
				}
				else 
				{
					$telegram->sendMessage([
					'chat_id' => $data->chat_id,
					'parse_mode' => 'Markdown', 
					'text' => "متاسفانه در این دسته بندی محصولی یافت نشد!"
					]);
				}
			}
			else
			{
				$database->update('users', ['last_query' => 'category'], ['id' => $data->user_id]);	
				
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
					'reply_markup' => $keyboard->key_start()
					]);
				}
			}
		}
	}
