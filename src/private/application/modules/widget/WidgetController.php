<?php 

class WidgetController extends Ajde_Controller
{
	function afterInvoke()
	{
		$title = __('Widget');
		if (Ajde::app()->getDocument()->hasTitle()) {
			$title = $title . '/' . Ajde::app()->getDocument()->getTitle();
		}
		Ajde::app()->getDocument()->setTitle($title);
		return true;
	}
		
	function view()
	{
		// Register models
		Ajde_Model::register('browser');
		
		// Menu colors
		$colors = array(
			'html5' => 'f58220',
			'wordpress' => '096aa5',
			'drupal' => '009edc',
			'typo3' => '3ab54a'
		);
		
		// Get browsers
		$browsers = new BrowserCollection();
		$browsers->orderBy("sort");
		$browsers->load();
		
		// Set vars and return
		$this->getView()->assign("browsers", $browsers);
		$this->getView()->assign("colors", $colors);
		return $this->render();
	}
}
