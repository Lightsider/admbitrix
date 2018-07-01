<?php
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

$mail->SMTPDebug = 0;

$mail->Host = 'smtp.yandex.ru';


$mail->Port = 465;

$mail->SMTPSecure = 'ssl';

$mail->SMTPAuth = true;

$mail->Username = "***";

$mail->Password = "***";

$mail->CharSet = "UTF-8";

//    preg_match('/From: (.+)\n/i', $additionalHeaders, $matches);
//    list(, $from) = $matches;  // Если почтовый домен привязан
//mb_convert_encoding('текст в utf', "CP1251", "UTF-8")


$mail->setFrom("testforobivan@yandex.ru");


$mail->addAddress($to);

$mail->Subject = $subject;

$mail->msgHTML($message);

if (!$mail->send()) {

} else {

}
return true;
}