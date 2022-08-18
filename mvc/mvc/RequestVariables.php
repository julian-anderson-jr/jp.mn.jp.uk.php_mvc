<?php
abstract class RequestVariables {
	protected static $_values;
	
	pblic function __construct() {
		$this->setValues();
	}
	
	abstract protected function setValues();
	
	public function get($key = null) {
		return $this->_values[$key];
	}
}