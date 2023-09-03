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
	
	if ( $constants->last_message == 'getCount' ) 
	{
		if ( $data->text == $keyboard->buttons['go_back'] ) 
		{
			$catInfo = $database->query("SELECT name FROM `category` where status=1 order by name ASC");
			
			$database->update('users', ['last_query' => 'category','last_request' => null], ['id' => $data->user_id]);	
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
			
			$telegram->deleteMessage([
			'chat_id' => $data->user_id,
			'message_id' => $data_inline[1],
			]);
			
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
		else 
		{
			$cartID   = $database->select('users', ['last_request'], ['id' => $data->user_id]);
			$proID    = $database->select('cart', ['product'], ['id' => $cartID[0]['last_request']]);
			$proCount = $database->select('product', ['count'], ['id' => $proID[0]['product']]);
			
			if (convertNumbers($data->text)>0 && convertNumbers($data->text)>=$proCount[0]['count'] && is_numeric(convertNumbers($data->text)))
			{
				$database->update("cart", ['count' => convertNumbers($data->text)], ['id' => $cartID[0]['last_request']]);
				$database->update("users", ['last_query' => 'getType'], ['id' => $data->user_id]);
				
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "✅ این محصول را برای کانال می خواهید یا گروه؟",
				'reply_markup' => $keyboard->key_getType()
				]);
			} 
			else 
			{
				$database->update("users", ['last_query' => 'getCount'], ['id' => $data->user_id]);
				
				$telegram->sendMessage([
				'chat_id' => $data->user_id,
				'text' => "⚠️ عدد وارد شده کمتر از حد مجاز است"."\n"."✅ تعداد مورد نیاز خود را به صورت عدد بزرگتر از صفر وارد نمایید.",
				'reply_markup' => $keyboard->go_back()
				]);
			}
		}
	}								