<?php
	class keyboard
	{
		public $buttons = [
        'contact_us'                    => '💬 پشتیبانی',
        'help'                          => '⚠️ راهنما',
        'payment'                       => '🎁 صدور بن تخفیف',
        'refresh'                       => '🔄 به روزرسانی',
        'ok'                            => '✅ فعال',
        'cancel'                        => '❌ غیرفعال',
        'add_offCode'                   => '🎁 افزودن کد تخفیف',
        'userList'                      => '👨‍👨‍👧‍👦 لیست کاربران',
        'buy'                           => '🛒 تکمیل خرید',
        'ads'                           => '📬 ارسال تبلیغات',
        'add-product'                   => '🛍 افزودن محصول',
        'edit-product'                  => '🛍 ویرایش محصول',
        'delete-product'                => '🗑 حذف',
        'add-category'                  => '🗂 افزودن دسته بندی',
        'delete-category'               => '🗂 حذف دسته بندی',
        'product'                       => '🛍 پلن های تبلیغاتی',
        'history'                       => '📦 سفارشات من',
        'cart'                          => '🛒 سبد خرید',
        'continueBuy'                   => '🛍 ادامه خرید',
        'peygiri'                       => '📋 پیگیری سفارش',
        'joinChannel'                   => '🤗 عضو شدم 🤗',
        'profile'                       => '👤 پروفایل کاربری',
        'mydetails'                     => '👤 مشخصات من',
        'buy-status'                    => '📦 وضعیت خریدها',
        'profileAdd'                    => '📝 تکمیل پروفایل',
        'profileEdit'                   => '📝 ویرایش اطلاعات',
        'startAgain'                    => '♻️ شروع مجدد',
        'channel'                       => '📣 عضویت در کانال',
        'stats'                         => '📊 آمار',
        'yes'                           => 'بله',
        'no'                            => 'خیر، نیاز به ویرایش دارد',
        'catEdit'                       => '🗂 دسته بندی',
        'nameEdit'                      => '🆔 نام',
        'priceEdit'                     => '💵 قیمت',
        'countEdit'                     => '🔢 حداقل تعداد',
        'descEdit'                      => '📃 توضیحات',
        'imageEdit'                     => '🖼 تصویر',
        'startText'                     => '🗯 متن شروع',
        'contact_usText'                => '💬 متن پشتیبانی',
        'helpText'                      => '⚠️ متن راهنما',
        'setting'                       => '⚙️ تنظیمات',
        'status-0'                      => 'پرداخت نشده',
        'status-1'                      => 'درحال بررسی',
        'status-2'                      => 'در حال انجام',
        'status-3'                      => 'انجام شده',
        'status-4'                      => 'رد شده',
        'confirm'                       => '✅ انجام شد',
        'reject'                        => '❌ عدم تایید',
        'go_back_one_step'              => '➡️ مرحله قبل',
        'go_back_list'                  => '➡️ لیست محصولات',
        'GroupType'          	        => '👨‍👨‍👧‍👦 گروه',
        'channelType'                   => '📣 کانال',
        'go_back'                       => '➡️ بازگشت'
		];
		
		
		public function key_start()
		{
				return  '{
				"keyboard": [
				[
				"' . $this->buttons['cart'] . '",
				"' . $this->buttons['product'] . '"
				],
				[
				"' . $this->buttons['profile'] . '",
				"' . $this->buttons['peygiri'] . '"
				],
				[
				"' . $this->buttons['contact_us'] . '",
				"' . $this->buttons['help'] . '"
				]
				],
				"resize_keyboard" : true,
				"ForceReply":{
				"force_reply" : true 
				}
				}';
		}
		
		
		public function key_start_admin()
		{
				return  '{
				"keyboard": [
				[
				"' . $this->buttons['stats'] . '",
				"' . $this->buttons['ads'] . '"
				],
				[
				"' . $this->buttons['edit-product'] . '",
				"' . $this->buttons['add-product'] . '"
				],
				[
				"' . $this->buttons['delete-category'] . '",
				"' . $this->buttons['add-category'] . '"
				],
				[
				"' . $this->buttons['setting'] . '",
				"' . $this->buttons['buy-status'] . '"
				],
				[
				"' . $this->buttons['help'] . '",
				"' . $this->buttons['userList'] . '"
				],
				[
				"' . $this->buttons['go_back'] . '"
				]
				],
				"resize_keyboard" : true,
				"ForceReply":{
				"force_reply" : true
				}
				}';
		}
		
		
		public function key_confirm($userID,$orderID)
		{
			$keyboard = array(
			'inline_keyboard' => array(
			[
			['text'=>$this->buttons['reject'],'callback_data'=>"reject-".$userID."-".$orderID],
			['text'=>$this->buttons['confirm'],'callback_data'=>"confirm-".$userID."-".$orderID]
			]
			)
			);
			return  json_encode($keyboard);
		}
		
		
		public function key_status_change()
		{
			return  '{
			"keyboard": [
			[
			"' . $this->buttons['status-1'] . '",
			"' . $this->buttons['status-0'] . '"
			],
			[
			"' . $this->buttons['status-3'] . '",
			"' . $this->buttons['status-2'] . '"
			],
			[
			"' . $this->buttons['go_back'] . '",
			"' . $this->buttons['status-4'] . '"
			]
			],
			"resize_keyboard" : true,
			"ForceReply":{
			"force_reply" : true
			}
			}';
		}
		
		
		public function key_status()
		{ 
			return  '{
			"keyboard": [
			[
			"' . $this->buttons['cancel'] . '",
			"' . $this->buttons['ok'] . '"
			],
			[
			"' . $this->buttons['go_back'] . '"
			]
			],
			"resize_keyboard" : true,
			"ForceReply":{
			"force_reply" : true
			}
			}';
		}
		
		
		public function key_setting()
		{
			return  '{
			"keyboard": [
			[
			"' . $this->buttons['helpText'] . '",
			"' . $this->buttons['startText'] . '"
			],
			[
			"' . $this->buttons['go_back'] . '",
			"' . $this->buttons['contact_usText'] . '"
			]
			],
			"resize_keyboard" : true,
			"ForceReply":{
			"force_reply" : true
			}
			}';
		}
		
		
		public function key_getType()
		{
			return  '{
			"keyboard": [
			[
			"' . $this->buttons['channelType'] . '",
			"' . $this->buttons['GroupType'] . '"
			],
			[
			"' . $this->buttons['go_back'] . '"
			]
			],
			"resize_keyboard" : true,
			"ForceReply":{
			"force_reply" : true
			}
			}';
		}
		
		
		public function key_stats()
		{
			$keyboard = array(
			'inline_keyboard' => array(
			[
			['text'=>$this->buttons['refresh'],'callback_data'=>"stats-refresh"]
			]
			)
			);
			return  json_encode($keyboard);
		}
		
		
		public function key_cart()
		{
			$keyboard = array(
			'inline_keyboard' => array(
			[
			['text'=>$this->buttons['refresh'],'callback_data'=>"cart-refresh"]
			]
			)
			);
			return  json_encode($keyboard);
		}
		
		
		public function key_cart_buy()
		{
			$keyboard = array(
			'inline_keyboard' => array(
			[
			['text'=>$this->buttons['buy'],'callback_data'=>"cart-buy"],
			['text'=>$this->buttons['refresh'],'callback_data'=>"cart-refresh"]
			]
			)
			);
			return  json_encode($keyboard);
		}
		
		
		public function key_cart_continue()
		{
			$keyboard = array(
			'inline_keyboard' => array(
			[
			['text'=>$this->buttons['no'],'callback_data'=>"cart-no"],
			['text'=>$this->buttons['yes'],'callback_data'=>"cart-yes"]
			]
			)
			);
			return  json_encode($keyboard);
		}
		
		
		public function key_num($cart_id,$msg_id)
		{
			$keyboard = array(
			'inline_keyboard' => array(
			[
			['text'=>"🔢 تعداد مورد نیاز را انتخاب نمایید:",'callback_data'=>"num-0"]
			],
			[
			['text'=>"5",'callback_data'=>"num-5-".$cart_id."-".$msg_id],
			['text'=>"4",'callback_data'=>"num-4-".$cart_id."-".$msg_id],
			['text'=>"3",'callback_data'=>"num-3-".$cart_id."-".$msg_id],
			['text'=>"2",'callback_data'=>"num-2-".$cart_id."-".$msg_id],
			['text'=>"1",'callback_data'=>"num-1-".$cart_id."-".$msg_id]
			],
			[
			['text'=>"10",'callback_data'=>"num-10-".$cart_id."-".$msg_id],
			['text'=>"9",'callback_data'=>"num-9-".$cart_id."-".$msg_id],
			['text'=>"8",'callback_data'=>"num-8-".$cart_id."-".$msg_id],
			['text'=>"7",'callback_data'=>"num-7-".$cart_id."-".$msg_id],
			['text'=>"6",'callback_data'=>"num-6-".$cart_id."-".$msg_id],
			]
			)
			);
			return  json_encode($keyboard);
		}
		
		
		public function key_continue($msg_id)
		{
			$keyboard = array(
			'inline_keyboard' => array(
			[
			['text'=>$this->buttons['continueBuy'],'callback_data'=>"continueBuy".$msg_id],
			['text'=>$this->buttons['cart'],'callback_data'=>"endBuy"]
			]
			)
			);
			return  json_encode($keyboard);
		}
		
		
		public function key_profileAdd()
		{
			$keyboard = array(
			'inline_keyboard' => array(
			[
			['text'=>$this->buttons['profileAdd'],'callback_data'=>"profileAdd"]
			]
			)
			);
			return  json_encode($keyboard);
		}
		
		
		public function key_profileEdit()
		{
			$keyboard = array(
			'inline_keyboard' => array(
			[
			['text'=>$this->buttons['profileEdit'],'callback_data'=>"profileEdit"]
			]
			)
			);
			return  json_encode($keyboard);
		}
		
		
		public function go_back()
		{
			return  '{
			"keyboard": [
			[
			"' . $this->buttons['go_back'] . '"
			]
			],
			"resize_keyboard" : true,
			"ForceReply":{
			"force_reply" : true
			}
			}';
		}
		
		
		public function go_back_one_step()
		{
			return  '{
			"keyboard": [
			[
			"' . $this->buttons['go_back'] . '",
			"' . $this->buttons['go_back_one_step'] . '"
			]
			],
			"resize_keyboard" : true,
			"ForceReply":{
			"force_reply" : true
			}
			}';
		}
		
		
		public function key_profile()
		{
			return  '{
			"keyboard": [
			[
			"' . $this->buttons['history'] . '",
			"' . $this->buttons['mydetails'] . '"
			],
			[
			"' . $this->buttons['go_back'] . '"
			]
			],
			"resize_keyboard" : true,
			"ForceReply":{
			"force_reply" : true
			}
			}';
		}
		
		
		public function key_product_edit()
		{
			return  '{
			"keyboard": [
			[
			"' . $this->buttons['priceEdit'] . '",
			"' . $this->buttons['nameEdit'] . '",
			"' . $this->buttons['catEdit'] . '"
			],
			[
			"' . $this->buttons['descEdit'] . '",
			"' . $this->buttons['imageEdit'] . '",
			"' . $this->buttons['countEdit'] . '"
			],
			[
			"' . $this->buttons['go_back_list'] . '",
			"' . $this->buttons['delete-product'] . '"
			]
			],
			"resize_keyboard" : true,
			"ForceReply":{
			"force_reply" : true
			}
			}';
		}
		
		
		public function key_channel($ch_link)
		{
			$keyboard = array(
			'inline_keyboard' => array(
			[
			['text'=>$this->buttons['joinChannel'],'callback_data'=>"joinChannel"],
			['text'=>$this->buttons['channel'],'url'=>$ch_link]
			]
			)
			);
			return  json_encode($keyboard);
		}
		
		
		public function key_back_1($back)
		{
			$keyboard = array(
			'inline_keyboard' => array(
			[
			['text'=>"◀️ بازگشت",'callback_data'=>"back" . $back]
			]
			)
			);
			return  json_encode($keyboard);
		}
		
		
		public function key_back_2($id,$next)
		{
			$keyboard = array(
			'inline_keyboard' => array(
			[
			['text'=>"🛒 خرید",'callback_data'=>"buy" . $id],
			['text'=>"▶️ بعدی",'callback_data'=>"next" . $next]
			]
			)
			);
			return  json_encode($keyboard);
		}
		
		
		public function key_back_3($id,$back,$next)
		{
			$keyboard = array(
			'inline_keyboard' => array(
			[
			['text'=>"🛒 خرید",'callback_data'=>"buy" . $id],
			['text'=>"◀️ قبلی",'callback_data'=>"back" . $back],
			['text'=>"▶️ بعدی",'callback_data'=>"next" . $next]
			]
			)
			);
			return  json_encode($keyboard);
		}
		
		
		public function key_history_back_1($next)
		{
			$keyboard = array(
			'inline_keyboard' => array(
			[
			['text'=>"▶️ بعدی",'callback_data'=>"next" . $next]
			]
			)
			);
			return  json_encode($keyboard);
		}
		
		
		public function key_history_back_2($back,$next)
		{
			$keyboard = array(
			'inline_keyboard' => array(
			[
			['text'=>"◀️ قبلی",'callback_data'=>"back" . $back],
			['text'=>"▶️ بعدی",'callback_data'=>"next" . $next]
			]
			)
			);
			return  json_encode($keyboard);
		}
		
		
		public function key_status_2($id,$next)
		{
			$keyboard = array(
			'inline_keyboard' => array(
			[
			['text'=>"🔁 تغییروضعیت",'callback_data'=>"status" . $id],
			['text'=>"▶️ بعدی",'callback_data'=>"next" . $next]
			]
			)
			);
			return  json_encode($keyboard);
		}
		
		
		public function key_status_3($id,$back,$next)
		{
			$keyboard = array(
			'inline_keyboard' => array(
			[
			['text'=>"🔁 تغییروضعیت",'callback_data'=>"status" . $id],
			['text'=>"◀️ قبلی",'callback_data'=>"back" . $back],
			['text'=>"▶️ بعدی",'callback_data'=>"next" . $next]
			]
			)
			);
			return  json_encode($keyboard);
		}
		
		
		public function key_stop()
		{ 
			return  '{
			"keyboard": [
			[
			"' . $this->buttons['startAgain'] . '"
			]
			],
			"resize_keyboard" : true,
			"ForceReply":{
			"force_reply" : true
			}
			}';
		}
	}		