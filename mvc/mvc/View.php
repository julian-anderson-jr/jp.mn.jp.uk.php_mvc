<?php
class View {
	protected $_baseUrl;
	protected $_initialValue;
	protected $_passValues = array();
	
	public function __construct($baseUrl, $initialValue = array()) {
		$this->_baseUrl = $baseUrl;
		$this->_initialValue = $initialValue;
	}
	
	public function setTargetTitle($name, $value) {
		$this->_passValues[$name] = $value;
	}
	
	public function render(
			$filename, $parameters = array(), $template = false
		) {
		$view = $this->_baseUrl . '/' . $filename . '.php';
		extract(array_merge($this->_initialValue, $parameters));
		ob_start();
		ob_implicit_flush(0);
		require $view;
		$content = ob_get_clean();
		if ($template) {
			$content = $this->render(
				$template,
				array_merge(
					$this->_passValues,
					array('_content' => $content)
				)
			);
		}
		
		return $content;
	}
	
	public function escape($str) {
		return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
	}
}