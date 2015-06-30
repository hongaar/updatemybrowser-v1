<?php
class Umb_Twitter extends Zend_Service_Twitter
{
	private $_token;
	private $_tokenSecret;
	
	public function __construct($options = null, Zend_Oauth_Consumer $consumer = null)
	{
		$this->_token = Config::get('twitterToken');
		$this->_tokenSecret = Config::get('twitterTokenSecret');
		
		$options['username'] = Config::get('twitterUser');
		
		$token = new Zend_Oauth_Token_Access();
		$token->setToken($this->_token);
		$token->setTokenSecret($this->_tokenSecret);		

		$options['accessToken'] = $token;
		
		return parent::__construct($options, $consumer);
	}
	
	public function statusUpdate($url, $title)
	{
		if (strtolower(substr($url, 0, 7)) !== 'http://') {
			$url = 'http://' . $url;
		}
		$status = Ajde_Component_String::trim($title, 140 - strlen($url) - 1) . ' ' .  $url;
		$ret = false;
		try {
			$ret = parent::statusUpdate($status);
		} catch (Exception $e) {
			Ajde_Exception_Log::logException($e);
		}
		return $ret;
	}
	
}