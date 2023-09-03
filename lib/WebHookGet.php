<?php

class webHookGet
{
    public $chat_id = null;
    public $text = null;
	public $audio = null;
	public $audio_file_id = null;
	public $document = null;
	public $document_file_id = null;
	public $photo = null;
	public $sticker = null;
	public $sticker_file_id = null;
	public $video = null;
	public $video_file_id = null;
	public $voice = null;
	public $voice_file_id  = null;
	public $contact = null; 
	public $contact_phone_number = null; 
	public $contact_first_name = null; 
	public $contact_last_name = null; 
	public $location = null; 
	public $caption = null; 
    public $rpto = null;
    public $first_name = null;
    public $last_name = null;
    public $username = null;
    public $message_id = null;
    public $user_id = null;
    public $latitude = null;
    public $longitude = null;
    public $raw_message = null;
    public $phone_number = null;
    public $callback_query = false;
    public $callback_query_id = null;
	
    public function __construct($telegram)
    {
        $result = $telegram->getData();
        $this->raw_message = $result;
       
        if ( isset( $result['callback_query'] ) ) 
		{ 
            $this->callback_query = true;
            $this->chat_id = $result['callback_query']["message"]["chat"]["id"];
            $this->text = $result['callback_query']["data"]; // Data instead of text in callback query
            $this->callback_query_id = $result['callback_query']["id"];
            $this->user_id = $result['callback_query']["from"]["id"];
            $this->first_name = isset( $result['callback_query']["from"]["first_name"] ) ? $result['callback_query']["message"]["from"]["first_name"] : null;
            $this->last_name = isset( $result['callback_query']["from"]["last_name"] ) ? $result['callback_query']["message"]["from"]["last_name"] : null;
            $this->username = isset( $result['callback_query']["from"]["username"] ) ? $result['callback_query']["message"]["from"]["username"] : null;
            $this->message_id = $result['callback_query']["message"]["message_id"];
            $this->latitude = isset( $result['callback_query']["message"]["location"]["latitude"] ) ? $result['callback_query']["message"]["location"]["latitude"] : null;
            $this->phone_number = isset( $result['callback_query']["message"]["contact"]["phone_number"] ) ? $result['callback_query']["message"]["contact"]["phone_number"] : null;
            $this->longitude = isset( $result['callback_query']["message"]["location"]["longitude"] ) ? $result['callback_query']["message"]["location"]["longitude"] : null;
        } 
		else 
		{
            $this->chat_id = $result["message"]["chat"]["id"];
            $this->text = $result["message"]["text"];
            $this->audio = $result["message"]["audio"];
            $this->audio_file_id = $result["message"]["audio"]["file_id"];
            $this->document = $result["message"]["document"];
            $this->document_file_id = $result["message"]["document"]["file_id"];
            $this->photo = $result["message"]["photo"];
            $this->sticker = $result["message"]["sticker"];
            $this->sticker_file_id = $result["message"]["sticker"]["file_id"];
            $this->video = $result["message"]["video"];
            $this->video_file_id = $result["message"]["video"]["file_id"];
            $this->voice = $result["message"]["voice"];
            $this->voice_file_id  = $result["message"]["voice"]["file_id"];
            $this->contact = $result["message"]["contact"];
            $this->contact_phone_number = $result["message"]["contact"]["phone_number"];
            $this->contact_first_name = $result["message"]["contact"]["first_name"];
            $this->contact_last_name = $result["message"]["contact"]["last_name"];
            $this->location = $result["message"]["location"];
            $this->caption = $result["message"]["caption"];
            $this->user_id = $result["message"]["from"]["id"];
            $this->first_name = isset( $result["message"]["from"]["first_name"] ) ? $result["message"]["from"]["first_name"] : null;
            $this->last_name = isset( $result["message"]["from"]["last_name"] ) ? $result["message"]["from"]["last_name"] : null;
            $this->rpto = isset( $result['message']['reply_to_message']['forward_from']['id'] ) ? $result['message']['reply_to_message']['forward_from']['id'] : null;
            $this->username = isset( $result["message"]["from"]["username"] ) ? $result["message"]["from"]["username"] : null;
            $this->message_id = $result["message"]["message_id"];
            $this->latitude = isset( $result["message"]["location"]["latitude"] ) ? $result["message"]["location"]["latitude"] : null;
            $this->phone_number = isset( $result["message"]["contact"]["phone_number"] ) ? $result["message"]["contact"]["phone_number"] : null;
            $this->longitude = isset( $result["message"]["location"]["longitude"] ) ? $result["message"]["location"]["longitude"] : null;
        }
    }
}