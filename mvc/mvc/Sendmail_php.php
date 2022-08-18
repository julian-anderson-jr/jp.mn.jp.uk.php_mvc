<?php
//PHPの設定
date_default_timezone_set('Asia/Tokyo');
mb_language('uni');
//mb_language("ja");
mb_internal_encoding("UTF-8");

//PHPMailerの使用宣言
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//自身の環境に合わせてPHPMailer読み込みパスを修正
require("PHPMailer/Exception.php");
require("PHPMailer/PHPMailer.php");
require("PHPMailer/SMTP.php");

class Sendmail_php {
	protected $_mail = null;
	
	//
	//
	public function __construct($from, $to, $subject, $body) {
		//Recipients
		if (
			(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $from))
			|| (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $to))
			) {
			return null;
		}
		try {
			$this->_mail = new PHPMailer(true);
			
			//$this->_mail->CharSet = PHPMailer::CHARSET_UTF8;
			$this->_mail->CharSet = 'utf-8';
			
			// デバッグ設定
			$this->_mail->SMTPDebug = 0;
			//$this->_mail->SMTPDebug = 2; // デバッグ出力を有効化（レベルを指定）
			//$this->_mail->Debugoutput = function($str, $level) {echo "debug level $level; message: $str<br>";};
			
			// SMTPサーバの設定
			$this->_mail->isSMTP();                          // SMTPの使用宣言
			$this->_mail->Host       = 'smtp.com';   // SMTPサーバーを指定
			$this->_mail->SMTPAuth   = true;                 // SMTP authenticationを有効化
			$this->_mail->Username   = 'info@com';   // SMTPサーバーのユーザ名
			$this->_mail->Password   = 'password';           // SMTPサーバーのパスワード
			//$this->_mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // 暗号化を有効（tls or ssl）無効の場合はfalse
			$this->_mail->SMTPSecure = 'ssl'; // 暗号化を有効（tls or ssl）無効の場合はfalse
			$this->_mail->Port       = 465; // TCPポートを指定（tlsの場合は465や587）
			$this->_mail->Sendmail = '/usr/sbin/sendmail';
			
			// 送受信先設定（第二引数は省略可）
			$this->_mail->setFrom($from, mb_encode_mimeheader('アカウント申請メール')); // 送信者
			$this->_mail->addAddress($to); // 宛先
			//$this->_mail->addReplyTo('replay@example.com', 'お問い合わせ'); // 返信先
			//$this->_mail->addCC('cc@example.com', '受信者名'); // CC宛先
			//$this->_mail->addBCC('dd@example.com', '受信者名'); // BCC宛先
			$this->_mail->Sender = $from; // Return-path
			
			//Attachments
			//$this->_mail->addAttachment('/var/tmp/file.tar.gz');      // Add attachments
			//$this->_mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name
			
			// 送信内容設定
			$this->_mail->isHTML(true); // Set email format to HTML
			$this->_mail->Subject = mb_encode_mimeheader($subject);
			$this->_mail->Body = $body;
			$this->_mail->AltBody = '';
			
			// 送信
			$this->_mail->send();
			return $this;
		} catch (Exception $e) {
			// エラーの場合
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
		return null;
	}
}
