<?php
class AccountController extends Controller {
	protected $_authentication = array('index', 'signout');
	const SIGNUP = 'account/signup';
	const SIGNIN = 'account/signin';
	const SIGNUPFIN = 'account/registmail';
	const debug_flag = 0;
	
	public function indexAction() {
		$user = $this->_session->get('auth_user');
		return $this->render(array('auth_user' => $user,));
	}
	
	public function signoutAction() {
		$this->_session->clear();
		
		$this->_session->setAuthenticateStatus(false);
		
		$this->redirect('/' . self::SIGNIN);
	}
	
	public function signupAction() {
		if ($this->_session->isAuthenticated()) {
			return $this->redirect('/account');
		}
		
		return $this->render(array(
			'uid'		=> '',
			'pwd'		=> '',
			'name'		=> '',
			'email'		=> '',
			'birth_day'	=> '',
			'_token'	=> $this->getToken(self::SIGNUP),
		));
	}
	
	public function signinAction() {
		if ($this->_session->isAuthenticated()) {
			return $this->redirect('/account');
		}
		
		$signin_view = $this->render(array(
			'uid'		=> '',
			'pwd'		=> '',
			'_token'	=> $this->getToken(self::SIGNIN),
		));
		return $signin_view;
	}
	
	public function authenticateAction() {
		if ($this->_session->isAuthenticated()) {
			return $this->redirect('/account');
		}
		
		if (!$this->_request->isPost()) {
			$this->httpNotFound();
		}
		
		$token = $this->_request->getPost('_token');
		if (!$this->checkToken(self::SIGNIN, $token)) {
			return $this->redirect('/' . self::SIGNIN);
		}
		
		$uid = $this->_request->getPost('uid');
		$pwd = $this->_request->getPost('pwd');
		
		$errors = array();
		if (!strlen($uid)) {
			$errors[] = 'ユーザIDが入力されていません。';
		}
		
		if (!strlen($pwd)) {
			$errors[] = 'パスワードが入力されていません。';
		}
		
		if (count($errors) === 0) {
			$user = $this->_connect_model->get('auth_user')
				->getAuth_UserRecored($uid);
			if ($user && $user['is_lock'] == 1) {
				$errors[] = 'ユーザはロックされました。';
			} else if ($user && $user['valid_mail'] == 0) {
				$errors[] = 'ユーザIDまたはパスワードが間違っています。';
			} else if (!$user || !password_verify($pwd, $user['pwd'])) {
				$errors[] = 'ユーザIDまたはパスワードが間違っています。';
				$this->_connect_model->get('auth_user')
					->setErrorCount($uid);
			} else {
				$this->_session->setAuthenticateStatus(true);
				$this->_session->set('auth_user', $user);
				return $this->redirect('/');
			}
		}
		
		return $this->render(array(
			'uid'		=> $uid,
			'pwd'		=> '',
			'errors'	=> $errors,
			'_token'	=> $this->getToken(self::SIGNIN),
		), 'signin');
		
	}
	
