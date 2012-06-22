<?php

class Ajde_Document_Format_Processor_Html_Beautifier extends Ajde_Object_Static implements Ajde_Document_Format_Processor
{
	public static function preProcess(Ajde_Layout $layout) {}
		
	public static function postProcess(Ajde_Layout $layout)
	{
		$layout->setContents(self::beautifyHtml($layout->getContents()));
	}

	public static function beautifyHtml($html,
		// @see http://tidy.sourceforge.net/docs/quickref.html
		$config = array(
			"output-xhtml" 	=> true,
			"char-encoding"	=> "utf8",
			"indent" 		=> true,
			"indent-spaces"	=> 4,
			"wrap"			=> 0
		)
	)
	{
		if (!Ajde_Core_Autoloader::exists('Tidy')) {
			throw new Ajde_Exception('Class Tidy not found', 90023);
		}
		$tidy = new Tidy();
		// http://bugs.php.net/bug.php?id=35647
		return $tidy->repairString($html, $config, 'utf8');
	}
	
}