<?php

class TG
	{
	private $telegram;

	function __construct($token)
		{
		$this->telegram = new Telegram($token);
		}

	function getMess()
		{
		return $this->telegram->Text();
		}

	function getChatId()
		{
		return $this->telegram->ChatID();
		}

	function getUsername()
		{
		return $this->telegram->Username();
		}

	function getChanChatId($chan)
		{
		$content = array('chat_id' => $chan);
		$ret = $this->telegram->getChat($content);
		if (@$ret['ok'])
			{
			return $ret['result']['id'];
			}
		else
			{
			return false;
			}
		}

	function sendMess($chat_id,$mess,$html=false)
		{
		$content = array('chat_id' => $chat_id, 'text' => $mess);
		if ($html)
			{
			$content['parse_mode'] = 'html';
			$content['disable_web_page_preview'] = false;
			}

		$ret = $this->telegram->sendMessage($content);
		return $ret;
		}

	}