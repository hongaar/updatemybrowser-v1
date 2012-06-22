<?php 

class AdminController extends Ajde_Controller
{
	
	function afterInvoke()
	{
		$title = __('Administrator');
		if (Ajde::app()->getDocument()->hasTitle()) {
			$title = $title . '/' . Ajde::app()->getDocument()->getTitle();
		}
		Ajde::app()->getDocument()->setTitle($title);
		return true;
	}
		
	public function view()
	{
		return $this->render();
	}
	
	public function browser()
	{
		Ajde_Model::register("browser");
		return $this->render();
	}
	
}
