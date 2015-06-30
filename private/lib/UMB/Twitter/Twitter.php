<?php
require 'twitteroauth.php';

class Umb_Twitter
{
	private $_consumerKey;
	private $_consumerSecret;
	private $_token;
	private $_tokenSecret;
	
	private $_twitter;
	
	public function __construct($options = null, Zend_Oauth_Consumer $consumer = null)
	{
		$this->_consumerKey = Config::get('twitterConsumerKey');
		$this->_consumerSecret = Config::get('twitterConsumerSecret');
		$this->_token = Config::get('twitterToken');
		$this->_tokenSecret = Config::get('twitterTokenSecret');
		
		$this->_twitter =  new TwitterOAuth($this->_consumerKey, $this->_consumerSecret, $this->_token, $this->_tokenSecret);
	}
	
	public function statusUpdate($url, $title)
	{
		if (strtolower(substr($url, 0, 7)) !== 'http://') {
			$url = 'http://' . $url;
		}
		$title = trim($title);
//		$status = Ajde_Component_String::trim($title, 140 - strlen($url) - 1) . ' ' .  $url;
		$status = substr($title, 0, 140 - strlen($url) - 5) . ' ' . $url;
//		Ajde_Log::log("Send request to twitter API with STATUS=$status");
		
		while ($curlength = iconv_strlen(htmlspecialchars($status, ENT_QUOTES, 'UTF-8'), 'UTF-8') >= 140) {
			$status = substr($status, 0, -1);
		}
		
		$ret = false;
		try {
			$ret =  $this->_twitter->post('statuses/update', array('status' => $status));
		} catch (Exception $e) {
			Ajde_Exception_Log::logException($e);
		}
		return $ret;
	}

	public function getLastTweet($username)
	{
		$ret = false;
		try {
			$ret =  $this->_twitter->get('statuses/update', array('status' => $status));
		} catch (Exception $e) {
			Ajde_Exception_Log::logException($e);
		}
		return $ret;
	}
	
}