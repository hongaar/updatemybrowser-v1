<?php

abstract class Ajde_Resource extends Ajde_Object_Standard
{

	const TYPE_JAVASCRIPT	= 'js';
	const TYPE_STYLESHEET	= 'css';
	
	public function  __construct($type)
	{
		$this->setType($type);
	}
	
	public function __toString()
	{
		return implode(", ", $this->_data);
	}
	
	abstract public function getFilename();
	abstract protected function getLinkUrl();

	public function getType() {
		return $this->get('type');
	}

	protected function _getLinkTemplateFilename()
	{
		$format = $this->hasFormat() ? $this->getFormat() : null;
		return self::getLinkTemplateFilename($this->getType(), $format);
	}

	public static function getLinkTemplateFilename($type, $format = 'null')
	{
		$layout = Ajde::app()->getDocument()->getLayout();
		$format = isset($format) ? $format : 'html';
		return LAYOUT_DIR . $layout->getName() . '/link/' . $type . '.' . $format . '.php';
	}

	public function getLinkCode()
	{
		ob_start();

		// variables for use in included link template
		$url = $this->getLinkUrl();

		// create temporary resource for link filename
		$linkFilename = $this->_getLinkTemplateFilename();

		// TODO: performance gain?
		// Ajde_Cache::getInstance()->addFile($linkFilename);
		include $linkFilename;

		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	
	public function getContents() {
		ob_start();

		Ajde_Cache::getInstance()->addFile($this->getFilename());
		include $this->getFilename();
		
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
}