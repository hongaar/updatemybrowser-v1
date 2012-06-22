<?php

class AjdeX_Db_Adapter_MySql extends AjdeX_Db_Adapter_Abstract
{
	protected $_connection = null;
	protected $_dbname = null;
	
	private $_cache = array();
	
	public function __construct($dsn, $user, $password)
	{
		$dsnString = 'mysql:';
		foreach($dsn as $k => $v) {
			if ($k === 'dbname') {
				$this->_dbname = $v;
			}
			$dsnString .= $k . '=' . $v . ';'; 
		}
		parent::__construct(
			$dsnString,
			$user,
			$password, 
		    array(
		    	PDO::MYSQL_ATTR_INIT_COMMAND 	=> "SET NAMES utf8",	// Modern, please
		    	PDO::ATTR_EMULATE_PREPARES 		=> true					// Better caching		    	
		    )
		);
	}
	
	/**
	 * @return PDO
	 */
	public function getConnection()
	{
		return $this->_connection;
	}
	
	private function getCache($sql)
	{
		return array_key_exists($sql, $this->_cache) ? $this->_cache[$sql] : false;
	}
	
	private function saveCache($sql, $result)
	{
		$this->_cache[$sql] = $result;
		return $result;
	}
	
	public function getTableStructure($tableName)
	{
		$sql = 'SHOW FULL COLUMNS FROM '.$tableName;
		if ($cache = $this->getCache($sql)) { return $cache; }
		$statement = $this->getConnection()->query($sql);
		return $this->saveCache($sql, $statement->fetchAll()); 
	}
	
	public function getForeignKey($childTable, $parentTable) {
		$sql = sprintf("SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE
			REFERENCED_TABLE_NAME = %s AND TABLE_NAME = %s AND TABLE_SCHEMA = %s",
			$this->getConnection()->quote($parentTable),
			$this->getConnection()->quote($childTable),
			$this->getConnection()->quote($this->_dbname)
		);
		if ($cache = $this->getCache($sql)) { return $cache; }
		$statement = $this->getConnection()->query($sql);
		return $this->saveCache($sql, $statement->fetch()); 
	}
	
	public function getParents($childTable) {
		$sql = sprintf("SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE
			TABLE_NAME = %s AND TABLE_SCHEMA = %s",
			$this->getConnection()->quote($childTable),
			$this->getConnection()->quote($this->_dbname)
		);
		if ($cache = $this->getCache($sql)) { return $cache; }
		$statement = $this->getConnection()->query($sql);
		return $this->saveCache($sql, $statement->fetchAll()); 
	}
	
	public function getFieldType($type) {
		// TODO: Quite naive, rough implementation
		// @see http://dev.mysql.com/doc/refman/5.0/en/data-types.html
		
		$types = array(
			AjdeX_Db::FIELD_TYPE_NUMERIC => "int tinyint bit float double real decimal numeric currency",
			AjdeX_Db::FIELD_TYPE_STRING => "varchar timestamp time year",
			AjdeX_Db::FIELD_TYPE_TEXT => "text blob",
			AjdeX_Db::FIELD_TYPE_DATE => "date",
			AjdeX_Db::FIELD_TYPE_ENUM => "enum set"
        );

		// Get normalized type
		//$typeName = AjdeX_Db::FIELD_TYPE_STRING;
		$typeName = $type;
		$start = strpos($type, '(');	
		$mysqlName = $start > 0 ? trim(substr($type, 0, $start)) : $type;
		foreach($types as $typeKey => $haystack) {
			if (substr_count($haystack, $mysqlName) > 0) {
				$typeName = $typeKey;
				break;
			}
		}
		
		// Get length/values
		$length = strpos($type, ')') - $start;
		$typeLength = $start > 0 ? trim(substr ($type, $start + 1, $length - 1)) : null; 
				
		return array(
			'type' => $typeName,
			'length' => $typeLength
		);
	}
	
}