<?php

class Ajde_Session extends Ajde_Object_Standard
{
	protected $_namespace = null;
	
	public function __bootstrap()
	{
		session_cache_limiter('private_no_expire');
		session_start();
		// remove cache headers invoked by session_start();
		if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
			header_remove('X-Powered-By');
		}
		return true;
	}
	
	public function __construct($namespace = 'default')
	{
		$this->_namespace = $namespace;
	}
	
	public function destroy()
	{
		$_SESSION[$this->_namespace] = null;
		$this->reset(); 
	}
	
	public function setModel($name, $object)
	{
		$this->set($name, serialize($object));	
	}
	
	public function getModel($name)
	{
		return unserialize($this->get($name));
	}
	
	public function has($key)
	{
		if (!isset($this->_data[$key]) && isset($_SESSION[$this->_namespace][$key])) {
			$this->set($key, $_SESSION[$this->_namespace][$key]);
		}
		return parent::has($key);
	}
	
	public function set($key, $value)
	{
		parent::set($key, $value);
		if ($value instanceof AjdeX_Model) {
			// TODO:
			throw new Ajde_Exception('It is not allowed to store a Model directly in the session, use Ajde_Session::setModel() instead.');
		}
		$_SESSION[$this->_namespace][$key] = $value;
	}
}