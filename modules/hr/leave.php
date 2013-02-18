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
			$firstPersonId = workflowInit($_SESSION['employee_id'], 'leave.apply', $mysqli->insert_id);
			//send mail to the first person
			if($firstPersonId > 0){
				$sql = 'SELECT email,namezh FROM uatme_oa_system_employee WHERE id="'.$firstPersonId.'"';
				$result = $mysqli->query($sql);
				if($result->num_rows == 1){
					while($array = $result->fetch_assoc()){
						$SMTPConfig = array(
								'sendto' => array(array('mail'=>$array['email'],'name'=>$array['namezh'])),
								'subject' => '假期申请审批任务',
								'body' => '<p><h3>'.$_SESSION["namezh"].'申请休假。</h3></p>
											<p><h4>申请详情</h4></p>
											<p><table>
												<tr><th>假期类型</th><th>起始-结束</th><th>代办</th><th>事由</th></tr>
												<tr><th>'.$_POST["leaveType"].'</th><th>'.$_POST['leaveStartDate'].' '.$_POST['leaveStartTime'].' 至 '.$_POST['leaveEndDate'].' '.$_POST['leaveEndTime'].'</th><th>'.$_POST['leaveAlter'].'</th><th>'.$_POST['leaveReason'].'</th></tr>
											</table></p>'
								);
						if(mailSendMail($SMTPConfig)){
							$httpstatus = 200;
							$msg = '假期申请成功！请等待批复结果';
						}else{
							$httpstatus = 500;
							$error = '服务器0忙，请稍后再试！';
						}
					}
				}else{
					$httpstatus = 500;
					$error = '服务器1忙，请稍后再试！';
				}
			}else{
				$httpstatus = 500;
				$error = '服务器2忙，请稍后再试！';
			}
		}else{
			$httpstatus = 500;
			$error = '服务器3忙，请稍后再试！';
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