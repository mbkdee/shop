<?php

	require_once dirname(__FILE__) . '/../../autoload.php';
	
	function JalaliAgo($jalaliDate, $beforeDays) {
		list($y, $m, $d) = explode('/', $jalaliDate);
		$ts = jmktime(0, 0, 0, $m, $d, $y);
		for($i = 0; $i < $beforeDays; $i++) {
			$ts -= 86400;
		}
		return jdate('Y/n/d', $ts);
	}
	
	if(in_array($data->user_id, $auth->admin_list))
	{
		$count         = $database->count("users");
		$countActive   = $database->count("users", ["status" => 1]);
		$userToday     = $database->count('users', ["AND" => ["date_created" => jdate('Y/n/d'),"status" => 1]]);
		$userYesterday = $database->count('users', ["AND" => ["date_created" => JalaliAgo(jdate('Y/n/d'),1),"status" => 1]]);
		$alluserToday  = $database->count('users', ["date_created" => jdate('Y/n/d')]);
		$countDeactive = $database->count("users", ["status" => 0]);
		$order         = $database->count("orders", ["status[!]" => 0]);
		$orderAmount   = $database->sum("orders", ['amount'], ["status[!]" => 0]);
		$orderDone     = $database->count("orders", ['amount'], ["status" => 3]);
		
		if ($data->callback_query)
		{
			if($data->text=="stats-refresh")
			{
				$telegram->editMessageText([
				'chat_id' => $data->chat_id,
				'message_id' => $data->message_id,
				'parse_mode' => 'Markdown',
				'text' => 
				'🕑 ' . 'زمان به روزرسانی :'."\n".'`' . jdate('H:i:s | l, Y/n/d') . '`'."\n\n".
				'👨‍👩‍👧‍👦 ' . 'تعداد کل کاربران: `' . $count . '`'."\n".
				'✅ ' . 'تعداد کل کاربران فعال: `' . $countActive . '`'."\n".
				'☑️ ' . 'تعداد کل کاربران بلاک کننده: `' . $countDeactive . '`'."\n".
				'📆 ' . 'تعداد کل کاربران امروز: `' . $alluserToday . '`'."\n".
				'🆕 ' . 'تعداد کاربران فعال امروز: `' . $userToday . '`'."\n".
				'📊 ' . 'تعداد کاربران فعال دیروز: `' . $userYesterday . '`'."\n".
				'🛒 ' . 'تعداد کل سفارشات: `' . $order . '`'."\n".
				'🛍 ' . 'سفارشات انجام شده: `' . $orderDone . '`'."\n".
				'💰 ' . 'درآمد کل: `' . number_format($orderAmount) . '` تومان'."\n\n".
				'🕧 ' . 'آخرین به روزرسانی بلاک ها:'."\n".'`' . file_get_contents('config/lastUpdate.txt') . '`',
				'reply_markup' => $keyboard->key_stats()
				]);
				
				$telegram->answerCallbackQuery([
				'callback_query_id' => $data->callback_query_id,
				'show_alert' => false,
				'text'=>"آمار ربات به روز رسانی شد."
				]);
			}
		}
		else
		{
			$telegram->sendMessage([
			'chat_id' => $data->chat_id,
			'parse_mode' => 'Markdown',
			'text' => 
			'👨‍👩‍👧‍👦 ' . 'تعداد کل کاربران: `' . $count . '`'."\n".
			'✅ ' . 'تعداد کل کاربران فعال: `' . $countActive . '`'."\n".
			'☑️ ' . 'تعداد کل کاربران بلاک کننده: `' . $countDeactive . '`'."\n".
			'📆 ' . 'تعداد کل کاربران امروز: `' . $alluserToday . '`'."\n".
			'🆕 ' . 'تعداد کاربران فعال امروز: `' . $userToday . '`'."\n".
			'📊 ' . 'تعداد کاربران فعال دیروز: `' . $userYesterday . '`'."\n".
			'🛒 ' . 'تعداد کل سفارشات: `' . $order . '`'."\n".
			'🛍 ' . 'سفارشات انجام شده: `' . $orderDone . '`'."\n".
			'💰 ' . 'درآمد کل: `' . number_format($orderAmount) . '` تومان'."\n\n".
			'🕧 ' . 'آخرین به روزرسانی بلاک ها:'."\n".'`' . file_get_contents('config/lastUpdate.txt') . '`',
			'reply_markup' => $keyboard->key_stats()
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
