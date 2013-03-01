<?php
//include db config and connect database
require_once('../db.config.php');

//verify the author key, see if visitor is a validated user.
$taskId = $_GET['i'];
$executerId = $_GET['e'];
$authorKey = $_GET['k'];
$sql = 'SELECT * FROM uatme_oa_workflow_task WHERE id="'.$taskId.'" AND employee_id="'.$executerId.'" AND author_key="'.$authorKey.'"';

$result = $mysqli->query($sql);
//approval start
//if user is validated
if($result->num_rows == 1){
	while($array = $result->fetch_assoc()){
		echo '<head>
				<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
				</head>
				<body>
				<form action="./approval.php" method="post">
				<input type="hidden" name="s" value="'.$_GET['s'].'"/>
				<input type="hidden" name="a" value="'.$_GET['a'].'"/>
				<input type="hidden" name="i" value="'.$_GET['i'].'"/>
				<input type="hidden" name="e" value="'.$_GET['e'].'"/>
				<input type="hidden" name="k" value="'.$_GET['k'].'"/>
				审批备注信息(点击递交后将发送邮件，请耐心等待近12秒，以获得审批结果)
				<br/>
				<textarea name="comment"></textarea>
				<br/>
				<input type="submit" value="递交"/>
				</form>
				</body>';
	}
}else{//if user is illegal
	exit('You are forbidden to access this page, please contact your system administrator!');
}