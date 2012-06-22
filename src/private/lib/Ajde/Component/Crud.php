<?php 

class Ajde_Component_Crud extends Ajde_Component
{
	public static function processStatic(Ajde_Template_Parser $parser, $attributes)
	{
		$instance = new self($parser, $attributes);
		return $instance->process();
	}
	
	protected function _init()
	{
		return array(
			'view' => 'view'
		);
	}
	
	public function process()
	{
		switch($this->_attributeParse()) {
		case 'view':
			switch($this->attributes['view']) {
			case 'list':				
				$crud = new AjdeX_Crud($this->attributes['model']);
				$options = issetor($this->attributes['options'], array());
				$controller = Ajde_Controller::fromRoute(new Ajde_Core_Route('_core/component:crudList'));
				$controller->setCrudInstance($crud);
				$controller->setCrudOptions($options);
				return $controller->invoke();
				break;
			}
			break;				
		}		
		// TODO:
		throw new Ajde_Component_Exception();	
	}
}