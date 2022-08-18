<?php
class Loader {
	// auto load dir property
	protected $_directories = array();
	
	// target directories
	public function regDirectory($dir) {
		$this->_directories[ ] = $dir;
	}
	
	// load register
	public function register() {
		spl_autoload_register(array($this, 'requiredClsFile'));
	}
	
	// register callback
	public function requiredClsFile($class) {
		foreach ($this->_directories as $dir) {
			$file = $dir . '/' . $class . '.php';
			if (is_readable($file))
			{
				require $file;
				return;
			}
		}
	}
}