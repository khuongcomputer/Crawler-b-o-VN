<?php
	
require_once('app/mailer/PHPMailerAutoload.php');

function smtpmailer($to, $subject, $body) { 
	global $error;
	$mail = new PHPMailer();  // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  // authentication enabled
	$mail->SMTPSecure = config('mail.secure'); // secure transfer enabled REQUIRED for GMail
	$mail->Host = config('mail.host');
	$mail->Port = config('mail.port'); 
	$mail->Username = config('mail.username');
	$mail->Password = config('mail.password');         
	$mail->SetFrom(config('mail.username'), config('mail.name'));
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AddAddress($to);
	$mail->CharSet="UTF-8";
	$mail->IsHTML(true);

	if(!$mail->Send()) {
		$error = 'Mail error: '.$mail->ErrorInfo; 
		return false;
	} else {
		$error = '';
		return true;
	}
}