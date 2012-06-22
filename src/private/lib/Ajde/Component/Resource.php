<?php 

class Ajde_Component_Resource extends Ajde_Component
{
	public static function processStatic(Ajde_Template_Parser $parser, $attributes)
	{
		$instance = new self($parser, $attributes);
		return $instance->process();
	}
	
	public function process()
	{
		return false;
	}
	
	public function requireResource($type, $action, $format = 'html', $base = null, $position = Ajde_Document_Format_Html::RESOURCE_POSITION_DEFAULT)
	{
		if (!isset($base)) {
			$base = $this->getParser()->getTemplate()->getBase();			
		}
		$resource = new Ajde_Resource_Local($type, $base, $action, $format);
		Ajde::app()->getDocument()->addResource($resource, $position);
	}
	
	public function requirePublicResource($type, $filename, $position = Ajde_Document_Format_Html::RESOURCE_POSITION_DEFAULT)
	{
		$resource = new Ajde_Resource_Public($type, $filename);
		Ajde::app()->getDocument()->addResource($resource, $position);
	}
	
	public function requireRemoteResource($type, $url, $position = Ajde_Document_Format_Html::RESOURCE_POSITION_DEFAULT)
	{
		$resource = new Ajde_Resource_Remote($type, $url);
		Ajde::app()->getDocument()->addResource($resource, $position);
	}
}