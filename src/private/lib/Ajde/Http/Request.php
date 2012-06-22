<?php

class Ajde_Http_Request extends Ajde_Object_Standard
{
	/**
	 * @var Ajde_Core_Route
	 */
	protected $_route = null;
	
	/**
	 * @return Ajde_Http_Request
	 */
	public static function fromGlobal()
	{
		$instance = new self();
		$global = array_merge($_GET, $_POST);
		foreach($global as $key => $value)
		{
			$instance->set($key, $value);
		}
		return $instance;
	}

	public static function getRefferer()
	{
		return $_SERVER['HTTP_REFERER'];
	}

	public function getParam($key, $default = null) {
		return $this->has($key) ? $this->get($key) : $default;
	}
	
	/**
	 * @return Ajde_Core_Route
	 */
	public function getRoute()
	{
		if (!isset($this->_route))
		{
			$routeKey = '_route';			
			if (!$this->has($routeKey)) {
				$this->set($routeKey, false);
			}
			$this->_route = new Ajde_Core_Route($this->get($routeKey));
			foreach ($this->_route->values() as $part => $value) {
				$this->set($part, $value);
			}
		}
		return $this->_route;
	}


}