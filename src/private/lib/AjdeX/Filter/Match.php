<?php

class AjdeX_Filter_Match extends AjdeX_Filter
{	
	protected $_fields;
	protected $_against;
	protected $_operator;
	protected $_table;
	
	public function __construct($fields, $against, $operator = AjdeX_Query::OP_AND, $table = null)
	{
		$this->_fields = $fields;
		$this->_against = $against;
		$this->_operator = $operator;
		$this->_table = $table;
	}
	
	public function prepare(AjdeX_Db_Table $table = null)
	{
		$sql = 'MATCH (' . implode(', ', $this->_fields) . ') AGAINST (:' . spl_object_hash($this) . ')';
		return array(
			'where' => array(
				'arguments' => array($sql, $this->_operator),
				'values' => array(spl_object_hash($this) => $this->_against)
			),
			'select' => array(
				'arguments' => array($sql . ' AS relevancy_' . (isset($this->_table) ? (string) $this->_table : (string) $table))
			)
		);
	}
}