<?php

class Config_Dev extends Config_Application {
	
	// Performance
	public $compressResources	= false;
	public $debug 				= true;
	public $useCache			= false;
	public $documentProcessors	= array(
									"html" => array(
										"Debugger",
										/*
										 * Disable Beautifier processor by default
										 * as Tidy class is not included in quite
										 * some PHP builds
										 * @see https://code.google.com/p/ajde/wiki/Exception90023
										 * 
										 */
										// "Beautifier"										
									)
								  );	

	function __construct() {
		parent::__construct();
	}
	
}