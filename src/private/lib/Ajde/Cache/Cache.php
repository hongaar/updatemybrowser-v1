<?php

class Ajde_Cache extends Ajde_Object_Singleton
{
	protected $_hashContext;
	protected $_hashFinal;
	protected $_lastModified = array();
	protected $_contents;

	/**
	 *
	 * @staticvar Ajde_Cache $instance
	 * @return Ajde_Cache
	 */
	public static function getInstance()
	{
		static $instance;
		return $instance === null ? $instance = new self : $instance;
	}

	public function getHashContext() {
		if (!isset($this->_hashContext))
		{
			$this->_hashContext = hash_init("md5");
		}
		return $this->_hashContext;
	}

	public function updateHash($data)
	{
		if (!isset($this->_hashFinal))
		{
			hash_update($this->getHashContext(), $data);
		}
	}

	public function addFile($filename)
	{
		if (!isset($this->_hashFinal))
		{
			if (file_exists($filename)) {
				hash_update_file($this->getHashContext(), $filename);
				$this->addLastModified(filemtime($filename));
			}
		}
	}

	public function getHash()
	{
		if (!isset($this->_hashFinal))
		{
			$this->_hashFinal = hash_final($this->getHashContext());
		}
		return $this->_hashFinal;
	}

	public function addLastModified($timestamp)
	{
		$this->_lastModified[] = $timestamp;
	}

	public function getLastModified()
	{
		return max($this->_lastModified);
	}

	public function ETagMatch($serverETag = null)
	{
		if (empty($serverETag) && isset($_SERVER['HTTP_IF_NONE_MATCH']))
		{
			$serverETag = $_SERVER['HTTP_IF_NONE_MATCH'];
		}
		return $serverETag == $this->getHash();
	}

	public function setContents($contents)
	{
		$this->set('contents', $contents);
	}

	public function getContents()
	{
		return $this->get('contents');
	}

	public function saveResponse()
	{
		$response = Ajde::app()->getResponse();
		if ($this->ETagMatch() && Config::get('useCache')) {			
			$response->setResponseType(Ajde_Http_Response::RESPONSE_TYPE_NOT_MODIFIED);
			$response->addHeader('Content-Length', '0');
			$response->setData(false);
		} else {
			$response->addHeader('Last-Modified', gmdate('D, d M Y H:i:s', $this->getLastModified()) . ' GMT');
			$response->addHeader('Etag', $this->getHash());
			$response->addHeader('Cache-Control', Ajde::app()->getDocument()->getCacheControl());
			$response->setData($this->hasContents() ? $this->getContents() : false);
		}
	}

}