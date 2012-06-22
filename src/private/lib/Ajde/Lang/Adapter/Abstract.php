<?php

abstract class Ajde_Lang_Adapter_Abstract
{	
	public static function _($ident, $module = null)
	{
		return self::getInstance()->get($ident, $module);
	}
	
	abstract public function get($ident, $module = null);
}