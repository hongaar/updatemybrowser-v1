<?php
/**
 * @source http://www.coderholic.com/php-database-query-logging-with-pdo/
 * Modified for use with Ajde_Document_Format_Processor_Html_Debugger
 */
/** 
* PDOStatement decorator that logs when a PDOStatement is 
* executed, and the time it took to run 
*/  
class AjdeX_Db_PDOStatement extends PDOStatement {  
    
	/**
	 * @see http://www.php.net/manual/en/book.pdo.php#73568
	 */
	public $dbh;
	
    protected function __construct($dbh) {
        $this->dbh = $dbh;
    }
	
    /** 
    * When execute is called record the time it takes and 
    * then log the query 
    * @return PDO result set 
    */  
    public function execute($input_parameters = array()) {
    	//$cache = AjdeX_Db_Cache::getInstance();
		$log = array('query' => '[PS] ' . $this->queryString);
		$start = microtime(true);
		//if (!$cache->has($this->queryString . serialize($input_parameters))) {  
        	$result = parent::execute($input_parameters);
			//$cache->set($this->queryString . serialize($input_parameters), $result);
		//	$log['cache'] = false;			
		//} else {
		//	$result = $cache->get($this->queryString . serialize($input_parameters));
		//	$log['cache'] = true;
		//}  
        $time = microtime(true) - $start;  
		$log['time'] = round($time * 1000, 0);
        AjdeX_Db_PDO::$log[] = $log;
        return $result;  
    }
}  