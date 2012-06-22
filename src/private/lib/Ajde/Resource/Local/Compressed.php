<?php

class Ajde_Resource_Local_Compressed extends Ajde_Resource
{
	public function  __construct($type, $filename)
	{
		$this->setFilename($filename);
		parent::__construct($type);		
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

	public function getLinkUrl()
	{
		
		$hash = md5(serialize($this));
		$session = new Ajde_Session('_ajde');
		$session->set($hash, $this);
		
		$url = '_core/component:resourceCompressed/' . $this->getType() . '/' . $hash . '/';
		
		if (Config::get('debug') === true)
		{
			$url .= '?file=' . str_replace('%2F', ':', urlencode($this->getFilename()));
		}
		return $url;
	}

	public function getFilename() {
		return $this->get('filename');
	}
}