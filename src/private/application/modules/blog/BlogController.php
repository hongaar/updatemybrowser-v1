<?php 

class BlogController extends Ajde_Controller
{
	function afterInvoke()
	{
		$title = __('Blog');
		if (Ajde::app()->getDocument()->hasTitle()) {
			$title = $title . '/' . Ajde::app()->getDocument()->getTitle();
		}
		Ajde::app()->getDocument()->setTitle($title);
		return true;
	}
		
	function view()
	{
		return $this->render();
	}
}
