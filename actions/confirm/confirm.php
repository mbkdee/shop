<?php

	require_once dirname(__FILE__) . '/../../autoload.php';
	
	if(in_array($data->user_id, $auth->admin_list))
	{
		if ($data->callback_query)
		{	
			$data_inline=explode("-",$data->text);
			if($data_inline[0]=="confirm")
			{
				$database->update("orders", ['status' => 3], [ 'id' => $data_inline[2]]);
				
				$orderInfo = $database->select('orders', '*', ['id' => $data_inline[2]]);
									
				$telegram->editMessageText([
				'chat_id' => $data->user_id, 
				'message_id' => $data->message_id,
				'parse_mode' => 'MarkDown',
				'disable_web_page_preview' => 'true',
				'text' => 
				"🔸 سفارش مورد نظر تایید گردید!"."\n\n".
				"🆔 شناسه کاربری: `".$orderInfo[0]['user_id']."`\n".
				"👤 نام و نام خانوادگی: `".$orderInfo[0]['name']."`\n".
				"🔢 شماره تماس: `".$orderInfo[0]['mobile']."`\n".
				"📅 زمان ثبت سفارش:"."\n`".$orderInfo[0]['paymentTime']."`\n\n".
				"🏧 کد پیگیری: `".$orderInfo[0]['codePeygiri']."`"."\n\n".
				"🛒 لیست سفارش: "."\n".$orderInfo[0]['cart_list']
				]);
				
				$telegram->sendMessage([
				'chat_id' => $data_inline[1],
				'parse_mode' => 'Markdown',
				'text' => "✅ سفارش شما با کدپیگیری `".$orderInfo[0]['codePeygiri']."` باموفقیت انجام شد.",
				'reply_markup' => $keyboard->key_start()
				]);
			}
			else if($data_inline[0]=="reject")
			{
				$database->delete("orders", ['id' => $data_inline[2]]);
				
				$telegram->editMessageText([
				'chat_id' => $data->user_id, 
				'message_id' => $data->message_id,
				'parse_mode' => 'Markdown',
				'text' => "❌ سفارش مورد نظر از دیتابیس حذف گردید!"
				]);
				
				$telegram->sendMessage([
				'chat_id' => $data_inline[1],
				'parse_mode' => 'Markdown',
				'text' => "❌ متاسفانه سفارش شما توسط ادمین تایید نشد!",
				'reply_markup' => $keyboard->key_start()
				]);
			}
			
			$telegram->answerCallbackQuery([
			'callback_query_id' => $data->callback_query_id,
			'show_alert' => false,
			'text'=>""
			]);
		}
		else
		{
			$telegram->sendMessage([
			'chat_id' => $data->chat_id,
			'parse_mode' => 'Markdown',
			'text' => '⚠️ دستور وارد شده معتبر نمی باشد!'
			]);
		}
	}
	else
	{
		$telegram->sendMessage([
		'chat_id' => $data->user_id,
		'text' => "⚠️ متاسفانه شما اجازه دسترسی به این بخش را ندارید.",
		"parse_mode" =>"HTML",
		'reply_markup' => $keyboard->key_start()
		]);
	}
