<?php

class Ajde_Resource_Local extends Ajde_Resource
{
	public function  __construct($type, $base, $action, $format = 'html')
	{
		$this->setBase($base);
		$this->setAction($action);
		$this->setFormat($format);
		parent::__construct($type);
	}

	/**
	 *
	 * @param string $type
	 * @param string $base
	 * @param string $action
	 * @param string $format (optional)
	 * @return Ajde_Resource
	 */
	public static function lazyCreate($type, $base, $action, $format = 'html')
	{
		$filename = self::getFilenameFromStatic($base, $type, $action);
		if (self::exist($filename))
		{
			return new self($type, $base, $action, $format);
		}
		return false;
	}

	/**
	 *
	 * @param string $hash
	 * @return Ajde_Resource
	 */
	public static function fromHash($hash)
	{
		$session = new Ajde_Session('_ajde');
		return $session->get($hash);
	}

	public function getBase() {
		return $this->get('base');
	}

	public function getAction() {
		return $this->get('action');
	}

	public function getFormat() {
		return $this->get('format');
	}

	protected static function exist($filename)
	{
		if (file_exists($filename)) {
			return true;
		}
		return false;

	}

	protected static function _getFilename($base, $type, $action)
	{
		return $base . 'res/' . $type . '/' . $action . '.' . $type;
	}

	public function getFilename()
	{
		return $this->_getFilename($this->getBase(), $this->getType(), $this->getAction());
	}

	public static function getFilenameFromStatic($base, $type, $action)
	{
		return self::_getFilename($base, $type, $action);
	}

	protected function getLinkUrl()
	{
		$hash = md5(serialize($this));
		$session = new Ajde_Session('_ajde');
		$session->set($hash, $this);
		
		$url = '_core/component:resourceLocal/' . $this->getType() . '/' . $hash . '/';

		if (Config::get('debug') === true)
		{
			$url .= '?file=' . str_replace('%2F', ':', urlencode($this->getFilename()));
		}
		return $url;
	}
}