<?php 

class Ajde_Template_Parser_Phtml_Helper extends Ajde_Object_Standard
{
	/**
	 * 
	 * @var Ajde_Template_Parser
	 */
	protected $_parser = null;
	
	/**
	 * 
	 * @param Ajde_Template_Parser $parser
	 */
	public function __construct(Ajde_Template_Parser $parser)
	{
		$this->_parser = $parser;
	}
	
	/**
	 * 
	 * @return Ajde_Template_Parser
	 */
	public function getParser()
	{
		return $this->_parser;
	}
	
	/************************
	 * Ajde_Component_Js
	 ************************/
	
	/**
	 *
	 * @param string $name
	 * @param string $version
	 * @return void 
	 */
	public function requireJsLibrary($name, $version)
	{
		return Ajde_Component_Js::processStatic($this->getParser(), array('library' => $name, 'version' => $version));
	}
	
	/**
	 * 
	 * @param string $action
	 * @param string $format
	 * @param string $base
	 * @param integer $position
	 * @return void
	 */
	public function requireJs($action, $format = 'html', $base = null, $position = Ajde_Document_Format_Html::RESOURCE_POSITION_DEFAULT)
	{
		return Ajde_Component_Js::processStatic($this->getParser(), array('action' => $action, 'format' => $format, 'base' => $base, 'position' => $position));
	}
	
	/**
	 * 
	 * @param string $action
	 * @param string $format
	 * @param string $base
	 * @return void
	 */
	public function requireJsFirst($action, $format = 'html', $base = null)
	{
		return $this->requireJs($action, $format, $base, Ajde_Document_Format_Html::RESOURCE_POSITION_FIRST);
	}
	
	/**
	 * 
	 * @param string $filename
	 * @param integer $position
	 * @return void
	 */
	public function requireJsPublic($filename, $position = Ajde_Document_Format_Html::RESOURCE_POSITION_DEFAULT)
	{
		return Ajde_Component_Js::processStatic($this->getParser(), array('filename' => $filename, 'position' => $position));
	}
	
	/**
	 * 
	 * @param string $url
	 * @param integer $position
	 * @return void
	 */
	public function requireJsRemote($url, $position = Ajde_Document_Format_Html::RESOURCE_POSITION_DEFAULT)
	{
		return Ajde_Component_Js::processStatic($this->getParser(), array('url' => $url, 'position' => $position));
	}
	
	/************************
	 * Ajde_Component_Css
	 ************************/
	
	/**
	 * 
	 * @param string $action
	 * @param string $format
	 * @param string $base
	 * @param integer $position
	 * @return void
	 */
	public function requireCss($action, $format = 'html', $base = null, $position = Ajde_Document_Format_Html::RESOURCE_POSITION_DEFAULT)
	{
		return Ajde_Component_Css::processStatic($this->getParser(), array('action' => $action, 'format' => $format, 'base' => $base, 'position' => $position));
	}

	/**
	 * 
	 * @param string $action
	 * @param string $format
	 * @param string $base
	 * @return void
	 */
	public function requireCssFirst($action, $format = 'html', $base = null)
	{
		return $this->requireCss($action, $format, $base, Ajde_Document_Format_Html::RESOURCE_POSITION_FIRST);
	}
	
	/**
	 * 
	 * @param string $action
	 * @param string $format
	 * @param string $base
	 * @return void
	 */
	public function requireCssTop($action, $format = 'html', $base = null)
	{
		return $this->requireCss($action, $format, $base, Ajde_Document_Format_Html::RESOURCE_POSITION_TOP);
	}
	
	/**
	 * 
	 * @param string $filename
	 * @param integer $position
	 * @return void
	 */
	public function requireCssPublic($filename, $position = Ajde_Document_Format_Html::RESOURCE_POSITION_DEFAULT)
	{
		return Ajde_Component_Css::processStatic($this->getParser(), array('filename' => $filename, 'position' => $position));
	}
	
	/************************
	 * Ajde_Component_Include
	 ************************/

	/**
	 *
	 * @param string $route
	 * @return string
	 */
	public function includeModule($route)
	{
		return Ajde_Component_Include::processStatic($this->getParser(), array('route' => $route));
	}
	
	/************************
	 * Ajde_Component_Form
	 ************************/

	/**
	 *
	 * @param string $route
	 * @param mixed $id
	 * @return string
	 */
	public function ajaxForm($route, $id = null, $class = null)
	{
		return Ajde_Component_Form::processStatic($this->getParser(), array('route' => $route, 'id' => $id, 'class' => $class));
	}
	
	/************************
	 * Ajde_Component_Crud
	 ************************/

	/**
	 *
	 * @param mixed $model
	 * @return string
	 */
	public function crudList($model, $options = array())
	{
		return Ajde_Component_Crud::processStatic($this->getParser(),
			array(
				'view' => 'list',
				'model' => $model,
				'options' => $options
			)
		);
	}
}