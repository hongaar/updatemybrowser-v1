<?php

class Ajde_Application extends Ajde_Object_Singleton
{
	protected $_timers = array();
	protected $_timerLevel = 0;
	
	/**
	 *
	 * @staticvar Ajde_Application $instance
	 * @return Ajde_Application
	 */
	public static function getInstance()
	{
		static $instance;
		return $instance === null ? $instance = new self : $instance;
	}

	/**
	 *
	 * @return Ajde_Application
	 */
	public static function app()
	{
		return self::getInstance();
	}

	/**
	 *
	 * @return Ajde_Application
	 */
	public static function create()
	{
		return self::getInstance();
	}
	
	public function addTimer($description)
	{		
		$this->_timers[] = array('description' => $description, 'level' => $this->_timerLevel, 'start' => microtime(true));
		$this->_timerLevel++;		
		return $this->getLastTimerKey();
	}
	
	public function getLastTimerKey()
	{
		end($this->_timers);		
		return key($this->_timers);
	}
	
	public function endTimer($key)
	{
		$this->_timerLevel--;
		$this->_timers[$key]['end'] = $end = microtime(true);
		$this->_timers[$key]['total'] = round(($end - $this->_timers[$key]['start']) * 1000, 0);
	}
	
	public function getTimers()
	{
		return $this->_timers;
	}

	public function run()
	{
		// For debugger
		$timer = $this->addTimer('Application');
		
		// Bootstrap init
		$bootstrap = new Ajde_Core_Bootstrap();
		$bootstrap->run();

		// Get request
		$request = Ajde_Http_Request::fromGlobal();
		$this->setRequest($request);

		// Get route
		$route = $request->getRoute();
		$this->setRoute($route);

		// Load document
		$document = Ajde_Document::fromRoute($route);
		$this->setDocument($document);
		
		// Create fresh response
		$response = new Ajde_Http_Response();
		$this->setResponse($response);

		// Load controller
		$controller = Ajde_Controller::fromRoute($route);
		$this->setController($controller);

		// Invoke controller action
		$actionResult = $controller->invoke();
		$document->setBody($actionResult);

		if (!$document->hasLayout())
		{
			// Load default layout into document
			$layout = new Ajde_Layout(Config::get("layout"));
			$document->setLayout($layout);
		}

		// Get document contents
		$contents = $document->render();

		// Let the cache handle the contents and have it saved to the response
		$cache = Ajde_Cache::getInstance();
		$cache->setContents($contents);
		$cache->saveResponse();

		// Output the buffer
		$response->send();
	}

	public static function routingError(Ajde_Exception $exception)
	{
		if (Config::get("debug") === true)
		{
			throw $exception;
		}
		else
		{
			Ajde_Exception_Log::logException($exception);
			Ajde_Http_Response::redirectNotFound();
		}
	}

	/**
	 *
	 * @return Ajde_Http_Request
	 */
	public function getRequest() {
		return $this->get("request");
	}

	/**
	 *
	 * @return Ajde_Http_Response
	 */
	public function getResponse() {
		return $this->get("response");
	}

	/**
	 *
	 * @return Ajde_Core_Route
	 */
	public function getRoute() {
		return $this->get("route");
	}

	/**
	 *
	 * @return Ajde_Document
	 */
	public function getDocument() {
		return $this->get("document");
	}

	/**
	 *
	 * @return Ajde_Controller
	 */
	public function getController() {
		return $this->get("controller");
	}

	public static function includeFile($filename)
	{
		Ajde_Cache::getInstance()->addFile($filename);
		include $filename;
	}

	public static function includeFileOnce($filename)
	{
		Ajde_Cache::getInstance()->addFile($filename);
		include_once $filename;
	}

}