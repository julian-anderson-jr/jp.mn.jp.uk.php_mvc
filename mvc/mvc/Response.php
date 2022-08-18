<?php
class Response {
	protected $_content;
	protected $_statusCode = 200;
	protected $_statusMsg = 'OK';
	protected $_headers = array();
	const HTTP = 'HTTP/1.1 ';
	
	
	public function send() {
		header('self::HTTP ' . $this->_statusCode . ' ' . $this->_statusMsg);
		foreach($this->_headers as $name => $value) {
			header($name . ': ' . $value);
		}
		print $this->_content;
	}
	
	public function setContent($content){
		$this->_content = $content;
	}
	
	public function setStatusCode($code, $msg = '') {
		$this->_statusCode = $code;
		$this->_statusMsg = $msg;
	}

	public function setHeader($name, $value) {
		$this->_headers[$name] = $value;
	}
	
}