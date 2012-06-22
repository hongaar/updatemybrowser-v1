<?php

abstract class Ajde_Object_Magic extends Ajde_Object
{
	protected $_data = array();
	
	public final function __call($method, $arguments)
	{
		$prefix = strtolower(substr($method, 0, 3));
		$key = substr($method, 3);
		$key = strtolower(substr($key, 0, 1)).substr($key, 1);
		switch ($prefix)
		{
			case "get":
				if ($this->has($key))
				{
					return $this->get($key);
				}
				else
				{					
					if (!method_exists($this, '__fallback')) {
						throw new Ajde_Exception("Property '$key' not set in class ".get_class($this)." when calling get('$key')", 90007);
					}					
				}
				break;
			case "set":
				return $this->set($key, $arguments[0]);
				break;
			case "has":
				return $this->has($key);
				break;
		}
		if (method_exists($this, '__fallback')) {
			return call_user_func_array(array($this, '__fallback'), array($method, $arguments));
		}
		throw new Ajde_Exception("Call to undefined method ".get_class($this)."::$method()", 90006);
    }

	/**
	 *
	 * @param mixed $name
	 * @param mixed $value
	 * @return mixed
	 */
	public function set($key, $value)
	{
		$this->_data[$key] = $value;
	}

	public function get($key)
	{
		if ($this->has($key))
		{
			return $this->_data[$key];
		}
		else
		{
			throw new Ajde_Exception("Parameter '$key' not set in class ".get_class($this)." when calling get('$key')", 90007);
		}		
	}

	public function has($key)
	{
		return array_key_exists($key, $this->_data);
	}
	
	public function reset()
	{
		$this->_data = array();
	}
	
	public final function values()
	{
		return $this->_data;
	}
}