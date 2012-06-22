<?php
require_once 'Config_Simple.php';
require_once 'Config_Advanced.php';

class Config_Application extends Config_Advanced
{	
	// Site parameters
	public $ident				= 'browserbar';
	public $sitename 			= 'Browser Bar';
	public $description			= 'Browser Bar';	
	public $author				= 'Nabble';
	public $version 			= array(
									'number' => '0.1',
									'name' => 'alpha'
									);
									
									
	public $homepageRoute		= "browser/check.html";
	//public $defaultRouteParts;
	//public $aliases;
	//public $routes;
	
	//public $titleFormat;
	//public $lang;
	//public $langAutodetect;
	//public $langAdapter;
	//public $timezone;
	public $layout				= 'browser';
	//public $responseCodeRoute;
	
	//public $autoEscapeString;
	//public $autoCleanHtml;
	//public $requirePostToken;
	//public $postWhitelistRoutes;
	public $secret				= 'randomstring';
	//public $cookieDomain;
	//public $cookieSecure;
	//public $cookieHttponly;
	
	//public $cookieLifetime;
	//public $gcLifetime;
	
	//public $compressResources;
	//public $debug;
	//public $useCache;
	//public $documentProcessors;
	
	// Database
	//public $dbAdapter;
	public $dbDsn				= array(
									'host' 		=> 'localhost',
									'dbname'	=> 'browser'
									);
	public $dbUser 				= 'browser';
	public $dbPassword 			= 'bar';	
	//public $textEditor;
	
	//public $registerNamespaces;
	//public $overrideClass;
	
	//public $transactionProviders;
	//public $currency;
	//public $currencyCode;
	//public $defaultVAT;
	
	//public $bootstrap;
	
	public function getParentClass()
	{
		return strtolower(str_replace('Config_', '', get_parent_class('Config_Application')));
	}
	
}