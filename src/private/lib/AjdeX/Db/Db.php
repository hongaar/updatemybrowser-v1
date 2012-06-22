<?php

class AjdeX_Db extends Ajde_Object_Singleton
{
	protected $_adapter = null;
	protected $_tables = null;
	
	const FIELD_TYPE_NUMERIC = 'numeric';
	const FIELD_TYPE_STRING = 'string';
	const FIELD_TYPE_TEXT = 'text';
	const FIELD_TYPE_ENUM = 'enum';
	const FIELD_TYPE_DATE = 'date';
	
	
	/**
	 * @return AjdeX_Db
	 */
	public static function getInstance()
	{
    	static $instance;
    	return $instance === null ? $instance = new self : $instance;
	}
		
	public function __construct()
	{
		$adapterName = 'AjdeX_Db_Adapter_' . ucfirst(Config::get('dbAdapter'));
		$dsn = Config::get('dbDsn');
		$user = Config::get('dbUser');
		$password = Config::get('dbPassword');
		$this->_adapter = new $adapterName($dsn, $user, $password);
	}
	
	/**
	 * @return AjdeX_Db_Adapter_Abstract
	 */
	public function getAdapter()
	{
		return $this->_adapter;
	}
	
	/**
	 * @return AjdeX_Db_PDO
	 */
	function getConnection()
	{
		return $this->_adapter->getConnection();
	}
	
	function getTable($tableName)
	{
		if (!isset($this->_tables[$tableName])) {
			$this->_tables[$tableName] = new AjdeX_Db_Table($tableName);
		}
		return $this->_tables[$tableName];
	}
}