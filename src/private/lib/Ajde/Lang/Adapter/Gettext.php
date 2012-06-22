<?php

class Ajde_Lang_Adapter_Gettext extends Ajde_Lang_Adapter_Abstract
{
	public function __construct()
	{
		$lang = Ajde_Lang::getInstance()->getLang();
		setlocale(LC_ALL, $lang);
		bindtextdomain(Config::get('ident'), rtrim(LANG_DIR, DIRECTORY_SEPARATOR));
		textdomain(Config::get('ident'));
		//bind_textdomain_codeset(Config::get('ident'), 'utf8');
	}
	
	public function get($ident, $module = null)
	{
		return gettext($ident);
	}
}