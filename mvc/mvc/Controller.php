<?php
abstract class Controller {
	// application instance
	protected $_application;
	// controller class name
	protected $_controller;
	// action name
	protected $_action;
	// request object
	protected $_request;
	// response object
	protected $_response;
	// session object
	protected $_session;
	// connectmodel object
	protected $_connect_model;
	// login require flag
	protected $_authentication = array();
	// set protocol
	const PROTOCOL = 'https://';
	// set action head name
	const ACTION = 'Action';

	public function __construct($application) {
		$this->_controller		= strtolower(substr(get_class($this), 0, -10));
		$this->_application		= $application;
		$this->_request			= $application->getRequestObject();
		$this->_response		= $application->getResponseObject();
		$this->_session			= $application->getSessionObject();
		$this->_connect_model	= $application->getConnectModelObject();
	}
	
	public function validateDate($date, $format = 'Y/m/d H:i:s') {
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	
	public function dispatch($action, $params = Array()) {
		$this->_action = $action;
		
		$action_method = $action . self::ACTION;
		
		if (!method_exists($this, $action_method)) {
			$this->httpNotFound();
		}
		
		if ($this->isAuthentication($action)
			&& !$this->_session->isAuthenticated()) {
			$this->redirect('/account/signin');
			//throw new AuthorizedException();
		}
		
		$content = $this->$action_method($params);
		return $content;
	}
	
	protected function httpNotFound() {
		throw new FileNotFoundException('FILE NOT FOUND '
			. $this->_controller . '/' . $this->_action
		);
	}
	
	protected function isAuthentication($action) {
		if ($this->_authentication === true 
			|| (is_array($this->_authentication)
			&& in_array($action, $this->_authentication))
			) {
			return true;
		}
		return false;
	}
	
	protected function render(
		$param = array(), $viewFile = null, $template = null) {
		$info = array(
			'request'	=> $this->_request,
			'base_url'	=> $this->_request->getBaseUrl(),
			'session'	=> $this->_session,
		);
		
		$view = new View($this->_application
			->getViewDirectory(), $info);
		
		if (is_null($viewFile)) {
			$viewFile = $this->_action;
		}
		
		if (is_null($template)) {
			$template = 'template';
		}
		
		$path = $this->_controller . '/' .$viewFile;
		$content = $view->render($path, $param, $template);
		return $content;
	}
	
	protected function redirect($url) {
		$host = $this->_request->getHostName();
		$base_url = $this->_request->getBaseUrl();
		$url = self::PROTOCOL . $host . $base_url . $url;
		$this->_response->setStatusCode(302, 'Found');
		$this->_response->setHeader('Location', $url);
	}
	
	protected function getToken($form) {
		$key = 'token/' . $form;
		$tokens = $this->_session->get($key, array());
		
		if (count($tokens) >= 10) {
			array_shift($tokens);
		}
		
		$password = $form . session_id();
		$token = password_hash($password, PASSWORD_DEFAULT);
		$tokens[] = $token;
		$this->_session->set($key, $tokens);
		return $token;
	}
	
	protected function checkToken($form, $token) {
		$key = 'token/' . $form;
		$tokens = $this->_session->get($key, array());
		
		if (false !== ($present = array_search($token, $tokens, true))) {
			unset($tokens[$present]);
			$this->_session->set($key, $tokens);
			return true;
		}
		return false;
	}
}