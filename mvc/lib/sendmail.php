<?php
//PHPの設定
date_default_timezone_set('Asia/Tokyo');
mb_language("ja");
mb_internal_encoding("UTF-8");

//PHPMailerの使用宣言
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//自身の環境に合わせてPHPMailer読み込みパスを修正
require_once("PHPMailer/src/Exception.php");
require_once("PHPMailer/src/PHPMailer.php");
require_once("PHPMailer/src/SMTP.php");

//PHPMailerの使用
$mailer = new PHPMailer(true);    //Passing `true` enables exceptions

try {
  //Server settings
  $mailer->CharSet = 'UTF-8';
  $mailer->SMTPDebug = 0;         // Enable verbose debug output
  $mailer->isSMTP();              // Set mailer to use SMTP
  $mailer->Host = 'SMTPサーバ';    // Specify main and backup SMTP servers
  $mailer->SMTPAuth = true;       // Enable SMTP authentication
  $mailer->Username = 'ユーザ名';  // SMTP username
  $mailer->Password = 'パスワード';// SMTP password
  $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;// Enable TLS encryption, `ssl` also accepted
  $mailer->Port = 587;            // TCP port to connect to (ssl:465)

  //Recipients
  $mailer->setFrom('from@example.com', mb_encode_mimeheader('送信者名'));
  $mailer->addAddress('joe@example.net', mb_encode_mimeheader('宛先者'));
  $mailer->addAddress('ellen@example.com');  // Name is optional
  $mailer->addReplyTo('info@example.com', 'Information');
  $mailer->addCC('cc@example.com');
  $mailer->addBCC('bcc@example.com');

  //Attachments
  $mailer->addAttachment('/var/tmp/file.tar.gz');      // Add attachments
  $mailer->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name

  //Content
  $mailer->isHTML(true); // Set email format to HTML
  $mailer->Subject = mb_encode_mimeheader('件名');
  $mail->Body = ' HTML形式の本文 <b>太字</b>';
  $mail->AltBody = 'non-HTML mail cliants用本文';

  $mailer->send();
  echo 'Message has been sent';

} catch (Exception $e) {
  echo 'Message could not be sent. Mailer Error: ', $mailer->ErrorInfo;
}