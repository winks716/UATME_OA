<?php


//SMTP setting, for sending mail
$defaultSMTPConfig = array(
		'host' => 'smtp.163.com',
		'port' => 25,
		'username' => 'oa@163.com',
		'password' => '123456',
		'replyto' => array(array('mail'=>'oa@163.com','name'=>'OAAdmin')),
		'sendto' => array(array('mail'=>'oa@163.com','name'=>'OAAdmin')),
		'setfrom' => array(array('mail'=>'oa@163.com','name'=>'OAAdmin')),
		'subject' => 'Msg from Admin',
		//'altbody' => 'This is a mail from Admin',
		'body' => 'This is a mail from Admin.'
);