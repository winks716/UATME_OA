<?php

switch($A){
	case 'add':
		$sql = 'INSERT INTO uatme_oa_support_question(title, detail, employee_id, employee_name, create_date) VALUES("'.$_POST['title'].'", "'.$_POST['detail'].'", "'.$_SESSION['employee_id'].'", "'.$_SESSION['employee_name'].'", "'.date('Y-m-d H:i:s').'")';
		if($mysqli->query($sql)){
			$httpstatus = 200;
			$msg = '已成功添加问题';
		}else{
			$httpstatus = 503;
			$error = '服务器忙，请稍候再试';
		}
		sendResponse($httpstatus, $error, $msg);
	break;
}