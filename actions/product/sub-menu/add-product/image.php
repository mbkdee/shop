<?php
	require_once dirname(__FILE__) . '/../../../../autoload.php';
	
	function save_image($url,$saveto)
	{
		$ch = curl_init ($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		$raw=curl_exec($ch);
		curl_close ($ch);
		if(file_exists($saveto)){
			unlink($saveto);
		}
		$fp = fopen($saveto,'x');
		fwrite($fp, $raw);
		fclose($fp);
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
		elseif ( $constants->last_message == 'imageProAdd' ) 
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
				$database->update("users", ['last_query' => 'descriptionProAdd'], ['id' => $data->user_id]);
				
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "✅ لطفا توضیحات محصول مورد نظر را وارد کنید.",
				'reply_markup' => $keyboard->go_back_one_step()
				]);
			} 
			else 
			{
				if (isset($data->photo))
				{ 
					$photo = $data->photo;
					foreach($photo as $pic)
					$pict = $pic['file_id'];
					$getfile=file_get_contents('https://api.telegram.org/bot'.$auth->bot_id.'/getFile?file_id='.$pict);
					$file=json_decode($getfile, true);
					
					$picurl='https://api.telegram.org/file/bot'.$auth->bot_id.'/'.$file['result']['file_path'];
					$Name=time().".png";
					$ImgName="images/".$Name;
					save_image($picurl,$ImgName);
					
					$proID = $database->select('users', ['last_request'], ['id' => $data->user_id]);
					$database->update("product", ['image' =>$Name,'status' =>1], ['id' => $proID[0]['last_request']]);
					$database->update("users", ['last_query' => null,'last_request' => null], ['id' => $data->user_id]);
					
					$telegram->sendMessage([
					'chat_id' => $data->user_id,
					'text' => "✅ محصول جدید باموفقیت افزوده شد.",
					'reply_markup' => $keyboard->key_start_admin()
					]);
				} 
				else 
				{
					$database->update("users", ['last_query' => 'imageProAdd'], ['id' => $data->user_id]);
					
					$telegram->sendMessage([
					'chat_id' => $data->user_id,
					'text' => "⚠️ یک عکس می بایست ارسال کنید."."\n"."✅ لطفا عکس محصول مورد نظر را ارسال کنید.",
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