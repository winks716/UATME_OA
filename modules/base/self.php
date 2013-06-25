<?php
switch($A){
	case 'task.count':
		$taskcount = 0;
		$httpstatus = 200;
		$sql = 'SELECT COUNT(*) as taskcount FROM uatme_oa_workflow_task WHERE employee_id="'.$_SESSION['employee_id'].'" AND status=1 GROUP BY employee_id';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$taskcount = $array['taskcount'];
			}
		}
		sendResponse($httpstatus, $error, $taskcount);
		break;
	case 'task.list':
		//get employee information
		$employee = basicMysqliQuery('uatme_oa_system_employee');
		//get task type
		$taskType = basicMysqliQuery('uatme_oa_workflow_document_typelv1');
		//get task list
		$sql = 'SELECT * FROM uatme_oa_workflow_task WHERE employee_id="'.$_SESSION['employee_id'].'" AND status=1';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$array['document_typelv1_name'] = $taskType[$array['document_typelv1_id']]['name'];
				$apply = basicMysqliQuery($taskType[$array['document_typelv1_id']]['db_table_name'],' WHERE id="'.$array['document_id'].'" ');
				$array['reason'] = $apply[$array['document_id']]['reason'];
				$array['period'] = $apply[$array['document_id']]['start'].'~'.$apply[$array['document_id']]['end'];
				$array['apply_date'] = $apply[$array['document_id']]['apply_date'];
				$array['apply_employee'] = $employee[$apply[$array['document_id']]['employee_id']]['namezh'].' ('.$employee[$apply[$array['document_id']]['employee_id']]['name'].')';
				$array['apply_alternative'] = $employee[$apply[$array['document_id']]['alternative_employee_id']]['namezh'].' ('.$employee[$apply[$array['document_id']]['alternative_employee_id']]['name'].')';
				$array['document_typelv1_nameen'] = $taskType[$array['document_typelv1_id']]['name_english'];
				$assign['task'][] = $array;
			}
		}
		$assign['WEBSERVER'] = WEBSERVER;
		$smarty->assign($assign);
		$smarty->display('base/self.task.list.html');
		break;
	case 'edit':
		//all employee
		$sql = 'SELECT * FROM uatme_oa_system_employee WHERE id="'.$_SESSION['employee_id'].'"';
		$result = $mysqli->query($sql);
		if($result->num_rows == 1){
			while($array = $result->fetch_assoc()){
				$assign['employee'] = $array;
			}
		}
		//all department
		$sql = 'SELECT * FROM uatme_oa_system_department WHERE id="'.$assign['employee']['department_id'].'"';
		$result = $mysqli->query($sql);
		if($result->num_rows == 1){
			while($array = $result->fetch_assoc()){
				$assign['employee']['department_name'] = $array['name'];
			}
		}
		//totally how many annual leave available (including used or unused)
		$sql = 'SELECT * FROM uatme_oa_hr_leave_employee WHERE leave_type_id=(SELECT id FROM uatme_oa_hr_leave_type WHERE name="年假" LIMIT 1) AND ifavailable=1 AND employee_id="'.$_SESSION['employee_id'].'"';
		$result = $mysqli->query($sql);
		if($result->num_rows == 1){
			while($array = $result->fetch_assoc()){
				$assign['employee']['total_annualleave'] = $array['count'];
			}
		}
		//has used annual leave
		$sql = 'SELECT start,end FROM uatme_oa_hr_leave_apply WHERE employee_id="'.$_SESSION['employee_id'].'" AND status=1 AND ((start BETWEEN "'.Date('Y').'-01-01 00:00:00" AND "'.Date('Y').'-12-31 23:59:59") OR (end BETWEEN "'.Date('Y').'-01-01 00:00:00" AND "'.Date('Y').'-12-31 23:59:59")) AND type=(SELECT id FROM uatme_oa_hr_leave_type WHERE name="年假" LIMIT 1)';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$daynumber = caculateDay($array['start'], $array['end']);
				$assign['employee']['used_annualleave'] += $daynumber['day'];
			}
		}
		$smarty->assign($assign);
		$smarty->display('base/self.edit.html');
	break;
	case 'save':
		//echo "{httpstatus:500}";
		$sql = 'UPDATE uatme_oa_system_employee SET password="'.md5(sha1($_POST['newPassword'])).'" WHERE id="'.$_SESSION['employee_id'].'" and password="'.md5(sha1($_POST['oldPassword'])).'"';
		if($result = $mysqli->query($sql)){
			$httpstatus = 200;
		}else{
			$httpstatus = 500;
		}
	break;
	case 'reset.password':
	    $email = $_POST['email'].'@'.COMPANY_DOMAIN;
	    $name = $_POST['email'];
	    
	    $employee = basicMysqliQuery('uatme_oa_system_employee', ' WHERE email="'.$email.'" ');
	    if(count($employee) == 1){
	        foreach($employee as $employee_id => $e){
	            $lastrequest = basicMysqliQuery('uatme_oa_workflow_resetpassword', ' WHERE employee_id="'.$employee_id.'" AND created_date >= "'.(time()-1800).'" ');
	            if(count($lastrequest) >= 1){
	                $httpstatus = 500;
	                $error = '30分钟内请勿多次递交申请！';
	            }else{
	                $time = time();
	                $token = md5(sha1(rand().$employee_id.$time.rand()));
    	            $mysqli->query('INSERT INTO  uatme_oa_workflow_resetpassword(employee_id, author_key, created_date) VALUES("'.$employee_id.'","'.$token.'", "'.$time.'")');
    	            $task_id = $mysqli->insert_id;
    	            $SMTPConfig = array(
    	                    'sendto' => array(array('mail'=>$email,'name'=>$e['namezh'])),
    	                    'subject' => '密码重置',
    	                    'body' => '<head>
        					<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
        					<style>
        					.clickbtn{color:blue;cursor:pointer;}
        					</style>
        					</head>
        					<body>
        					<h4>'.$e['name'].'('.$e['namezh'].'),<br/>请点击以下链接来重置您的密码，若链接失效请复制链接到您的浏览器</h4>
        					<p>
                                <a class="clickbtn" href="'.WEBSERVER.'api/before.resetpassword.php?e='.$employee_id.'&i='.$task_id.'&k='.$token.'">'.WEBSERVER.'api/before.resetpassword.php?e='.$employee_id.'&i='.$task_id.'&k='.$token.'</a>
        					</p>
        					</body>
        					'
    	            );
    	            mailSendMail($SMTPConfig);
    	            $httpstatus = 200;
    	            $msg = '密码重置申请已递交！请前去邮箱收取密码重置邮件,30分钟内有效';
	            }
	        }   
	    }else{
	        $httpstatus = 500;
	        $error = '邮箱验证失败，请重新填写！';
	    }
	    sendResponse($httpstatus, $error, $msg);
	    break;
}