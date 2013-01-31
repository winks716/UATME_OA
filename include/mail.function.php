<?php

/*
Using smtp to send mail

$template  the path of mail template
$config  the array contain mail server configuration
*/
function mailSendMail($SMTPConfig){
	global $defaultSMTPConfig;
	
	$config = array_merge($defaultSMTPConfig, $SMTPConfig);

	$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

	$mail->IsSMTP(); // telling the class to use SMTP

	try {
		$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->Host       = $config['host']; // sets the SMTP server
		$mail->Port       = $config['port'];                    // set the SMTP port for the GMAIL server
		$mail->Username   = $config['username']; // SMTP account username
		$mail->Password   = $config['password'];        // SMTP account password
		foreach($config['replyto'] as $c){
			$mail->AddReplyTo($c['mail'], $c['name']);		
		}
		foreach($config['sendto'] as $c){
			$mail->AddAddress($c['mail'], $c['name']);		
		}
		foreach($config['setfrom'] as $c){
			$mail->SetFrom($c['mail'], $c['name']);		
		}
		$mail->Subject = $config['subject'];
		//$mail->AltBody = $config['altbody']; // optional - MsgHTML will create an alternate automatically
		$mail->MsgHTML($config['body']);
		//$mail->AddAttachment('images/phpmailer.gif');      // attachment
		//$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
		$mail->Send();
	} catch (phpmailerException $e) {
		echo $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
		echo $e->getMessage(); //Boring error messages from anything else!
	}
}

?>