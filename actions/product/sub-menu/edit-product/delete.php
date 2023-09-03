<?php
	require_once dirname(__FILE__) . '/../../../../autoload.php';
	
	if(in_array($data->user_id, $auth->admin_list))
	{
		$proID = $database->select('users', ['last_request'], ['id' => $data->user_id]);		
		
		$database->update("users", ['last_query' => null], ['id' => $data->user_id]);
		$proInfo = $database->select('product', ['image'], ["id" => $proID[0]['last_request']]);
		unlink("images/".$proInfo[0]['image']);
		$database->delete("favorite", ["d_id" => $proID[0]['last_request']]);
		$database->delete("product", ["id" => $proID[0]['last_request']]);
		
		$database->update('users', ['last_query' => 'edit-product'], ['id' => $data->user_id]);
		
		$catInfo = $database->query("SELECT name FROM `product` where status=1 order by name ASC");
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
			'text' => "✅ محصول مورد نظر باموفقیت حذف گردید."."\n"."لطفا محصول مورد نظر خود را انتخاب نمایید:",
			'reply_markup' => json_encode($keyboard)
			]);
		}
		else
		{
			$database->update('users', ['last_query' => null,'last_request' => null], ['id' => $data->user_id]);	
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'parse_mode' => 'Markdown', 
			'disable_web_page_preview' => 'true',
			'text' => "⚠️ متاسفانه در حال حاضر محصولی وجود ندارد.",
			'reply_markup' => $keyboard->key_start_admin()
			]);
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