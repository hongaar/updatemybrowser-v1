<?php

class _coreDebuggerController extends Ajde_Controller
{
	function view()
	{
		// Grab the view to easily assign variables
		$view = $this->getView();
		
		// Get all dumps from Ajde_Dump::dump() [Aliased as a global function dump()]
		if (Ajde_Dump::getAll()) {
			$view->assign('dump', Ajde_Dump::getAll());			
		}
		
		// Get request parameters
		$view->assign('request', Ajde::app()->getRequest());
		
		// Get Configuration stage
		$view->assign('configstage', Config::$stage);
		
		// Get database queries 
		if (Ajde_Core_Autoloader::exists('AjdeX_Db_PDO')) {
			$view->assign('database', AjdeX_Db_PDO::getLog());
		}
		
		// Get language 
		$view->assign('lang', Ajde_Lang::getInstance()->getLang());
		
		// Get the application timer
		Ajde::app()->endTimer(0);
		Ajde::app()->endTimer(Ajde::app()->getLastTimerKey());
		$view->assign('timers', Ajde::app()->getTimers());
		
		return $this->render();
	}
}