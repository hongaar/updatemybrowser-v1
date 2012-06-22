<?php

class Config {

	// Redirect this class to the following config stage
	// Defaults are 'live' and 'dev'
	public static $stage			= "dev";

	/**
	 * 
	 * @return Config_Base
	 */
	public static function getInstance($stage = null) {
		$stage = self::_getStage($stage);
		static $instance = array();
		if (!isset($instance[$stage])) {
			$className = "Config_".ucfirst($stage);
			if (class_exists($className))
			{
				$instance[$stage] = new $className();
			}
			else
			{
				throw new Ajde_Core_Autoloader_Exception("Unable to load $className", 90005);
			}
			
		}
		return $instance[$stage];
	}

	/**
	 * 
	 * @param string $param
	 * @return mixed
	 */
	public static function get($param, $stage = null) {
		$stage = self::_getStage($stage);
		$instance = self::getInstance($stage);
		if (isset($instance->$param)) {
			return $instance->$param;
		} else {
			throw new Ajde_Exception("Config parameter $param not set", 90004);
		}
	}

	private static function _getStage($stage = null) {
		return empty($stage) ? self::$stage : $stage;
	}

}