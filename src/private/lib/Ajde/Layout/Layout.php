<?php

class Ajde_Layout extends Ajde_Template
{
	public function __construct($name, $style = 'default', $format = 'html')
	{
		$this->setName($name);
		$this->setStyle($style);

		$base = LAYOUT_DIR.$this->getName() . '/';
		$action = $this->getStyle();
		$format = $format;
		parent::__construct($base, $action, $format);
	}

	public function setName($name)
	{
		$this->set("name", $name);
	}

	public function setStyle($style)
	{
		$this->set("style", $style);
	}

	public function getName()
	{
		return $this->get('name');
	}

	public function getStyle()
	{
		return $this->get('style');
	}

	public function getFormat()
	{
		return $this->get('format');
	}
	
	public function getDefaultResourcePosition()
	{
		return Ajde_Document_Format_Html::RESOURCE_POSITION_FIRST;
	}
}