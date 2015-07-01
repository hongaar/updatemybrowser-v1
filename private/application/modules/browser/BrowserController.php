<?php 

class BrowserController extends Ajde_Controller
{
	function afterInvoke()
	{
		$title = __('Browser Check');
		if (Ajde::app()->getDocument()->hasTitle()) {
			$title = $title . '/' . Ajde::app()->getDocument()->getTitle();
		}
		Ajde::app()->getDocument()->setTitle($title);
		return true;
	}
	
	function view()
	{
		return $this->check();
	}
		
	function check()
	{
		// Register models
		Ajde_Model::register($this);
		
		// Ensure right view when coming from other action
		$this->setView(Ajde_View::fromRoute('browser/check'));
		
		// Get browsers
		$browsers = new BrowserCollection();
		$browsers->orderBy("sort");
		$browsers->load();
		
		// Set vars and return
		$this->getView()->assign("browsers", $browsers);
		return $this->render();
	}
}
