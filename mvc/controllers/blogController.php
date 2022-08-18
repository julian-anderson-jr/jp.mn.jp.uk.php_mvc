<?php
class BlogController extends Controller {
	protected $_authentication = array('index', 'post');
	const POST = 'messages/post';
	
	public function signoutAction() {
		
	}
	
	public function indexAction() {
		$user = $this->_session->get('auth_user');
		if (!$user) {
			return $this->redirect('/account/signin');
		}
		$dat = $this->_connect_model->get('message')->getUserData($user['uid']);
		$index_view = $this->render(array(
			'messagelist' => $dat,
			'message' => '',
			'_token' => $this->getToken(self::POST),
		));
		return $index_view;
	}
	
	public function userAction($par) {
		$user = $this->_connect_model->get('auth_user')->getAuth_UserRecored($par['uid']);
		
		if (!$user) {
			$this->httpNotFound();
		}
		
		$dat = $this->_connect_model->get('message')->getUserData($user['uid']);
		$user_view = $this->render(array(
			'messagelist' => $dat,
			'user' => $user,
		), 'user');
		return $user_view;
	}
	
	public function specificAction($par) {
		$dat = $this->_connect_model->get('message')->getSpecificMessage($par['id'], $par['uid']);
		
		if (!$dat) {
			$this->httpNotFound();
		}
		
		$user_view = $this->render(array(
			'messages' => $dat,
		), 'specific');
		return $user_view;
	}
	
	public function postAction() {
		if (!$this->_request->isPost()) {
			$this->httpNotFound();
		}
		
		$token = $this->_request->getPost('_token');
		if (!$this->checkToken(self::POST, $token)) {
			return $this->redirect('/');
		}
		
		$message = $this->_request->getPost('message');
		
		$errors = array();
		if (!strlen($message)) {
			$errors[] = '投稿記事を入力してください。';
		} else if (mb_strlen($message) > 200) {
			$errors[] = '投稿記事最大200文字までです。';
		}
		
		if (count($errors) === 0) {
			$user = $this->_session->get('auth_user');
			$uid = $this->_connect_model->get('message')
				->insert($user['uid'], $message);
			return $this->redirect('/');
		}
		
		$user = $this->_session->get('auth_user');
		$dat = $this->_connect_model->get('message')->getUserData($user['uid']);
		
		return $this->render(array(
			'message'	=> $message,
			'messagelist'=> $dat,
			'errors'	=> $errors,
			'_token'	=> $this->getToken(self::POST),
		), 'index');
		
	}
	
}