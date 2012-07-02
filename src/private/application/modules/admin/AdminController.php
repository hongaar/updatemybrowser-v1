<?php 

class AdminController extends Ajde_Acl_Controller
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
	
	public function autoUpdate()
	{
		$this->_autoUpdate();
		return $this->render();
	}
	
	private function _autoUpdate()
	{		
		$url = 'http://fresh-browsers.com/export/browsers.serialized';
		$fresh = unserialize(Ajde_Http_Curl::get($url));

		Ajde_Model::register("browser");		
		$browsers = new BrowserCollection();
		
		foreach($browsers as $browser) {
			if (array_key_exists($browser->shortname, $fresh)) {
				$current = $fresh[$browser->shortname]['Stable']['releaseVersion'];
				$c = preg_match_all('/[0-9]+/', $current, $matches);
				if ($c > 0) {
					$browser->current = $matches[0][0];
					if ($c > 1 && (int) $matches[0][1] > 0) {
						$browser->current = $browser->current . "." . $matches[0][1];
					}
				}
				$browser->save();
			}
		}
	}
	
	public function compileScript()
	{
		$this->_compileScript();		
		return $this->render();
	}
	
	private function _compileScript()
	{
		// Register models
		Ajde_Model::register('browser');
			
		// Get browsers
		$browsers = new BrowserCollection();
		$browsers->orderBy("sort");
		$browsers->load();
		$json = $browsers->getJSON();
		
		// Compile browsers.js
		$browsersJsSrc = file_get_contents(MODULE_DIR . 'umb/res/js/browsers.src.js');
		$browsersJs = str_replace('###JSONSTRINGHERE###', $json, $browsersJsSrc);
		file_put_contents(MODULE_DIR . 'umb/res/js/browsers.js', $browsersJs);
		
		// Init compressed resource
		/* @var $compressor Ajde_Resource_Local_Compressor_Js */
		$compressor = Ajde_Resource_Local_Compressor::fromType(Ajde_Resource::TYPE_JAVASCRIPT);
		
		// Sources for script
		$sources = array(
			new Ajde_Resource_Local(Ajde_Resource::TYPE_JAVASCRIPT, MODULE_DIR . 'umb/', 'umb'),
			new Ajde_Resource_Local(Ajde_Resource::TYPE_JAVASCRIPT, MODULE_DIR . 'umb/', 'browsers'),
			new Ajde_Resource_Local(Ajde_Resource::TYPE_JAVASCRIPT, MODULE_DIR . 'umb/', 'detect'),
			new Ajde_Resource_Local(Ajde_Resource::TYPE_JAVASCRIPT, MODULE_DIR . 'umb/', 'status'),
			new Ajde_Resource_Local(Ajde_Resource::TYPE_JAVASCRIPT, MODULE_DIR . 'umb/', 'widget')
		);
		
		// Add to compressor
		foreach ($sources as $source) {
			/* @var $source Ajde_Resource */			
			$compressor->addResource($source);			
		}
		
		// Process and get compressed script
		$resource = $compressor->process();
		$js = $resource->getContents();
		
		// Write
		file_put_contents('umb.js', $js);
	}
	
	public function browser()
	{
		Ajde_Model::register("browser");
		return $this->render();
	}
	
	public function cron()
	{
		Ajde::app()->getDocument()->setLayout(new Ajde_Layout('empty'));
		
		try {
			$this->_autoUpdate();
			$this->_compileScript();
			return 'Admin cronjob OK';
		} catch (Exception $e) {
			return 'Admin cronjob failed with ' . $e->getMessage();
		}
	}
	
}
