<?php 

class Ajde_Component_Css extends Ajde_Component_Resource
{
	public static function processStatic(Ajde_Template_Parser $parser, $attributes)
	{
		$instance = new self($parser, $attributes);
		return $instance->process();
	}
	
	protected function _init()
	{
		return array(
			'action' => 'local',
			'filename' => 'public',
			'href' => 'remote'
		);
	}
	
	public function process()
	{
		switch($this->_attributeParse()) {
			case 'local':
				$this->requireResource(
					Ajde_Resource_Local::TYPE_STYLESHEET,
					$this->attributes['action'],
					issetor($this->attributes['format'], null),
					issetor($this->attributes['base'], null),
					issetor($this->attributes['position'], null)
				);
				break;
			case 'public':
				$this->requirePublicResource(
					Ajde_Resource_Local::TYPE_STYLESHEET,
					$this->attributes['filename'],
					issetor($this->attributes['position'], null)
				);
				break;
		}		
	}
}