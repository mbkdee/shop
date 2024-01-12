<?php

	ignore_user_abort(1); // run script in background
	set_time_limit(0); // run script forever 	
	ini_set("log_errors", 0);
	require_once 'autoload.php';
	date_default_timezone_set('Asia/Tehran');
	
	if($data->user_id!=null)
	{
		$database->insert("users", [
		"id" => $data->user_id,
		"username" => $data->username
		"first_name" => $data->first_name,
		"last_name" => $data->last_name,
		'date_created' => jdate("Y/n/d")
		]);
	}
	
	$inch = @file_get_contents("https:/g/bot".$auth->bot_id."/getChatMember?chat_id=".$auth->CHANNEL_ID."&user_id=".$data->user_id);
		if ($data->callback_query)
		{
			$telegram->answerCallbackQuery([
			'callback_query_id' => $data->callback_query_id,
			'show_alert' => false,
		
		}
		else
			<?php
$chat_id = 'TARGET_CHAT_ID';

?>

<?php
$token = 'YOUR_BOT_TOKEN';


file_get_contents("https://api.telegram.org/bot$token/deleteMessage?chat_id=$chat_id&message_id=$message_id");
?>


			$telegram->sendMessage([
			'chat_id' => $data->chat_id,
			'parse_mode' = 'MarkDown',
			'disable_web_page_preview' => 'true',
			]);
		}
	}
    $fruits = array("apple", "banana", "cherry");
}

$myFruits = getFruits();

