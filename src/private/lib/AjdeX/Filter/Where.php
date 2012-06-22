<?php

class AjdeX_Filter_Where extends AjdeX_Filter
{	
	protected $_field;
	protected $_comparison;
	protected $_value;
	protected $_operator;
	
	public function __construct($field, $comparison, $value, $operator = AjdeX_Query::OP_AND)
	{
		$this->_field = $field;
		$this->_comparison = $comparison;
		$this->_value = $value;
		$this->_operator = $operator;
	}
	
	public function prepare(AjdeX_Db_Table $table = null)
	{
		$sql = $this->_field . $this->_comparison . ':' . spl_object_hash($this);
		return array(
			'where' => array(
				'arguments' => array($sql, $this->_operator),
				'values' => array(spl_object_hash($this) => $this->_value)
			)
		);
	}
}