<?php
/**
 * @source http://www.coderholic.com/php-database-query-logging-with-pdo/
 * Modified for use with Ajde_Document_Format_Processor_Html_Debugger
 */
 
/** 
* Extends PDO and logs all queries that are executed and how long 
* they take, including queries issued via prepared statements 
*/  
class AjdeX_Db_PDO extends PDO
{  
    public static $log = array();  
  
    public function __construct($dsn, $username = null, $password = null, $options = array()) {
    	$options = $options + array(
    		PDO::ATTR_STATEMENT_CLASS => array('AjdeX_Db_PDOStatement', array($this))
		);
        parent::__construct($dsn, $username, $password, $options);  
    }  
  
    public function query($query) {
    	//$cache = AjdeX_Db_Cache::getInstance();
		$log = array('query' => $query);
		$start = microtime(true);
		//if (!$cache->has($query)) {
        	$result = parent::query($query);
			//$cache->set($query, serialize($result));
		//	$log['cache'] = false;			
		//} else {
		//	$result = $cache->get($query);
		//	$log['cache'] = true;
		//}  
		$time = microtime(true) - $start;  
		$log['time'] = round($time * 1000, 0);
        self::$log[] = $log;
        return $result;  
    }  
  
    public static function getLog() {  
        return self::$log;  
    }
}  
  
