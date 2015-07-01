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
		$updater = new Umb_Updater_Caniuse();
        $updater->update();

		Ajde_Model::register("browser");		
		$browsers = new BrowserCollection();
		
		foreach($browsers as $browser) {
			if ($version = $updater->getVersion($browser->shortname, $browser->version_channel, $browser->version_platform)) {
                if ((string) $browser->current <> (string) $version) {

                    // Update browser version in DB
                    $browser->current = $version;
                    $browser->minimum = $version - 1;
                    $browser->save();

                    // Send tweet
                    $twitter = new Umb_Twitter();
                    $status = '';
                    if ($browser->vendor) {
                        $status = $status . $browser->vendor . ' ';
                    }
                    $status = $status . $browser->name . ' ';
                    $status = $status . __('updated to version') . ' ';
                    $status = $status . $version . ' ';
                    if ($browser->twitter_user) {
                        $status = $status . '#' . $browser->twitter_user . ' ';
                    }
                    $status = $status . '#uptodate ';
                    $twitter->statusUpdate('http://updatemybrowser.org', $status);

				}				
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
		$browsersJs = str_replace("'###JSONSTRINGHERE###'", $json, $browsersJsSrc);
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
