<?php

class AjdeX_Collection extends Ajde_Object_Standard implements Iterator, Countable {
	
	/**
	 * @var string
	 */
	protected $_modelName;
	
	/**
	 * @var PDO
	 */
	protected $_connection;
	
	/**
	 * @var PDOStatement
	 */
	protected $_statement;
	
	/**
	 * @var AjdeX_Query
	 */
	protected $_query;
	
	protected $_link = array();
	
	/**
	 * @var AjdeX_Db_Table
	 */
	protected $_table;
	
	protected $_filters = array();	
	public $_filterValues = array();
	
	// For Iterator
	protected $_items = null;
	protected $_position = 0;
	
	public static function register(Ajde_Controller $controller)
	{
		// Extend Ajde_Controller
		if (!Ajde_Event::has('Ajde_Controller', 'call', 'AjdeX_Collection::extendController')) {
			Ajde_Event::register('Ajde_Controller', 'call', 'AjdeX_Collection::extendController');
		}
		// Extend autoloader
		Ajde_Core_Autoloader::addDir(MODULE_DIR.$controller->getModule().'/model/');
	}
	
	public static function extendController(Ajde_Controller $controller, $method, $arguments)
	{
		// Register getCollection($name) function on Ajde_Controller
		if ($method === 'getCollection') {
			return self::getCollection($arguments[0]);
		}
		// TODO: if last triggered in event cueue, throw exception
		// throw new Ajde_Exception("Call to undefined method ".get_class($controller)."::$method()", 90006);
		// Now, we give other callbacks in event cueue chance to return
		return null; 
	}
	
	public static function getCollection($name)
	{
		$collectionName = ucfirst($name) . 'Collection';
		return new $collectionName();
	}
	
	public function __construct()
	{
		$this->_modelName = str_replace('Collection', '', get_class($this)) . 'Model';		
		$this->_connection = AjdeX_Db::getInstance()->getConnection();
		$tableName = strtolower(str_replace('Collection', '', get_class($this)));	
		$this->_table = AjdeX_Db::getInstance()->getTable($tableName);
		$this->_query = new AjdeX_Query();
	}
	
	public function __sleep()
	{
		return array('_modelName', '_items');
	}

	public function __wakeup()
	{
	}
	
	public function rewind() {
		if (!isset($this->_items)) {
    		$this->load();
    	}
        $this->_position = 0;
    }

    public function current() {    	
        return $this->_items[$this->_position];
    }

    public function key() {
        return $this->_position;
    }

    public function next() {
        $this->_position++;
    }
	
	public function count() 
	{
		if (!isset($this->_items)) {
    		$this->load();
    	}
		return count($this->_items);
	}
	
	public function find($field, $value) {
		foreach($this as $item) {
			if ($item->{$field} == $value) {
				return $item;
			}
		}
		return false;
	}

    function valid() {
        return isset($this->_items[$this->_position]);
    }
	
	/**
	 * @return AjdeX_Db_PDO
	 */
	public function getConnection()
	{
		return $this->_connection;
	}
	
	/**
	 * @return AjdeX_Db_Table
	 */
	public function getTable()
	{
		return $this->_table;
	}
	
	/**
	 * @return PDOStatement
	 */
	public function getStatement()
	{
		return $this->_statement;
	}
	
	/**
	 * @return AjdeX_Query
	 */
	public function getQuery()
	{
		return $this->_query;
	}
		
	public function populate($array)
	{
		$this->reset();
		$this->_data = $array;
	}
	
	public function getLink($modelName, $value)
	{
		if (!array_key_exists($modelName, $this->_link)) {
			// TODO:
			throw new AjdeX_Exception('Link not defined...');
		}
		return new AjdeX_Filter_Link($this, $modelName, $this->_link[$modelName], $value);
	}
	
	// Chainable collection methods
	public function addFilter(AjdeX_Filter $filter)
	{
		$this->_filters[] = $filter;
		return $this;		
	}
	
	public function orderBy($field, $direction = AjdeX_Query::ORDER_ASC)
	{
		$this->getQuery()->addOrderBy($field, $direction);
		return $this;
	}
	
	public function limit($count, $start = 0)
	{
		$this->getQuery()->limit((int) $count, (int) $start);
		return $this;
	}
	
	public function getSql()
	{		
		foreach($this->getTable()->getFieldNames() as $field) {
			$this->getQuery()->addSelect((string) $this->getTable() . '.' . $field);
		}
		if (!empty($this->_filters)) {
			foreach($this->getFilter('select') as $select) {
				call_user_func_array(array($this->getQuery(), 'addSelect'), $select);
			}
		}
		$this->getQuery()->addFrom($this->_table);
		if (!empty($this->_filters)) {
			foreach($this->getFilter('join') as $join) {
				call_user_func_array(array($this->getQuery(), 'addJoin'), $join);
			}
		}
		if (!empty($this->_filters)) {
			foreach($this->getFilter('where') as $where) {
				call_user_func_array(array($this->getQuery(), 'addWhere'), $where);
			}
		}
		return $this->getQuery()->getSql();
	}
	
	public function getFilter($queryPart)
	{
		$arguments = array();
		foreach($this->_filters as $filter) {
			$prepared = $filter->prepare($this->getTable());			
			if (isset($prepared[$queryPart])) {
				if (isset($prepared[$queryPart]['values'])) {
					$this->_filterValues = array_merge($this->_filterValues, $prepared[$queryPart]['values']);
				}
				$arguments[] = $prepared[$queryPart]['arguments'];
			}			
		}
		return empty($arguments) ? array() : $arguments;
	}	
	
	public function getFilterValues()
	{
		return $this->_filterValues;
	}
	
	// Load the collection
	public function load()
	{
		if (!$this->getConnection() instanceof AjdeX_Db_PDO) {
			// return false;
		}
		$this->_statement = $this->getConnection()->prepare($this->getSql());
		$this->_statement->execute($this->getFilterValues());
		return $this->_items = $this->_statement->fetchAll(PDO::FETCH_CLASS, $this->_modelName);
	}
	
	public function loadParents()
	{
		if (count($this) > 0) {
			foreach($this as $model) {
				$model->loadParents();
			}
		}
	}
	
	public function length()
	{
		return isset($this->_items) ? count($this->_items) : 0;
	}
}