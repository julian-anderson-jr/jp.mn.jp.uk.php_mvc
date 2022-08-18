<?php
class Session {
	protected static $_session_flag = false;
	protected static $_generated_flag = false;
	
	public function __construct() {
		if (!self::$_session_flag) {
			session_start();
			self::$_session_flag = true;
		}
	}
	
	public function set($key, $value) {
		$_SESSION[$key] = $value;
	}
	
	public function get($key, $par = null) {
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}
		return $par;
	}
	
	public function generateSession($del = true) {
		if (!self::$_generated_flag) {
			session_regenerate_id($del);
			self::$_generated_flag = true;
		}
	}
	
	public function setAuthenticateStatus($flag) {
		$this->set('_authenticated', (bool)$flag);
		$this->generateSession();
	}
	
	public function isAuthenticated() {
		return $this->get('_authenticated', false);
	}
	
	public function clear() {
		$_SESSION = array();
	}
}