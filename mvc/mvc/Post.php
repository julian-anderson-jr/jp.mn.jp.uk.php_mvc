<?php
abstract class Post extends RequestVariables {
	protected static $_values;
	
	pblic function __construct() {
		$this->setValues();
	}
	
	protected function setValues() {
		foreach ($_POST as $key -> $value) {
			$this->_values[$key] = $value;
		}
	}
}