	public function registerAction() {
		if ($this->_session->isAuthenticated()) {
			return $this->redirect('/account');
		}
		
		if (!$this->_request->isPost()) {
			$this->httpNotFound();
		}
		
		if ($this->_session->isAuthenticated()) {
			$this->redirect('/account');
		}
		
		$token = $this->_request->getPost('_token');
		if (!$this->checkToken(self::SIGNUP, $token)) {
			return $this->redirect(self::SIGNUP);
		}
		
		$uid = $this->_request->getPost('uid');
		$pwd = $this->_request->getPost('pwd');
		$name = $this->_request->getPost('name');
		$email = $this->_request->getPost('email');
		$birth_day = $this->_request->getPost('birth_day');
		
		$errors = array();
		if (!strlen($uid)) {
			$errors[] = 'ユーザIDが入力されていません。';
		} else if (!preg_match('/^\w{3,20}$/', $uid)) {
			$errors[] = 'ユーザIDが半角英数字3文字以上20字以内にしてください。';
		} else if (!$this->_connect_model->get('auth_user')
			->getUIDExists($uid)) {
			$errors[] = '入力したユーザIDが他のユーザが使用しています。';
		}
		
		if (!strlen($pwd)) {
			$errors[] = 'パスワードが入力されていません。';
		} else if (8 > strlen($pwd) || 30 < strlen($pwd)) {
			$errors[] = 'パスワードは8文字以上30文字以内であることが必要です。';
		}
		
		if (!strlen($name)) {
			$errors[] = '氏名が入力されていません。';
		}
		
		if (!strlen($email)) {
			$errors[] = 'e-mailが入力されていません。';
		} else if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)) {
			$errors[] = 'e-mailの入力が間違っています。';
		} else if (!$this->_connect_model->get('auth_user')
			->getMailExists($email)) {
			$errors[] = '入力したe-mailが他のユーザが使用しています。';
		}
		
		if (!strlen($birth_day)) {
			$errors[] = '誕生日が入力されていません。';
		} else if (!$this->validateDate($birth_day, 'Y/m/d')) {
			$errors[] = '誕生日の入力が間違っています。';
		}
		
		if (count($errors) === 0) {
			$this->_connect_model->get('auth_user')
				->insert($uid, $pwd, $name, $email, $birth_day);
			$this->_connect_model->get('mail_history')
				->insert($uid, $email, 'info@julia.yokohama');
			$subject = '仮登録のお知らせ';
			$body = 'この度は、アカウント登録ありがとうございます。<br/>まだ仮登録の状態ですので、以下のリンクから本登録をお願いいたします。<br/><br/>以下をクリック<br/><a href="{0}">{1}</a>';
			$urlpram = 'https://julia.yokohama/mvc/web/index.php/account/registmail?uid=' . urlencode($uid) . '&key=' . urlencode($this->getToken($email));
			$body = str_replace('{0}', $urlpram, $body);
			$body = str_replace('{1}', $urlpram, $body);
			//https://localhost/mvc/web/index.php/account/registmail?uid=poko&key=%242y%2410%24knPi6sFRMCnBAUSz4USLoOAI6BwHBpWuzH06L%2F53qHLrVvb41i7E
			//https://julia.yokohama/mvc/web/index.php/account/registmail?uid=poko&key=%242y%2410%24knPi6sFRMCnBAUSz4USLoOAI6BwHBpWuzH06L%2F53qHLrVvb41i7E..
			//throw new Exception($body);
			if (self::debug_flag == 0)
			{
				$mailto = new Sendmail_php('info@julia.yokohama', $email, $subject, $body);
				if (!$mailto) {
					
				}
			} else {
				$email = $urlpram;
			}
			return $this->redirect('/account/fin?uid=' . urlencode($uid) . '&email=' . urlencode($email) . '&tt=' . urlencode('1'));
		}
		
		return $this->render(array(
			'uid'		=> $uid,
			'pwd'		=> $pwd,
			'name'		=> $name,
			'email'		=> $email,
			'birth_day'	=> $birth_day,
			'errors'	=> $errors,
			'_token'	=> $this->getToken(self::SIGNUP),
		), 'signup');
	}
	
	public function finAction() {
		$this->_session->clear();
		
		$this->_session->setAuthenticateStatus(false);

		$uid = $this->_request->getGet('uid');
		$tt = $this->_request->getGet('tt');
		$email = '';
		if ($tt == 1)
		{
			$email = $this->_request->getGet('email');
			$message = '
仮登録完了しました。
上記、メールに本登録のリンクを
送信しましたので、
本登録して下さい。';
		} else if ($tt == 2) {
			$message = '
本登録に失敗しました。
';
		} else if ($tt == 3) {
			$message = '
本登録が完了しました。
';
		}
		
		return $this->render(array(
			'uid'		=> $uid,
			'email'		=> $email,
			'message'	=> $message,
		), 'fin');
	}
	
	public function registmailAction() {
		$this->_session->clear();
		
		$this->_session->setAuthenticateStatus(false);
		
		$uid = $this->_request->getGet('uid');
		$key = $this->_request->getGet('key');
		
		$user = $this->_connect_model->get('auth_user')
			->getAuth_UserRecored($uid);
		
		$errors = array();
		if (!$user || !strlen($user['email']) || $this->getToken($user['email']) == $key)
		{
			$errors[] = 'ユーザ認証に失敗しました。';
		}
		
		if (count($errors) === 0) {
			$this->_connect_model->get('auth_user')
				->setValidMail($uid, 1);
			return $this->redirect('/account/fin?uid=' . urlencode($uid) . '&email=' . urlencode('') . '&tt=' . urlencode('3'));
			
		}
		return $this->redirect('/account/fin?uid=' . urlencode($uid) . '&email=' . urlencode('') . '&tt=' . urlencode('2'));
		
		//return $this->render(array(
		//	'uid'		=> $uid,
		//	'msgid'		=> 1,
		//	'errors'	=> $errors,
		//	'_token'	=> $this->getToken(self::SIGNUPFIN),
		//), 'signup');
	}
}