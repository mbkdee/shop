<?php
	require_once dirname(__FILE__) . '/../../autoload.php';
	
	function create_zip($files = array(),$destination = '',$overwrite = false) 
	{
		if(file_exists($destination) && !$overwrite) 
		{ 
			return false; 
		}
		$valid_files = array();
		if(is_array($files))
		{
			foreach($files as $file)
			{
				if(file_exists($file))
				{
					$valid_files[] = $file;
				}
			}
		}
		
		if(count($valid_files)) 
		{
			$zip = new ZipArchive();
			if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true)
			{
				return false;
			}
			foreach($valid_files as $file) 
			{
				$zip->addFile($file,$file);
			}
			$zip->close();
			return file_exists($destination);
		}
		else
		{
			return false;
		}
	}
	
	if(in_array($data->user_id, $auth->admin_list))
	{
		$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
		
		$userInfo = $database->query("SELECT id,username,date_created,name,mobile FROM users")->fetchAll();
		$aaddd="";
		for($i=0;$i<sizeof($userInfo);$i++) 
		{
			if ($i % 2 == 0) 
			{
				$tr='<tr style="text-align:center">';
			} 
			else 
			{
				$tr='<tr style="text-align:center;background:#eee">';
			}
			$aaddd .=  $tr.'<td>'.($i+1).'</td><td>'.$userInfo[$i]['id']."</td><td>".$userInfo[$i]['username']."</td><td>".$userInfo[$i]['name']."</td><td>".$userInfo[$i]['mobile']."</td><td>".$userInfo[$i]['date_created']."</td></tr>";
		}	
		$time=time();
		$aadddHTML='<!DOCTYPE html>
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<meta http-equiv="Content-Language" content="en" />
		<title>لیست کاربران</title>
		<center>
		<table align="center" width="80%" style="border:1px solid #ededed;margin-top:40px;" dir="rtl">
		<thead>
		<tr style="text-align:center;background:#f7f7f7">
		<th scope="col" width="20px">ردیف</th>
		<th scope="col">شناسه عددی</th>
		<th scope="col">نام کاربری</th>
		<th scope="col">نام و نام خانوادگی</th>
		<th scope="col">شماره تماس</th>
		<th scope="col">تاریخ عضویت</th>
		</tr>
		</thead>
		<tbody>
		'.$aaddd.'
		</tbody>
		</table>
		</center>
		</head>
		<body>';
		file_put_contents($time.'_list.html',$aadddHTML);	
		
		$files_to_zip = array($time.'_list.html');
		$result = create_zip($files_to_zip,$time.'_list.zip');
		
		$telegram->sendDocument([
		'chat_id' => $data->chat_id,
		'document' =>  $auth->path.$time.'_list.zip',
		'caption' =>  "لیست کاربران" ,
		"parse_mode" =>"HTML",
		'disable_web_page_preview' => 'true'
		]);
		unlink($time.'_list.html');
		unlink($time.'_list.zip');
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
