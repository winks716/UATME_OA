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
		//based on the task status to deal with the task.
		//0, means not yet, show forbidden notice
		//1, means normal to approve, show approval interface
		//2, means task normally finished, show notice
		//3, means canceled by applier, show notice
		//4, means canceled by supervisor, show notice
		//others, means no privilege to access, show forbidden notice
		switch($array['status']){
			case 0:
				echo '<head>
					<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
					</head>
					<body>
					您无权访问此页面
					</body>';
				break;
			case 1:
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
					审批备注信息(点击递交后将发送邮件，请耐心等待近5秒，以获得审批结果)
					<br/>
					<textarea name="comment"></textarea>
					<br/>
					<input type="submit" value="递交"/>
					</form>
					</body>';
				break;
			case 2:
				echo '<head>
					<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
					</head>
					<body>
					您已完成该申请的审批
					</body>';
				break;
			case 3:
				$sql = 'UPDATE uatme_oa_workflow_task SET author_key="'.md5(sha1($authorKey.date('Y-m-d H:i:s'))).'" WHERE id="'.$taskId.'" AND employee_id="'.$executerId.'" AND author_key="'.$authorKey.'"';
				$mysqli->query($sql);
				echo '<head>
					<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
					</head>
					<body>
					该申请已被原申请人撤销
					</body>';
				break;
			case 4:
				echo '<head>
					<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
					</head>
					<body>
					该审批已被原申请人上级撤销
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
	exit('<head><meta content="text/html; charset=UTF-8" http-equiv="Content-Type"></head><body>您访问的链接不存在或已失效，请与管理员联系！</body></html>');
}