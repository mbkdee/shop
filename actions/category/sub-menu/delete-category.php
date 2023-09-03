<?php
	require_once dirname(__FILE__) . '/../../../autoload.php';
	if(in_array($data->user_id, $auth->admin_list))
	{
		if ( $constants->last_message === null ) 
		{
			$database->update('users', ['last_query' => 'delete-category'], ['id' => $data->user_id]);
			
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
				'text' => "✅ لطفا دسته بندی مورد نظر خود را انتخاب نمایید:"."\n\n"."⚠️ توجه داشته باشید که با حذف دسته بندی محصولات مربوط به آن هم پاک خواهد شد.",
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
		elseif ( $constants->last_message == 'delete-category' ) 
		{
			if ( $data->text == $keyboard->buttons['go_back'] ) 
			{
				$database->update("users", ['last_query' => null], ['id' => $data->user_id]);
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "گزینه مورد نظر را انتخاب نمایید:",
				'reply_markup' => $keyboard->key_start_admin()
				]);
			} 
			else if($database->has("category", ["name" => $data->text]))
			{
				$database->update("users", ['last_query' => null], ['id' => $data->user_id]);	
				$c_id = $database->select('category', ['id'], ['name' => $data->text]);		
				$proInfo = $database->select('product', ['id','image'], ["c_id" => $c_id[0]['id']]);
				for($i=0;$i<=sizeof($proInfo);$i++)
				{
					unlink("images/".$proInfo[$i]['image']);
				}
				$database->delete("product", ["c_id" => $c_id[0]['id']]);
				$database->delete("favorite", ["d_id" => $proInfo[0]['id']]);
				$database->delete("category", ["name" => $data->text]);
				$telegram->sendMessage([
				'chat_id' => $data->chat_id,
				'parse_mode' => 'Markdown', 
				'text' => "دسته بندی ".$data->text." باموفقیت حذف گردید.",
				'reply_markup' => $keyboard->key_start_admin()
				]);
			}
			else
			{
				$database->update('users', ['last_query' => 'delete-category'], ['id' => $data->user_id]);	
				
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
					'text' => "🚫 از دکمه های زیر استفاده کنید."."\n\n"."✅ لطفا دسته بندی مورد نظر خود را انتخاب نمایید:"."\n\n"."⚠️ توجه داشته باشید که با حذف دسته بندی محصولات مربوط به آن هم پاک خواهد شد.",
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
