<?php

class AjdeX_Crud extends Ajde_Object_Standard
{
	protected $_model = null;
	protected $_collection = null;
	
	public function __construct($model) {
		if ($model instanceof AjdeX_Model) {
			$this->_model = $model;
		} else {
			$modelName = ucfirst($model) . 'Model';
			$this->_model = new $modelName();
		}
	}
	
	/**
	 * @return AjdeX_Collection
	 */
	public function getCollection()
	{
		if (!isset($this->_collection))	{
			$collectionName = str_replace('Model', '', get_class($this->getModel())) . 'Collection';
			$this->_collection = new $collectionName();
		}
		return $this->_collection;
	}
	
	/**
	 * @return AjdeX_Model
	 */
	public function getModel()
	{
		return $this->_model;
	}
	
	public function getItem($id = null)
	{
		$model = $this->getModel();
		if (isset($id)) {
			$model->loadByPK($id);
		}
		return $model;
	}
	
	public function getItems()
	{
		$collection = $this->getCollection();
		$collection->reset();
		$collection->load();
		return $collection;
	}
	
	public function getFields()
	{
		$model = $this->getModel();
		return $model->getTable()->getFieldProperties();		
	}
	
	public function getFieldLabels()
	{
		$model = $this->getModel();
		return $model->getTable()->getFieldLabels();
	}
}