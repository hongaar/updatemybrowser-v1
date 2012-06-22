<?php

class Ajde_Document_Format_Html extends Ajde_Document
{
	const RESOURCE_POSITION_TOP = 0;
	const RESOURCE_POSITION_FIRST = 1;
	const RESOURCE_POSITION_DEFAULT = 2;
	const RESOURCE_POSITION_LAST = 3;
	
	protected $_cacheControl = 'private';
	
	protected $_resources = array(
		self::RESOURCE_POSITION_FIRST => array(),
		self::RESOURCE_POSITION_DEFAULT => array(),
		self::RESOURCE_POSITION_LAST => array()
	);
	protected $_compressors = array();
	protected $_meta = array();

	public function  __construct()
	{
		/*
		 * We add the resources before the template is included, otherwise the
		 * layout resources never make it into the <head> section.
		 */
		Ajde_Event::register('Ajde_Template', 'beforeGetContents', array($this, 'autoAddResources'));
		parent::__construct();
	}

	public function render()
	{
		Ajde::app()->getResponse()->addHeader('Content-type', 'text/html');
		$documentProcessors = Config::get('documentProcessors');
		if (is_array($documentProcessors) && isset($documentProcessors['html'])) {
			foreach($documentProcessors['html'] as $processor) {
				$processorClass = 'Ajde_Document_Format_Processor_Html_' . $processor;
				if (!Ajde_Core_Autoloader::exists($processorClass)) {
					// TODO:
					throw new Ajde_Exception('Processor ' . $processorClass . ' not found', 90022);
				}
				Ajde_Event::register('Ajde_Layout', 'beforeGetContents', $processorClass . '::preProcess');
				Ajde_Event::register('Ajde_Layout', 'afterGetContents', $processorClass . '::postProcess');
			}
		}
		return parent::render();
	}

	/**
	 *
	 * @param mixed $resourceTypes
	 * @return string
	 */
	public function getHead($resourceTypes = '*')
	{
		if (!is_array($resourceTypes)) {
			$resourceTypes = (array) $resourceTypes;
		}
		return $this->renderHead($resourceTypes);
	}
	
	public function getScripts()
	{
		return $this->getHead('js');
	}

	public function renderHead(array $resourceTypes = array('*'))
	{		
		$code = '';
		$code .= $this->renderResources($resourceTypes);
		// TODO: meta tags etc
		return $code;
	}

	public function renderResources(array $types = array('*'))
	{
		return Config::get('compressResources') ?
			$this->renderCompressedResources($types) :
			$this->renderAllResources($types);
	}

	public function renderAllResources(array $types = array('*'))
	{
		$code = '';
		foreach ($this->getResources() as $resource)
		{
			/* @var $resource Ajde_Resource */
			if (current($types) == '*' || in_array($resource->getType(), $types))
			{
				$code .= $resource->getLinkCode() . PHP_EOL;
			}
		}
		return $code;
	}

	public function renderCompressedResources(array $types = array('*'))
	{
		// Reset compressors
		$this->_compressors = array();
		$code = '';
		foreach ($this->getResources() as $resource)
		{
			/* @var $resource Ajde_Resource */
			if (current($types) == '*' || in_array($resource->getType(), $types))
			{				
				if ($resource instanceof Ajde_Resource_Local)
				{
					if (!isset($this->_compressors[$resource->getType()]))
					{
						$this->_compressors[$resource->getType()] =
								Ajde_Resource_Local_Compressor::fromType($resource->getType());
					}
					$compressor = $this->_compressors[$resource->getType()];
					/* @var $compressor Ajde_Resource_Local_Compressor */
					$compressor->addResource($resource);
				}
				else
				{
					$code .= $resource->getLinkCode() . PHP_EOL;
				}
			}
		}
		foreach ($this->_compressors as $compressor)
		{
			$resource = $compressor->process();
			$code .= $resource->getLinkCode() . PHP_EOL;
		}
		return $code;
	}

	public function getResourceTypes()
	{
		return array(
			Ajde_Resource::TYPE_JAVASCRIPT,
			Ajde_Resource::TYPE_STYLESHEET
		);
	}

	public function addMeta($contents)
	{
		
	}

	public function addResource(Ajde_Resource $resource, $position = self::RESOURCE_POSITION_DEFAULT)
	{
		// Check for duplicates
		foreach($this->_resources as $positionArray) {
			foreach($positionArray as $item) {
				if ((string) $item == (string) $resource) {
					return false;
				}
			}
		}
		if ($position === self::RESOURCE_POSITION_TOP) {
			array_unshift($this->_resources[self::RESOURCE_POSITION_FIRST], $resource);
		} else {
			$this->_resources[$position][] = $resource;
		}
		return true;	
	}
	
	public function getResources()
	{
		$return = array();
		foreach($this->_resources as $positionArray) {
			$return = array_merge($return, $positionArray);
		}
		return $return;
	}

	public function autoAddResources(Ajde_Template $template)
	{
		$position = $template->getDefaultResourcePosition();
		foreach($this->getResourceTypes() as $resourceType) {
			if ($defaultResource = Ajde_Resource_Local::lazyCreate($resourceType, $template->getBase(), 'default', $template->getFormat()))
			{
				$this->addResource($defaultResource, $position);
			}
			if ($template->getAction() != 'default' &&
				$actionResource = Ajde_Resource_Local::lazyCreate($resourceType, $template->getBase(), $template->getAction(), $template->getFormat()))
			{
				$this->addResource($actionResource, $position);
			}
		}
	}
	
}