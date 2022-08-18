<?php
abstract class AppBase {
	protected $_request;
	protected $_response;
	protected $_session;
	protected $_connectModel;
	protected $_router;
	protected $_signinAction = array();
	protected $_displayErrors = false;
	
	const CONTROLLER = 'Controller';
	const VIEWDIR = '/views';
	const MODELDIR = '/models';
	const WEBDIR = '/mvc/web';
	const CONTROLLERDIR = '/controllers';
	
	public function __construct($dspErr) {
		$this->setDisplayErrors($dspErr);
		$this->initialize();
		$this->doDbConnection();
	}
	
	protected function initialize() {
		$this->_router       = new Router($this->getRouteDefinition());
		$this->_connectModel = new ConnectModel();
		$this->_request      = new Request();
		$this->_response     = new Response();
		$this->_session      = new Session();
	}
	
	protected function setDisplayErrors($dspErr) {
		if ($dspErr) {
			$this->_displayErrors = true;
			ini_set('display_errors', 1);
			ini_set('error_reporting', E_ALL);
		} else {
			$this->_displayErrors = false;
			ini_set('display_errors', 0);
		}
	}
	
	
	public function isdisplayErrors() {
		return $this->_displayErrors;
	}
	
	public function run() {
		try {
			$parameters = $this->_router->getRouteParams($this->_request->getPath());
			
			if ($parameters === false) {
				throw new FileNotFoundException(
					'NO ROUTE' . $this->_request->getPath()
				);
			}
			
			$controller = $parameters['controller'];
			$action = $parameters['action'];
			$this->getContent($controller, $action, $parameters);

		} catch (FileNotFoundException $e) {
			$this->dispErrorPage($e);

		} catch (AuthrizedException $e) {
			list($controller, $action) = $this->signinAction;
			$this->getContent($controller, $action);
		}
		$this->_response->send();
	}
	
	public function getContent($controllerName, $action, $parameters = array()){
		$controllerClass = $controllerName . self::CONTROLLER;
		$controller = $this->getControllerObject($controllerClass);
		
		if ($controller === false) {
			throw new FileNotFoundException(
				$controllerClass . ' NOT FOUND.'
			);
		}
		
		$content = $controller->dispatch($action, $parameters);
		$this->_response->setContent($content);
	}
	
	protected function getControllerObject($controllerClass) {
		if (!class_exists($controllerClass)) {
			$controllerFile = $this->getControllerDirectory() . '/' . $controllerClass . '.php';
			if (!is_readable($controllerFile)) {
				return false;
			} else {
				require_once $controllerFile;
				if (!class_exists($controllerClass)) {
					return false;
				}
			}
		}
		$controller = new $controllerClass($this);
		return $controller;
	}
	
	protected function dispErrorPage($e) {
		$this->_response->setStatusCode(404, 'FILE NOT FOUND.');
		$errMessage = $this->isdisplayErrors() ? $e->getMessage() : 'FILE NOT FOUND.';
		$errMessage = htmlspecialchars($errMessage, ENT_QUOTES, 'UTF-8');
		$html = "
<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<title>HTTP 404 Error</title>
</head>
<body>
{$errMessage}
</body>
</html>
";
		$this->_response->setContent($html);
	}
	
	abstract protected function getRouteDefinition();
	
	protected function doDbConnection() {}
	
	public function getRequestObject() {
		return $this->_request;
	}
	
	public function getResponseObject() {
		return $this->_response;
	}
	
	public function getSessionObject() {
		return $this->_session;
	}
	
	public function getConnectModelObject() {
		return $this->_connectModel;
	}
	
	public function getViewDirectory() {
		return $this->getRootDirectory() . self::VIEWDIR;
	}
	
	public function getModelDirectory() {
		return $this->getRootDirectory() . self::MODELDIR;
	}
	
	public function getDocDirectory() {
		return $this->getRootDirectory() . self::WEBDIR;
	}
	
	abstract protected function getRootDirectory();

	public function getControllerDirectory() {
		return $this->getRootDirectory() . self::CONTROLLERDIR;
	}
	
}