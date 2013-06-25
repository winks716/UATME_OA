<?php
//include db config and connect database
require_once('../db.config.php');
//import shared class files
foreach(glob('../include/class/*/class.*.php') as $f){
	require_once $f;
}
//verify the author key, see if visitor is a validated user.
$taskId = $_POST['i'];
$executerId = $_POST['e'];
$authorKey = $_POST['k'];
$sql = 'SELECT * FROM uatme_oa_workflow_resetpassword WHERE id="'.$taskId.'" AND employee_id="'.$executerId.'" AND author_key="'.$authorKey.'"';
//echo $sql;
$result = $mysqli->query($sql);
//approval start
//if user is validated
if($result->num_rows == 1){
	while($array = $result->fetch_assoc()){
		//based on the task status to deal with the task.
		//1, means normal to go ahead, show reset interface
		//2, means task normally finished, show notice
		//others, means no privilege to access, show forbidden notice
		switch($array['status']){
			case 1:
				$sql = 'UPDATE uatme_oa_workflow_resetpassword SET status="2", author_key="'.md5(sha1($array['author_key'].rand().time().rand())).'", updated_date="'.time().'" WHERE id="'.$array['id'].'"';
				$mysqli->query($sql);
				
				$sql = 'UPDATE uatme_oa_system_employee SET password="'.md5(sha1($_POST['password'])).'" WHERE id="'.$executerId.'"';
				$mysqli->query($sql);
				
				echo '<head>
					<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
					</head>
					<body>
					您已完成该密码的重置，新密码为：'.$_POST['password'].'
					</body>';
				break;
			case 2:
				echo '<head>
					<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
					</head>
					<body>
					您已完成该密码的重置，如要重新设定请在登录页面申请
					</body>';
				break;
			default:
				echo '<head>
					<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
					</head>
					<body>
					您无权访问此页面
					</body>';
				break;
		}	
	}
}else{//if user is illegal
	exit('You are forbidden to access this page, please contact your system administrator!');
}