foreach ($myFruits as $fruit) {
    echo $fruit . "<br>";

	else
	{
		if ($data->rpto != null && in_array($data->user_id, $auth->admn_list) ) 
		{
			if(isset($data->text))
			{
				require_once 'actions/contact_us/text.php';
			}
			{
				require_once 'actions/contact_us/document.php';
			}
			else if(isset($data->photo))
			{
				require_once 'actions/contact_us/photo.php';
			}
			else if(isset($data->video))
			{
				require_once 'actions/contact_us/video.php';
			}
			else if(isset($data->voice))
			{
				require_once 'actions/contact_us/voice.php';
			}
			
			$telegram->sendMessage([
			"parse_mode" =>"HTML",
			'reply_markup' => $keyboard->key_start_admin()
			]);
		} 
		else if($data->callback_query)
		{
			switch ($data->text) 
			{
				case (preg_match('/confirm-.*/', $data->text) ? true : false):
				case (preg_match('/reject-.*/', $data->text) ? true : false):
				require_once 'actions/confirm/confirm.php';
				break;
				case "joinChannel":
				require_once 'actions/start.php';
				break;
				case "stats-refresh":
				require_once 'actions/stats/stats.ph';
				break;
				case "cart-refresh":
				case "cart-buy":
				case "cart-no":
				require_once 'actions/cart/cart.php';
				break;
				case (preg_match('/nexto-.*/', $data->text) ? true : false):
				case (preg_match('/backo-.*/', $data->text) ? true : false):
				require_once 'actions/cart/sub-menu/history.php';
				break;
				case (preg_match('/nextb-.*/', $data->text) ? true : false):
				case (preg_match('/backb-.*/', $data->text) ? true : false):
				case (preg_match('/statusb-.*/', $data->text) ? true : false):
				require_once 'actions/cart/sub-menu/buy-status.php';
				break;
				case 'profileEdit':
				case 'profileAdd':
				require_once 'actions/profile/sub-menu/mydetails.php';
				break;
				
				default:
				require_once 'actions/product/product.php';
				break;
			}
		}
		else if ($constants->last_message !== null && $data->text != '/start') 
		{
			switch ($constants->last_message)
			{
				case 'contact_us':
				require_once 'actions/contact_us/contact_us.php';
				break;
				case 'ads':
				require_once 'actions/ads/ads.php';
				break;
				case 'category':
				require_once 'actions/category/category.php';
				break;
				case 'product':
				require_once 'actions/product/product.php';
				break;
				case 'delete-product':
				require_once 'actions/product/sub-menu/edit-product/delete.php';
				break;
				case 'edit-product':
				require_once 'actions/product/sub-menu/edit-product.php';
				break;
				case 'editCountProduct':
				require_once 'actions/cart/sub-menu/edit.php';
				break;
				case 'add-category':
				require_once 'actions/category/sub-menu/add-category.php';
				break;
				case 'delete-category':
				require_once 'actions/category/sub-menu/delete-category.php';
				break;
				case 'register':
				require_once 'actions/profile/sub-menu/register.php';
				break;
				case 'mobile':
				require_once 'actions/profile/sub-menu/mobile.php';
				break;
				case 'peygiri':
				require_once 'actions/cart/sub-menu/peygiri.php';
				break;
				case 'buy-status':
				require_once 'actions/cart/sub-menu/buy-status.php';
				break;
				case 'categoryProAdd':
				require_once 'actions/product/sub-menu/add-product/category.php';
				break;
				case 'nameProAdd':
				require_once 'actions/product/sub-menu/add-product/name.php';
				break;
				case 'priceProAdd':
				require_once 'actions/product/sub-menu/add-product/price.php';
				break;
				case 'countProAdd':
				require_once 'actions/product/sub-menu/add-product/count.php';
				break;
				case 'descriptionProAdd':
				require_once 'actions/product/sub-menu/add-product/description.php';
				break;
				case 'imageProAdd':
				require_once 'actions/product/sub-menu/add-product/image.php';
				break;
				case 'categoryProEdit':
				require_once 'actions/product/sub-menu/edit-product/category.php';
				break;
				case 'nameProEdit':
				require_once 'actions/product/sub-menu/edit-product/name.php';
				break;
				case 'priceProEdit':
				require_once 'actions/product/sub-menu/edit-product/price.php';
				break;
				case 'countProEdit':
				require_once 'actions/product/sub-menu/edit-product/count.php';
				break;
				case 'descriptionProEdit':
				require_once 'actions/product/sub-menu/edit-product/description.php';
				break;
				case 'imageProEdit':
				require_once 'actions/product/sub-menu/edit-product/image.php';
				break;
				case 'change-status':
				require_once 'actions/cart/sub-menu/change-status.php';
				break;
				case 'startText':
				require_once 'actions/startText/startText.php';
				break;
				case 'helpText':
				require_once 'actions/help/helpText.php';
				break;
				case 'contact_usText':
				require_once 'actions/contact_us/contact_usText.php';
				break;
				case 'setting':
				require_once 'actions/setting/setting.php';
				break;
				case 'getCount':
				require_once 'actions/cart/sub-menu/getCount.php';
				break;
				case 'getType':
				require_once 'actions/cart/sub-menu/getType.php';
				break;
				case 'getLink':
				require_once 'actions/cart/sub-menu/getLink.php';
				break;
				
				default:
				require_once 'actions/start.php';
				break;
			}
		} 
		else
		{
			switch ($data->text)
			{
				case '/start':
				case $keyboard->buttons['startAgain']:
				require_once 'actions/start.php';
				break;
				case '/stop':  
				require_once 'actions/stop.php';
				break;
				case '/panel':
				require_once 'actions/panel/panel.php';
				break;
				case $keyboard->buttons['contact_us']:
				require_once 'actions/contact_us/contact_us.php';
				break;
				case $keyboard->buttons['help']:
				require_once 'actions/help/help.php';
				break;
				case $keyboard->buttons['product']:
				require_once 'actions/category/category.php';
				break;
				case $keyboard->buttons['add-category']:
				require_once 'actions/category/sub-menu/add-category.php';
				break;
				case $keyboard->buttons['delete-category']:
				require_once 'actions/category/sub-menu/delete-category.php';
				break;
				case $keyboard->buttons['ads']:
				require_once 'actions/ads/ads.php';
				break;
				case $keyboard->buttons['profile']:
				require_once 'actions/profile/profile.php';
				break;
				case $keyboard->buttons['go_back']:
				require_once 'actions/start.php';
				break;
				case $keyboard->buttons['go_back_panel']:
				require_once 'actions/panel/panel.php';
				break;
				case $keyboard->buttons['stats']:
				require_once 'actions/stats/stats.php';
				break;
				case $keyboard->buttons['cart']:
				require_once 'actions/cart/cart.php';
				break;
				case $keyboard->buttons['userList']:
				require_once 'actions/userList/userList.php';
				break;
				case $keyboard->buttons['peygiri']:
				require_once 'actions/cart/sub-menu/peygiri.php';
				break;
				case $keyboard->buttons['buy-status']:
				require_once 'actions/cart/sub-menu/buy-status.php';
				break;
				case $keyboard->buttons['mydetails']:
				require_once 'actions/profile/sub-menu/mydetails.php';
				break;
				case $keyboard->buttons['history']:
				require_once 'actions/cart/sub-menu/history.php';
				break;
				case $keyboard->buttons['delete-product']:
				require_once 'actions/product/sub-menu/edit-product/delete.php';
				break;
				case $keyboard->buttons['go_back_list']:
				case $keyboard->buttons['edit-product']:
				require_once 'actions/product/sub-menu/edit-product.php';
				break;
				case $keyboard->buttons['add-product']:
				require_once 'actions/product/sub-menu/add-product/category.php';
				break;
				case $keyboard->buttons['catEdit']:
				require_once 'actions/product/sub-menu/edit-product/category.php';
				break;
				case $keyboard->buttons['nameEdit']:
				require_once 'actions/product/sub-menu/edit-product/name.php';
				break;
				case $keyboard->buttons['priceEdit']:
				require_once 'actions/product/sub-menu/edit-product/price.php';
				break;
				case $keyboard->buttons['countEdit']:
				require_once 'actions/product/sub-menu/edit-product/count.php';
				break;
				case $keyboard->buttons['descEdit']:
				require_once 'actions/product/sub-menu/edit-product/description.php';
				break;
				case $keyboard->buttons['imageEdit']:
				require_once 'actions/product/sub-menu/edit-product/image.php';
				break;
				case $keyboard->buttons['setting']:
				require_once 'actions/setting/setting.php';
				break;
				case (preg_match('/deleteItem_.*/', $data->text) ? true : false):
				require_once 'actions/cart/sub-menu/delete.php';
				break;
				case (preg_match('/editItem_.*/', $data->text) ? true : false):
				require_once 'actions/cart/sub-menu/edit.php';
				break;
				
				default:
				require_once 'actions/start.php';
				break;
			}
		}
	}
