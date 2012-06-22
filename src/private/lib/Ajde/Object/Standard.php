<?php

abstract class Ajde_Object_Standard extends Ajde_Object_Magic
{
	protected static $__pattern = self::OBJECT_PATTERN_STANDARD;

	public static function __getPattern()
	{
		return self::$__pattern;
	}
}