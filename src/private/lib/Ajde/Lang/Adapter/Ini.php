<?php

class Ajde_Lang_Adapter_Ini extends Ajde_Lang_Adapter_Abstract
{
	public function get($ident, $module = null)
	{
		if (!$module) {	
			foreach(debug_backtrace() as $item) {			
				if (!empty($item['class'])) {
					if (is_subclass_of($item['class'], "Ajde_Controller")) {
						$module = strtolower(str_replace("Controller", "", $item['class']));
						break;
					}
				}
			}
		}
		
		if (!$module) {
			$module = 'main';
		}
		$lang = Ajde_Lang::getInstance()->getLang();
		$iniFilename = LANG_DIR . $lang . '/' . $module . '.ini';
		if (file_exists($iniFilename)) {
			$book = parse_ini_file($iniFilename);
			if (array_key_exists($ident, $book)) {
				return $book[$ident];
			}
		}
		return $ident;
	}
}