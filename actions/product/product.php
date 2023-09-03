<?php
/*
کانال سورس خونه ! پر از سورس هاي ربات هاي تلگرامي !
لطفا در کانال ما عضو شويد 
@source_home
https://t.me/source_home
*/
	require_once dirname(__FILE__) . '/../../autoload.php';
	
	if($data->callback_query)
	{
		$data_inline = explode("-", $data->text);		
		
		if($data_inline[0] == "next")
		{
			$result = $database->query("SELECT * FROM product WHERE status = 1 and price!=0 and c_id=".$data_inline[3]." ORDER BY id DESC")->fetchAll();
			
			$i = $data_inline[2] + 1;
			if($result[$i]['id'] == null)
			{
				$text = "🔚 محصول دیگری یافت نشد!";
				$key=$keyboard->key_back_1("-".$data_inline[1]."-".$i."-".$data_inline[3]);
			}
			else 
			{
				$text="<a href='".$auth->path."images/".$result[$i]['image']."'>‌‌</a>".
				"#".($i+1)."/".sizeof($result)."\n\n".
				"🔸 نام محصول:"."\n".$result[$i]['name']."\n\n".
				"🔹 قیمت: ".$result[$i]['price']." تومان"."\n\n".
				"🔸 توضیحات: "."\n".$result[$i]['description']."\n\n".
				"🆔 @".$auth->bot_Username;
				
				$key=$keyboard->key_back_3("-".$result[$i]['id']."-".$data_inline[1],"-".$data_inline[1]."-".$i."-".$data_inline[3],"-".$data_inline[1]."-".$i."-".$data_inline[3]);
			}
			
			$telegram->editMessageText([
			'chat_id' => $data->chat_id,
			'message_id' => $data_inline[1],
			'parse_mode' => 'HTML',
			'text' => $text,
			'reply_markup' => $key
			]);
			
			$telegram->answerCallbackQuery([
			'callback_query_id' => $data->callback_query_id,
			'show_alert' => false,
			'text'=>""
			]);
		}
		else if($data_inline[0] == "back")
		{
			$result = $database->query("SELECT * FROM product WHERE status = 1 and price!=0 and c_id=".$data_inline[3]." ORDER BY id DESC")->fetchAll();
			
			$i = $data_inline[2] - 1;
			if(sizeof($result) > 0)
			{
				$text="<a href='".$auth->path."images/".$result[$i]['image']."'>‌‌</a>".
				"#".($i+1)."/".sizeof($result)."\n\n".
				"🔸 نام محصول:"."\n".$result[$i]['name']."\n\n".
				"🔹 قیمت: ".$result[$i]['price']." تومان"."\n\n".
				"🔸 توضیحات: "."\n".$result[$i]['description']."\n\n".
				"🆔 @".$auth->bot_Username;
				
				if($i == 0)
				{
					$key=$keyboard->key_back_2("-".$result[$i]['id']."-".$data_inline[1],"-".$data_inline[1]."-".$i."-".$data_inline[3]);
				} 
				else
				{
					$key=$keyboard->key_back_3("-".$result[$i]['id']."-".$data_inline[1],"-".$data_inline[1]."-".$i."-".$data_inline[3],"-".$data_inline[1]."-".$i."-".$data_inline[3]);
				}
				
				$telegram->editMessageText([
				'chat_id' => $data->chat_id,
				'message_id' => $data_inline[1],
				'parse_mode' => 'HTML',
				'text' => $text,
				'reply_markup' => $key
				]);
				
				$telegram->answerCallbackQuery([
				'callback_query_id' => $data->callback_query_id,
				'show_alert' => false,
				'text'=>""
				]);
			} 
			else 
			{
				$telegram->editMessageText([
				'chat_id' => $data->chat_id,
				'message_id' => $data_inline[1],
				'parse_mode' => 'HTML',
				'text' => "متاسفانه محصولی یافت نشد!"
				]);
				
				$telegram->answerCallbackQuery([
				'callback_query_id' => $data->callback_query_id,
				'show_alert' => false,
				'text'=>""
				]);
			}
		}
		else if($data_inline[0] == "buy" or $data_inline[0] == "buyf")
		{
			$prodouctInfo = $database->select('product', ['name','count','price'], ['id' => $data_inline[1]]);
			
			if($database->has("cart", ["AND" => ["user_id" => $data->user_id,"product" => $data_inline[1],"status" => 1]]))
			{
				$cartInfo = $database->select('cart', ['id'], ["AND" => ["user_id" => $data->user_id,"product" => $data_inline[1]]]);
				
				$cart_id = $cartInfo[0]['id'];
			}
			else
			{
				$cart_id = $database->insert("cart", [
				"user_id" => $data->user_id,
				"product" => $data_inline[1],
				'date' => jdate("Y/n/d"),
				"status" => 0
				]);
			}
			
			$database->update('users', ['last_query' => 'getCount','last_request' => $cart_id], ['id' => $data->user_id]);
			
			$telegram->deleteMessage([
			'chat_id' => $data->user_id,
			'message_id' => $data_inline[2],
			]);
			
			$telegram->sendMessage([
			'chat_id' => $data->chat_id,
			'parse_mode' => 'HTML',
			'text' => "✅ تعداد مورد نیاز خود را به صورت عدد بزرگتر از صفر وارد نمایید.",
			'reply_markup' => $keyboard->go_back()
			]);
			
			$telegram->answerCallbackQuery([
			'callback_query_id' => $data->callback_query_id,
			'show_alert' => false,
			'text'=>""
			]);
		}
		else if($data_inline[0] == "num")
		{
			$database->update('cart',['count' => $data_inline[1],'status' =>1],["id" => $data_inline[2]]);
			
			$telegram->answerCallbackQuery([
			'callback_query_id' => $data->callback_query_id,
			'show_alert' => false,
			'text'=>""
			]);
			
			$telegram->editMessageText([
			'chat_id' => $data->chat_id,
			'message_id' => $data_inline[3],
			'parse_mode' => 'HTML',
			'text' => "✅ محصول مورد نظر باموفقیت به سبد خرید شما افزوده شد."."\n\n"."🔸 گزینه مورد نظر خود را انتخاب نمایید:",
			'reply_markup' => $keyboard->key_continue("-".$data_inline[3])
			]);
		}
		else if($data_inline[0] == "continueBuy")
		{
			$telegram->answerCallbackQuery([
			'callback_query_id' => $data->callback_query_id,
			'show_alert' => false,
			'text'=>""
			]);
			
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
		else if($data_inline[0] == "endBuy")
		{
			$database->update('users', ['last_query' => null], ['id' => $data->user_id]);
			require_once 'actions/cart/cart.php';
		}
	}																																			
