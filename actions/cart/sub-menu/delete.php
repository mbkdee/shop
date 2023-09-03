<?php
	require_once dirname(__FILE__) . '/../../../autoload.php';
	
	$database->update("users", [ 'last_query' => null ], [ 'id' => $data->user_id ]);
	
	$text=str_replace("/","",$data->text);
	$data_inline = explode("_", $text);	
	if($data_inline[0]=="deleteItem")
	{
		if($database->has("cart", ["AND" => ["id" => $data_inline[1],"user_id" => $data->user_id]]))
		{
			$database->delete("cart", ["AND" => ["id" => $data_inline[1],"user_id" => $data->user_id]]);
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'parse_mode' => 'HTML',
			'text' => "محصول مورد نظر باموفقیت از سبد خرید حذف شد."
			]);
			require_once 'actions/cart/cart.php';
		}
		else
		{
			$telegram->sendMessage([
			'chat_id' => $data->user_id,
			'parse_mode' => 'HTML',
			'text' => "متاسفانه این محصول در سبد خرید شما نیست."
			]);
		}
	}	