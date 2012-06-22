<?php

class Ajde_Http_Response extends Ajde_Object_Standard
{
	const REDIRECT_HOMEPAGE = 1;
	const REDIRECT_REFFERER = 2;

	const RESPONSE_TYPE_NOT_MODIFIED = 304;
	
	public static function redirectNotFound()
	{
		self::dieOnCode("404");
	}

	public static function redirectServerError()
	{
		self::dieOnCode("500");
	}

	public static function dieOnCode($code)
	{
		self::setResponseType($code);
		header("Content-type: text/html; charset=UTF-8");
		$_SERVER['REDIRECT_STATUS'] = $code;
		include("errordocument.php");
		die();
	}

	public static function setResponseType($code)
	{
		header("HTTP/1.0 ".$code." ".self::getResponseType($code));
		ob_get_clean();
		header("Status: $code");
	}

	protected static function getResponseType($code)
	{
		switch ($code)
		{
			case 304: return "Not Modified";
			case 400: return "Bad Request";
			case 401: return "Unauthorized";
			case 403: return "Forbidden";
			case 404: return "Not Found";
			case 500: return "Internal Server Error";
			case 501: return "Not Implemented";
			case 502: return "Bad Gateway";
			case 503: return "Service Unavailable";
			case 504: return "Bad Timeout";
		}
	}

	function setRedirect($url = null)
	{
		if ($url === true || $url === self::REDIRECT_HOMEPAGE) {
			$this->addHeader("Location", 'http://' . Config::get('site_root'));
		} elseif ($url === self::REDIRECT_REFFERER) {
			$this->addHeader("Location", Ajde_Http_Request::getRefferer());
		} elseif (substr($url, 0, 7) == "http://") {
			$this->addHeader("Location", $url);
		} elseif ($url) {
			$this->addHeader("Location", 'http://' . Config::get('site_root') . $url);
		} else {
			$self = $_SERVER["PHP_SELF"].($_SERVER["QUERY_STRING"] ? "?" : "").$_SERVER["QUERY_STRING"];
			$this->addHeader("Location", 'http://' . $self);
		}
	}

	function addHeader($name, $value)
	{
		$headers = array();
		if ($this->has('headers'))
		{
			$headers = $this->get('headers');
		}
		$headers[$name] = $value;
		$this->set("headers", $headers);
	}

	function setData($data)
	{
		$this->set("data", $data);
	}

	function send()
	{
		if ($this->has("headers"))
		{
			foreach($this->get("headers") as $name => $value)
			{
				header("$name: $value");
			}
		}

		if (!array_key_exists('Location', $this->get("headers"))) {
			echo $this->getData();
		}
	}

}