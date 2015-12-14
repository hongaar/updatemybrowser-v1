<?php
require_once 'Config_Secret.php';

class Config_Application extends Config_Secret
{	
	// Site parameters
	public $ident				= 'umb';
	public $sitename 			= 'Update my Browser';
	public $description			= 'Keep your browser up to date. Detect your current browser and checks if it is up to date. Provides site owners with an easy widget to inform visitors of outdated browsers.';
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
	public $langAutodetect		= false;
	//public $langAdapter;
	//public $timezone;
	public $layout				= 'browser';
	//public $responseCodeRoute;
	
	//public $autoEscapeString;
	//public $autoCleanHtml;
	//public $requirePostToken;
	//public $postWhitelistRoutes;
	//public $secret;
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
	//public $dbDsn;
	//public $dbUser;
	//public $dbPassword;
	//public $textEditor;
	
	public $registerNamespaces	= array(
									'Umb'
									);
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