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
			$database->update("users", ['last_query' => 'imageProEdit'], ['id' => $data->user_id]);
			
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'text' => "✅ لطفا عکس محصول مورد نظر را ارسال کنید.",
			'reply_markup' => $keyboard->go_back()
			]);
		}
		elseif ( $constants->last_message == 'imageProEdit' ) 
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
					$database->update("product", ['image' =>$Name], ['id' => $proID[0]['last_request']]);
					$database->update("users", ['last_query' => null], ['id' => $data->user_id]);
					
					$telegram->sendMessage([
					'chat_id' => $data->user_id,
					'text' => "✅ تغییرات باموفقیت انجام شد!"."\n"."بخش مورد نظر برای ویرایش محصول را انتخاب نمایید:",
					'reply_markup' => $keyboard->key_product_edit()
					]);
				} 
				else 
				{
					$database->update("users", ['last_query' => 'imageProEdit'], ['id' => $data->user_id]);
					
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