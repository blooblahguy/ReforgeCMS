<?php

$reforge_mail = false;

function htmlToPlainText($str){
	$str = preg_replace('/\<br(\s*)?\/?\>/i', "\n", $str);
    $str = str_replace('&nbsp;', ' ', $str);
    $str = html_entity_decode($str, ENT_QUOTES | ENT_COMPAT , 'UTF-8');
    $str = html_entity_decode($str, ENT_HTML5, 'UTF-8');
    $str = html_entity_decode($str);
    $str = htmlspecialchars_decode($str);
    $str = strip_tags($str);

    return $str;
}

function rf_mail($to, $subject, $body, $attachments = false) {
	global $reforge_mail, $root;

	// initial creation and setup
	if ($reforge_mail == false) {
		include($root."/vendor/PHPMailer/PHPMailer.php");
		include($root."/vendor/PHPMailer/Exception.php");
		include($root."/vendor/PHPMailer/SMTP.php");

		// pull in config
		$config = rf_config();
		list($from, $fromName) = $config['email_from'];
		list($reply, $replyName) = $config['email_replyto'];

		// configure basic mail
		$reforge_mail = new PHPMailer\PHPMailer\PHPMailer(true);
		$reforge_mail->isHTML(true);
		$reforge_mail->setFrom($from, $fromName);
		$reforge_mail->addReplyTo($reply, $replyName);
		$reforge_mail->CharSet = "UTF-8";

		// configure smtp
		if ($config['smtp_enable']) {
			// $reforge_mail->SMTPDebug = 1; // Debug
			$reforge_mail->isSMTP(); // Send using SMTP
			$reforge_mail->Host = $config['smtp_host']; // Set the SMTP server to send through
			$reforge_mail->SMTPAuth = true; // Enable SMTP authentication
			$reforge_mail->Username = $config['smtp_user']; // SMTP username
			$reforge_mail->Password = $config['smtp_password']; // SMTP password
			$reforge_mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$reforge_mail->Port = 587; 
		}
	}
	// Reset our mail object
	$mail = $reforge_mail;
	$mail->ClearAllRecipients();

	try {
		$mail->addAddress($to);
		$mail->Subject = $subject;

		// wrap message in header and footer
		ob_start();
		include "email/header.php";
		echo $body;
		include "email/footer.php";
		$body = ob_get_contents();
		ob_end_clean();

		$mail->Body = $body;

		$mail->AltBody = htmlToPlainText($body);
		$mail->send();

		echo "message_sent";
	} catch (PHPMailer\PHPMailer\Exception $e) {
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}

	

	

	// if(!$mail->Send()) {
	// 	echo 'Email Failed To Send.'; 
	// } 
	// else {
	// 	echo 'Email Was Successfully Sent.'; 
	// }

	
	// 

	// debug("here");
}