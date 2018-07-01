<?
// Файлы phpmailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

function custom_mail($to, $subject, $message, $additionalHeaders = '')
{ 
	$mail = new PHPMailer;
	
	$mail->isSMTP();
	
	$mail->SMTPDebug = 2;
	
	$mail->Host = 'smtp.yandex.ru';
	
	
	$mail->Port = 465;
	
	$mail->SMTPSecure = 'ssl';
	
	$mail->SMTPAuth = true;
	
	$mail->Username = "andrew9727";
	
	$mail->Password = "defenderdialog";
	
	$mail->setFrom('andrew9727@yandex.com');
	$mail->addAddress($to);
	
	$mail->Subject = 'PHPMailer GMail SMTP test';
	
	$mail->msgHTML($message);
	
	$mail->AltBody = 'This is a plain-text message body';
	
	if (!$mail->send()) {
		/
	} else {
		
	}

	
	return true;
}