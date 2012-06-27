<?php 

class AboutController extends Ajde_Controller
{
	/**
	 * Optional function called before controller is invoked
	 * When returning false, invocation is cancelled
	 * @return boolean 
	 */
	public function beforeInvoke()
	{
		return true;
	}
	
	/**
	 * Optional function called after controller is invoked
	 */
	public function afterInvoke()
	{
		
	}
	
	/**
	 * Default action for controller, returns the 'view.phtml' template body
	 * @return string 
	 */
	public function view()
	{
		Ajde::app()->getDocument()->setTitle("About");
		return $this->render();
	}
	
	public function docs()
	{
		Ajde::app()->getDocument()->setTitle("Widget documentation");
		return $this->render();
	}
	
	public function contact()
	{
		Ajde::app()->getDocument()->setTitle("Contact us");
		return $this->render();
	}
	
	public function plugins()
	{
		Ajde::app()->getDocument()->setTitle("Plugins");
		return $this->render();
	}
		
	public function stats()
	{
		Ajde::app()->getDocument()->setTitle("Statistics");
		return $this->render();
	}
}