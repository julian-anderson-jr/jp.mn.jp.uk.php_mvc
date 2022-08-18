<?php
class ConnectModel {
	protected $_dbConnections = array();
	protected $_modelList = array();
	protected $_connectName;
	
	const MODEL = 'Model';
	
	public function connect($name, $connection_strings) {
		try
		{
			$cnt = new PDO(
				$connection_strings['string'],
				$connection_strings['user'],
				$connection_strings['password']
			);
		}catch(PDOException $e){
			exit("データベースの接続に失敗しました。: {$e->getMessage()}");
		}
		$cnt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->_dbConnections[$name] = $cnt;
		$this->_connectName = $name;
	}
	
	public function getConnection($name = null) {
		if (is_null($name)) {
			return current($this->_dbConnections);
		}
		return $this->_dbConnections[$name];
	}
	
	public function getModelConnection($model_name) {
		if (isset($this->_connectName)) {
			$name = $this->_connectName;
			$cnt = $this->getConnection($name);
		} else {
			$cnt = $this->getConnection();
		}
		return $cnt;
	}
	
	public function get($model_name) {
		if (!isset($this->_modelList[$model_name])) {
			$mdl_class = $model_name . self::MODEL;
			$cnt = $this->getModelConnection($model_name);
			$obj = new $mdl_class($cnt);
			$this->_modelList[$model_name] = $obj;
			
		}
		$modelObj = $this->_modelList[$model_name];
		return $modelObj;
	}
	
	public function __destruct() {
		foreach ($this->_modelList as $model) {
			unset($model);
		}
		foreach ($this->_dbConnections as $cnt) {
			unset($cnt);
		}
	}
}