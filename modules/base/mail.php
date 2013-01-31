<?php
$SMTPConfig = array(
 //'replyto' => array(array('mail'=>'oa@highsource.com.cn','name'=>'OAAdmin')),
		'sendto' => array(array('mail'=>'oa@highsource.com.cn','name'=>'OAAdmin')),
		'subject' => 'subject for test2',
		'body' => 'Just a Test Body含中文'
);
mailSendMail($SMTPConfig);