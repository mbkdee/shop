<?php
	
	class Telegram 
	{
		private $bot_id = "";
		private $data = array();
		private $updates = array();
		
		public function __construct($bot_id) 
		{
			$this->bot_id = $bot_id;
			$this->data = $this->getData();
		}
		
		public function endpoint($api, array $content, $post = true) 
		{
			$url = 'https://api.telegram.org/bot' . $this->bot_id . '/' . $api;
			if ($post)
            $reply = $this->sendAPIRequest($url, $content);
			else
            $reply = $this->sendAPIRequest($url, array(), false);
			return json_decode($reply, true);
		}
		
		public function getMe() 
		{
			return $this->endpoint("getMe", array(), false);
		}
		
		public function sendMessage(array $content) 
		{
			return $this->endpoint("sendMessage", $content);
		}
		
		public function editMessageText(array $content) 
		{
			return $this->endpoint("editMessageText", $content);
			}
		
		public function answerCallbackQuery(array $content) 
		{
			return $this->endpoint("answerCallbackQuery", $content);
		}
		
		public function forwardMessage(array $content)
		{
			return $this->endpoint("forwardMessage", $content);
		}
		
		public function sendPhoto(array $content) 
		{
			return $this->endpoint("sendPhoto", $content);
		}
		
		public function sendAudio(array $content) 
		{
			return $this->endpoint("sendAudio", $content);
		}
		
		public function sendDocument(array $content) 
		{
			return $this->endpoint("sendDocument", $content);
		}
		
		public function sendSticker(array $content)
		{
			return $this->endpoint("sendSticker", $content);
		}
		
		public function sendVideo(array $content)
		{
			return $this->endpoint("sendVideo", $content);
		}
		
		public function sendContact(array $content)
		{
			return $this->endpoint("sendContact", $content);
		}
		
		public function sendVoice(array $content)
		{
			return $this->endpoint("sendVoice", $content);
		}
		
		public function sendLocation(array $content) 
		{
			return $this->endpoint("sendLocation", $content);
		}
		
		public function deleteMessage(array $content) 
		{
			return $this->endpoint("deleteMessage", $content);
		}
		
		public function editMessageReplyMarkup(array $content) 
		{
			return $this->endpoint("editMessageReplyMarkup", $content);
		}
		
		public function sendChatAction(array $content) 
		{
			return $this->endpoint("sendChatAction", $content);
		}
		
		public function getUserProfilePhotos(array $content) 
		{
			return $this->endpoint("getUserProfilePhotos", $content);
		}
		
		public function getFile($file_id)
		{
			$content = array('file_id' => $file_id);
			return $this->endpoint("getFile", $content);
		}
		
		public function downloadFile($telegram_file_path, $local_file_path) 
		{
			$file_url = "https://api.telegram.org/file/bot" . $this->bot_id . "/" . $telegram_file_path;
			$in = fopen($file_url, "rb");
			$out = fopen($local_file_path, "wb");
			while ($chunk = fread($in, 8192))
			{
				fwrite($out, $chunk, 8192);
			}
			fclose($in);
			fclose($out);
		}
		
		public function setWebhook($url, $certificate = "") 
		{
			if ($certificate == "")
			{
				$content = array('url' => $url);
			} 
			else 
			{
				$content = array('url' => $url, 'certificate' => $certificate);
			}
			return $this->endpoint("setWebhook", $content);
		}
		
		public function getData()
		{
			if (empty($this->data)) 
			{
				$rawData = file_get_contents("php://input");
				return json_decode($rawData, true);
			}
			else
			{
				return $this->data;
			}
		}
		
		public function setData(array $data)
		{
			$this->data = $data;
		}
		
		public function Text() 
		{
			return $this->data["message"]["text"];
		}
		
		public function ChatID() 
		{
			return $this->data["message"]["chat"]["id"];
		}
		
		public function Callback_Query()
		{
			return $this->data["callback_query"];
		}
		
		public function Callback_ID()
		{
			return $this->data["callback_query"]["id"];
		}
		
		public function Callback_Data()
		{
			return $this->data["callback_query"]["data"];
		}
		
		public function Callback_Message() 
		{
			return $this->data["callback_query"]["message"];
		}
		
		public function Callback_ChatID() 
		{
			return $this->data["callback_query"]["message"]["chat"]["id"];
		}
		
		public function Date()
		{
			return $this->data["message"]["date"];
		}
		
		public function FirstName() 
		{
			return $this->data["message"]["from"]["first_name"];
		}
		
		public function LastName() 
		{
			return $this->data["message"]["from"]["last_name"];
		}
		
		public function Username()
		{
			return $this->data["message"]["from"]["username"];
		}
		
		public function Location() 
		{
			return $this->data["message"]["location"];
		}
		
		public function UpdateID() 
		{
			return $this->data["update_id"];
		}
		
		public function UpdateCount() 
		{
			return count($this->updates["result"]);
		}
		
		public function messageFromGroup() 
		{
			if ($this->data["message"]["chat"]["title"] == "") 
			{
				return false;
			}
			return true;
		}
		
		public function buildKeyBoard(array $options, $onetime = false, $resize = false, $selective = true)
		{
			$replyMarkup = array(
            'keyboard' => $options,
            'one_time_keyboard' => $onetime,
            'resize_keyboard' => $resize,
            'selective' => $selective
			);
			$encodedMarkup = json_encode($replyMarkup, true);
			return $encodedMarkup;
		}
		
		public function buildInlineKeyBoard(array $options) 
		{
			$replyMarkup = array(
            'inline_keyboard' => $options,
			);
			$encodedMarkup = json_encode($replyMarkup, true);
			return $encodedMarkup;
		}
		
		public function buildInlineKeyboardButton($text, $url = "", $callback_data = "", $switch_inline_query = "") 
		{
			$replyMarkup = array(
            'text' => $text
			);
			if ($url != "") 
			{
				$replyMarkup['url'] = $url;
			} 
			else if ($callback_data != "") 
			{
				$replyMarkup['callback_data'] = $callback_data;
			} 
			else if ($switch_inline_query != "") 
			{
				$replyMarkup['switch_inline_query'] = $switch_inline_query;
			}
			return $replyMarkup;
		}
		
		public function buildKeyboardButton($text, $request_contact = false, $request_location = false)
		{
			$replyMarkup = array(
            'text' => $text,
            'request_contact' => $request_contact,
            'request_location' => $request_location
			);
			if ($url != "")
			{
				$replyMarkup['url'] = $url;
			} 
			else if ($callback_data != "") 
			{
				$replyMarkup['callback_data'] = $callback_data;
			} 
			else if ($switch_inline_query != "") 
			{
				$replyMarkup['switch_inline_query'] = $switch_inline_query;
			}
			return $replyMarkup;
		}
		
		public function buildKeyBoardHide($selective = true)
		{
			$replyMarkup = array(
            'hide_keyboard' => true,
            'selective' => $selective
			);
			$encodedMarkup = json_encode($replyMarkup, true);
			return $encodedMarkup;
		}
		
		public function buildForceReply($selective = true) 
		{
			$replyMarkup = array(
            'force_reply' => true,
            'selective' => $selective
			);
			$encodedMarkup = json_encode($replyMarkup, true);
			return $encodedMarkup;
		}
		
		public function getUpdates($offset = 0, $limit = 100, $timeout = 0, $update = true) 
		{
			$content = array('offset' => $offset, 'limit' => $limit, 'timeout' => $timeout);
			$this->updates = $this->endpoint("getUpdates", $content);
			if ($update)
			{
				if(count($this->updates["result"]) >= 1) 
				{ //for CLI working.
					$last_element_id = $this->updates["result"][count($this->updates["result"]) - 1]["update_id"] + 1;
					$content = array('offset' => $last_element_id, 'limit' => "1", 'timeout' => $timeout);
					$this->endpoint("getUpdates", $content);
				}
			}
			return $this->updates;
		}
		
		public function serveUpdate($update)
		{
			$this->data = $this->updates["result"][$update];
		}
		
		private function sendAPIRequest($url, array $content, $post = true) 
		{
			if (isset($content['chat_id'])) 
			{
				$url = $url . "?chat_id=" . $content['chat_id'];
				unset($content['chat_id']);
			}
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			if ($post) 
			{
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
			}
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
		}
	}
	
	if (!function_exists('curl_file_create'))
	{
		function curl_file_create($filename, $mimetype = '', $postname = '') 
		{
			return "@$filename;filename="
			. ($postname ? : basename($filename))
			. ($mimetype ? ";type=$mimetype" : '');
		}
	}
	
?>						