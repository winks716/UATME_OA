<?php
/*
 * travel apply, approval and etc.
 * 
 * */

switch($A){
	case 'apply':
		$sql = 'SELECT id,name,namezh FROM uatme_oa_system_employee WHERE ifleave=0 AND ifavailable=1 AND id!=1 ORDER BY name';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$assign['employee'][] = $array;
			}
		}
		$smarty->assign($assign);
		$smarty->display('hr/travel.apply.html');
		break;
	case 'submit':
		//save apply to db
		$sql = 'INSERT INTO uatme_oa_hr_travel_apply(id,ls) VALUES ()';
		if($mysqli->query($sql)){
			//init work flow
			//workflowInit($apply_employee_id, $apply_type_english, $apply_id);
			//send mail to the first person
			//mailSendMail($SMTPConfig);
			$httpstatus = 200;
			$msg = '差旅申请成功！请等待批复结果';
		}else{
			$httpstatus = 500;
			$error = '服务器忙，请稍后再试！';
		}
		sendResponse($httpstatus, $error, $msg);
		break;
	case 'agree':
		
		break;
	case 'deny':
		
		break;
	case 'list':
		$smarty->assign($assign);
		$smarty->display('hr/travel.list.html');
		break;
	default:
		
		break;
}