<?php
/*
 * overtime apply, approval and etc.
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
		$smarty->display('hr/overtime.apply.html');
		break;
	case 'submit':
		//save apply to db
		$sql = 'INSERT INTO uatme_oa_hr_overtime_apply(date, period, reason, location, employee_id, copy_employee_id, apply_date) 
				 VALUES ("'.$_POST['overtimeDate'].'", "'.$_POST['overtimePeriod'].'", "'.$_POST['overtimeReason'].'", "'.$_POST['overtimeLocation'].'", "'.$_SESSION['employee_id'].'", "'.implode(',', $_POST['overtimeCopy']).'", "'.Date('Y-m-d H:i:s').'")';
		if($mysqli->query($sql)){
			//init work flow
			$task = workflowInit($_SESSION['employee_id'], 'overtime.apply', $mysqli->insert_id);
			//send mail to the first person
			if($task['executerId'] > 0){
				//
				$sql = 'SELECT id,name,namezh FROM uatme_oa_system_employee';
				$result = $mysqli->query($sql);
				if($result->num_rows > 0){
					while($array = $result->fetch_assoc()){
						$employee[$array['id']] = array('name'=>$array['name'],'namezh'=>$array['namezh']); 
					}
				}
				$sql = 'SELECT email,name FROM uatme_oa_system_employee WHERE id="'.$task['executerId'].'"';
				$result = $mysqli->query($sql);
				if($result->num_rows == 1){
					while($array = $result->fetch_assoc()){
						$SMTPConfig = array(
								'sendto' => array(array('mail'=>$array['email'],'name'=>$array['name'])),
								'subject' => '新的加班申请',
								'body' => '<head>
											<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
											<style>
											.clickbtn{color:blue;cursor:pointer;}
											</style>
											</head>
											<body>
											<h3>'.$_SESSION['employee_namezh'].'('.$_SESSION['employee_name'].') 申请加班。</h3><br/>
											<h4>详情如下</h4>
											<p><table>
												<tr><th>日期</th><th>时长</th><th>地点</th><th>事由</th></tr>
												<tr><td>'.$_POST['overtimeDate'].'</td><td>'.$_POST['overtimePeriod'].'</td><td>'.$_POST['overtimeLocation'].'</td><td>'.$_POST['overtimeReason'].'</td></tr>
											</table></p>
											<p><a class="clickbtn" href="'.WEBSERVER.'api/before.approval.php?s=overtime.apply&a=agree&e='.$task['executerId'].'&i='.$task['taskId'].'&k='.$task['authorKey'].'">[同意]</a>
											&nbsp;&nbsp;&nbsp;&nbsp;<a class="clickbtn" href="'.WEBSERVER.'api/before.approval.php?s=overtime.apply&a=deny&e='.$task['executerId'].'&i='.$task['taskId'].'&k='.$task['authorKey'].'">[拒绝]</a></p>
											</body>											
											'
								);
						mailSendMail($SMTPConfig);
						$httpstatus = 200;
						$msg = '加班申请成功！请等待批复结果';
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
	case 'applier.cancel':
		$sql = 'UPDATE uatme_oa_hr_overtime_apply SET status=3, comment="本人撤销" WHERE id="'.$_POST['id'].'" AND employee_id="'.$_SESSION['employee_id'].'"';
		if($mysqli->query($sql)){
			$httpstatus = 200;
			$msg = '此申请已成功撤销';
			//delete related task db information
			$applyType = basicMysqliQuery('uatme_oa_workflow_document_typelv1', ' WHERE name_english="overtime.apply" ');
			foreach($applyType as $k => $t){
				$sql = 'UPDATE uatme_oa_workflow_task SET status="3", comment="本人撤销", updated_date="'.date('Y-m-d H:i:s').'" WHERE document_typelv1_id="'.$k.'" AND document_id="'.$_POST['id'].'" AND status IN (0,1)';
				$mysqli->query($sql);
			}
		}else{
			$httpstatus = 500;
			$error = '申请撤销：服务器0忙，请稍后再试，谢谢！';
		}
		sendResponse($httpstatus, $error, $msg);
		break;
	case 'manager.cancel':
	    //check if operator is the supervisor of applier
	    $sql = 'SELECT id FROM uatme_oa_system_department  
	            WHERE id=(SELECT department_id FROM uatme_oa_system_employee WHERE id="'.$_POST['eid'].'")  
	                    AND manager_employee_id="'.$_SESSION['employee_id'].'"';
	    $result = $mysqli->query($sql);
	    //if check ok, go cancel
	    if($result->num_rows > 0){
	        $sql = 'UPDATE uatme_oa_hr_overtime_apply SET status=4, comment="上级撤销" WHERE id="'.$_POST['id'].'" AND employee_id="'.$_POST['eid'].'"';
	        if($mysqli->query($sql)){
	            $httpstatus = 200;
	            $msg = '此申请已成功撤销';
	        }else{
	            $httpstatus = 500;
	            $error = '申请撤销：服务器0忙，请稍后再试，谢谢！';
	        }
	    }else{
	        $httpstatus = 500;
	        $error = '申请撤销：服务器1忙，请稍后再试，谢谢！';
	    }
		sendResponse($httpstatus, $error, $msg);
		break;
	case 'hr.cancel':
	    $sql = 'UPDATE uatme_oa_hr_overtime_apply SET status=4, comment="上级撤销" WHERE id="'.$_POST['id'].'" AND employee_id="'.$_POST['eid'].'"';
        if($mysqli->query($sql)){
            $httpstatus = 200;
            $msg = '此申请已成功撤销';
        }else{
            $httpstatus = 500;
            $error = '申请撤销：服务器0忙，请稍后再试，谢谢！';
        }
		sendResponse($httpstatus, $error, $msg);
	    break;
	/*case 'hr.update.overtime.type':
	    $sql = 'UPDATE uatme_oa_hr_overtime_apply SET type="'.$_POST['type'].'" WHERE id="'.$_POST['id'].'" AND employee_id="'.$_POST['eid'].'"';
        if($mysqli->query($sql)){
            $httpstatus = 200;
            $msg = '更新加班类型已成功';
        }else{
            $httpstatus = 500;
            $error = '服务器1忙，请稍后再试，谢谢！';
        }
		sendResponse($httpstatus, $error, $msg);
		break;*/
	case 'list':
		//get apply status definition list
		$assign['status'] = basicMysqliQuery('uatme_oa_system_apply_status');
		//get employee
		$sql = 'SELECT id,name,namezh FROM uatme_oa_system_employee';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$assign['employee'][$array['id']] = $array['namezh'].' - '.$array['name'];
			}
		}
		$sql = 'SELECT * FROM uatme_oa_hr_overtime_apply WHERE employee_id="'.$_SESSION['employee_id'].'"';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$array['applyer'] = $assign['employee'][$array['employee_id']];
				$array['statusZH'] = $assign['status'][$array['status']]['namezh'];
				$assign['apply'][] = $array;
			}
		}
		$smarty->assign($assign);
		$smarty->display('hr/overtime.list.html');
		break;
	default:
		
		break;
}