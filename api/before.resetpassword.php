<?php
//include db config and connect database
require_once('../db.config.php');

//verify the author key, see if visitor is a validated user.
$taskId = $_GET['i'];
$executerId = $_GET['e'];
$authorKey = $_GET['k'];
$sql = 'SELECT * FROM uatme_oa_workflow_resetpassword WHERE id="'.$taskId.'" AND employee_id="'.$executerId.'" AND author_key="'.$authorKey.'"';

$result = $mysqli->query($sql);
//approval start
//if user is validated
if($result->num_rows == 1){
	while($array = $result->fetch_assoc()){
		//based on the task status to deal with the task.
		//1, means normal to approve, show approval interface
		//2, means task normally finished, show notice
		//others, means no privilege to access, show forbidden notice
		switch($array['status']){
			case 1:
				echo '<head>
					<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
					</head>
					<body>
					<form id="resetpasswordform" action="./resetpassword.php" method="post">
					<input type="hidden" name="i" value="'.$_GET['i'].'"/>
					<input type="hidden" name="e" value="'.$_GET['e'].'"/>
					<input type="hidden" name="k" value="'.$_GET['k'].'"/>
					新密码<input name="password" id="password" type="password"/>
					<br/>
					重复新密码<input id="repassword" type="password"/>
					<br/>
					<br/>
					<script>
					    function checkPassword(){
					        if(document.getElementById("password").value != document.getElementById("repassword").value){
					            alert("两次密码输入不一致，请检查！");
		                    }else{
					            document.getElementById("resetpasswordform").submit();
		                    }
					    }
					</script>
					<span style="color:blue;cursor:pointer;" onClick="checkPassword()">[递交]</span>
					</form>
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
	exit('<head><meta content="text/html; charset=UTF-8" http-equiv="Content-Type"></head><body>您访问的链接不存在或已失效，请重新申请或与管理员联系！</body></html>');
}