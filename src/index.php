<?php

/**
 * AJDE OPEN WEB FRAMEWORK
 * http://code.google.com/p/ajde/
 */

/*********************
 * ERROR REPORTING
 *********************/

// Check PHP version
if (version_compare(PHP_VERSION, '5.2.3') < 0) {
	die('<h3>Ajde requires PHP/5.2.3 or higher.<br>You are currently running PHP/'.phpversion().'.</h3><p>You should contact your host to see if they can upgrade your version of PHP.</p>');
}

// Show errors before errorhandler is initialized in bootstrapping
error_reporting(E_ALL);

// Uncomment to hide uncatchable fatal errors
//ini_set('display_errors', 0);

// Try to catch fatal errors
function shutdown()
{
	$traceOn = array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR);
	if ($error = error_get_last()) if (in_array($error['type'], $traceOn))
	{
		$error = new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
		echo Ajde_Exception_Handler::trace($error);
	}
}
register_shutdown_function('shutdown');

/*********************
 * INCLUDE PATHS
 *********************/

// Define paths
define('PRIVATE_DIR', 		'private/');
define('PUBLIC_DIR', 		'public/');
define('TEMPLATE_DIR', 		'template/');
define('APP_DIR', 			PRIVATE_DIR.'application/');
define('LIB_DIR', 			PRIVATE_DIR.'lib/');
define('VAR_DIR', 			PRIVATE_DIR.'var/');
define('CONFIG_DIR', 		APP_DIR.'config/');
define('LAYOUT_DIR', 		APP_DIR.'layout/');
define('MODULE_DIR', 		APP_DIR.'modules/');
define('LANG_DIR', 			APP_DIR.'lang/');
define('CACHE_DIR', 		VAR_DIR.'cache/');
define('LOG_DIR', 			VAR_DIR.'log/');

// Zend requires include path to be set to the LIB directory
set_include_path(get_include_path() . PATH_SEPARATOR . LIB_DIR);

// Configure the autoloader
require_once(LIB_DIR."Ajde/Core/Autoloader.php");
Ajde_Core_Autoloader::register();

/*********************
 * GLOBAL FUNCTIONS
 *********************/

// The only thing missing in PHP < 5.3
function issetor(&$what, $else = null)
{
	return isset($what) ? $what : $else;
}
 
// Global dump function for debugging
function dump($var, $collapse = false) {
	Ajde_Dump::dump($var, $collapse);
}

// Global translation function
function __($ident, $module = null) {
	return Ajde_Lang::getInstance()->get($ident, $module);
}

/*********************
 * LET'S RUN THINGS
 *********************/

// Run the main application
$app = Ajde::create();

try {
	$app->run();	
} catch (Ajde_Core_Exception_Deprecated $e) {
	// Throw $e to die on deprecated functions / methods (only in debug mode)
	throw $e;
}