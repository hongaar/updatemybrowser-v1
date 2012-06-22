<?php

class Config_Application extends Config_Default
{	
	// Site parameters
	public $ident				= "browsernotification";
	public $sitename 			= "Browser Notification";	
	public $version 			= array(
									"number" => "1",
									"name" => "alpha"
									);
									
									
	//public $homepageRoute;
	//public $defaultRouteParts;
	public $aliases				= array(
									"home.html" => "browser/check.html",
									"main.html" => "browser/check.html"
									);			
	//public $routes;
	
	//public $lang;
	//public $langAutodetect;
	public $langAdapter			= "gettext";
	//public $timezone;
	//public $layout;
	
	//public $compressResources;
	//public $debug;
	//public $useCache;
	//public $documentProcessors;
	
	//public $dbAdapter;
	public $dbDsn				= array(
									"host" 		=> "localhost",
									"dbname"	=> "browser"
									);
	public $dbUser 				= "browser";
	public $dbPassword 			= "bar";	
	//public $registerNamespaces;
	
	//public $bootstrap;
	
}