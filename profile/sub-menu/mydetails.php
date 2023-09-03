<?php
	require_once dirname(__FILE__) . '/../../../autoload.php';
	if ($data->callback_query)
	{
		$telegram->answerCallbackQuery([
		'callback_query_id' => $data->callback_query_id,
		'show_alert' => false,
		'text'=>""
		]);
		
		if($data->text=="profileAdd")
		{
			require_once 'actions/profile/sub-menu/register.php';
		}
		else
		{
			require_once 'actions/profile/sub-menu/register.php';
		}
	}
	else
	{
		$userInfo = $database->select('users', ['reg_status','name','mobile'], ['id' => $data->user_id]);
		
		if( $userInfo[0]['reg_status'] == "0" )
		{
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'parse_mode' => 'Markdown',
			'text' => "متاسفانه اطلاعات شما یافت نشد!"."\n"."برای تکمیل اطلاعات دکمه زیر را انتخاب نمایید:" ,
			'reply_markup' => $keyboard->key_profileAdd()
			]);
		}
		else
		{
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'parse_mode' => 'Markdown',
			'text' =>
			"👤 نام و نام خانوادگی: ".$userInfo[0]['name']."\n".
			"📞 شماره موبایل: ".$userInfo[0]['mobile'],
			'reply_markup' => $keyboard->key_profileEdit()
			]);
		}
	}
