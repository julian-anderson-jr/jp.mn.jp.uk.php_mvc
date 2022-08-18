<?php
class BlogApp extends AppBase {
	protected function doDbConnection() {
		$this->_connectModel->connect('master', array(
			'string' => 'mysql:dbname=dbsample;host=127.0.0.1;charset=utf8',
			'user'   => 'user',
			'password' => 'password',
		));
	}
		
	public function getRootDirectory() {
		return dirname(__FILE__);
	}
	
	protected function getRouteDefinition() {
		return array(
			'/account'
				=> array('controller' => 'account', 'action' => 'index'),
			'/account/:action'
				=> array('controller' => 'account'),
			'/'
				=> array('controller' => 'blog', 'action' => 'index'),
			'/messages/post'
				=> array('controller' => 'blog', 'action' => 'post'),
			'/user/:uid'
				=> array('controller' => 'blog', 'action' => 'user'),
			'/user/:uid/messages/:id'
				=> array('controller' => 'blog', 'action' => 'specific'),
		);
	}
}