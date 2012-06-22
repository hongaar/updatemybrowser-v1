<?php

class AjdeX_Filter_Join extends AjdeX_Filter
{	
	protected $_table;
	protected $_ownerField;
	protected $_childField;
	
	public function __construct($table, $ownerField, $childField)
	{
		$this->_table = $table;
		$this->_ownerField = $ownerField;
		$this->_childField = $childField;
	}
	
	public function prepare(AjdeX_Db_Table $table = null)
	{
		$sql = $this->_table . ' ON ' . $this->_ownerField . ' = ' . $this->_childField;
		return array(
			'join' => array(
				'arguments' => array($sql),
				'values' => array()
			)
		);
	}
}