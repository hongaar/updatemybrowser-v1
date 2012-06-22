<?php

class Ajde_Cookie extends Ajde_Object_Standard
{
	protected $_namespace = null;
	protected $_lifetime = 90;
		
	public function __construct($namespace = 'default')
	{
		$this->_namespace = $namespace;
		if (isset($_COOKIE[$this->_namespace])) {
			$this->_data = unserialize($_COOKIE[$this->_namespace]);
		}
	}
	
	public function destroy()
	{
		setcookie($this->_namespace, '', time() - 3600, '/');
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
	
	public function setLifetime($days)
	{
		$this->_lifetime = $days;
	}
		
	public function set($key, $value)
	{
		parent::set($key, $value);
		if ($value instanceof AjdeX_Model) {
			// TODO:
			throw new Ajde_Exception('It is not allowed to store a Model directly in a cookie, use Ajde_Cookie::setModel() instead.');
		}
		// store for 90 days
		setcookie($this->_namespace, serialize($this->values()), time() + (60 * 60 * 24 * $this->_lifetime), '/');
	}
}