<?php

class Config_Default
{
	/**
	 * Please do not edit this configuration file, this makes it easier
	 * to upgrade when defaults are changed or new values are introduced.
	 * Instead, use Config_Application to override default values. 
	 */
		
	// Site parameters, defined in Config_Application
	public $ident				= null;
	public $sitename 			= null;	
	public $version 			= array(
									"number" => null,
									"name" => null
									);
									
	// Routing
	public $homepageRoute		= "main.html";
	public $defaultRouteParts	= array(
									"module" => "main",
									"controller" => null,
									"action" => "view",
									"format" => "html"
									);       
	public $aliases				= array(
									"home.html" => "main.html"
									);											
	public $routes				= array(
									);
									
	// Presentation
	public $lang 				= "en_GB";
	public $langAutodetect		= true;
	public $langAdapter			= "ini";
	public $timezone			= "UTC";
	public $layout 				= "default";
	
	// Performance
	public $compressResources	= true;
	public $debug 				= false;
	public $useCache			= true;
	public $documentProcessors	= array();
	
	// Extension settings
	public $dbAdapter			= "mysql";
	public $dbDsn				= array(
									"host" 		=> "localhost",
									"dbname"	=> "ajde"
									);
	public $dbUser 				= "ajde";
	public $dbPassword 			= "ajde";	
	public $registerNamespaces	= array(
									);

	// Which modules should we call on bootstrapping?
	public $bootstrap			= array(
									"Ajde_Exception_Handler",
									"Ajde_Session",
									);

	function __construct()
	{
		$this->local_root = $_SERVER["DOCUMENT_ROOT"] . str_replace("/index.php", "", $_SERVER["PHP_SELF"]);
		$this->site_domain = $_SERVER["SERVER_NAME"];
		$this->site_path = str_replace('index.php', '', $_SERVER["PHP_SELF"]);
		$this->site_root = $this->site_domain . $this->site_path;
		date_default_timezone_set($this->timezone);
	}
	
}