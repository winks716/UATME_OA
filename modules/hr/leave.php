<?php
/*
 * leave apply, approval and etc.
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
		$smarty->display('hr/leave.apply.html');
		break;
	case 'submit':
		//save apply to db
		$sql = 'INSERT INTO uatme_oa_hr_leave_apply(type, start, end, reason, employee_id, alternative_employee_id) 
				 VALUES ("'.$_POST['leaveType'].'", "'.$_POST['leaveStartDate'].' '.$_POST['leaveStartTime'].'", "'.$_POST['leaveEndDate'].' '.$_POST['leaveEndTime'].'", "'.$_POST['leaveReason'].'", "'.$_SESSION['employee_id'].'", "'.$_POST['leaveAlter'].'")';
		if($mysqli->query($sql)){
			//init work flow
			workflowInit($_SESSION['employee_id'], 'leave.apply', $mysqli->insert_id);
			//send mail to the first person
			//mailSendMail($SMTPConfig);
			$httpstatus = 200;
			$msg = '假期申请成功！请等待批复结果';
		}else{
			$httpstatus = 503;
			$error = '服务器忙，请稍后再试！';
		}
		sendResponse($httpstatus, $error, $msg);
		break;
	case 'agree':
		
		break;
	case 'deny':
		
		break;
	case 'list':
		$assign['status'] = array(
			0 => '审批中',
			1 => '通过',
			2 => '拒绝'
		);
		$sql = 'SELECT id,name,namezh FROM uatme_oa_system_employee';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$assign['employee'][$array['id']] = $array['namezh'].' - '.$array['name'];
			}
		}
		$sql = 'SELECT id,name FROM uatme_oa_hr_leave_type';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$assign['type'][$array['id']] = $array['name'];
			}
		}
		$sql = 'SELECT * FROM uatme_oa_hr_leave_apply WHERE employee_id="'.$_SESSION['employee_id'].'"';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$array['type'] = $assign['type'][$array['type']];
				$array['applyer'] = $assign['employee'][$array['employee_id']];
				$array['alter'] = $assign['employee'][$array['alternative_employee_id']];
				$array['status'] = $assign['status'][$array['status']];
				$assign['apply'][] = $array;
			}
		}
		$smarty->assign($assign);
		$smarty->display('hr/leave.list.html');
		break;
	default:
		
		break;
}