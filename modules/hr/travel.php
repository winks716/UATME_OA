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
		$sql = 'INSERT INTO uatme_oa_hr_travel_apply(target, start, end, reason, currency_id, expense, employee_id, alternative_employee_id, apply_date) 
				 VALUES ("'.$_POST['travelTarget'].'", "'.$_POST['travelStartDate'].' '.$_POST['travelStartTime'].'", "'.$_POST['travelEndDate'].' '.$_POST['travelEndTime'].'", "'.$_POST['travelReason'].'", "'.$_POST['travelCurrencyId'].'", "'.$_POST['travelExpense'].'", "'.$_SESSION['employee_id'].'", "'.$_POST['travelAlter'].'", "'.Date('Y-m-d H:i:s').'")';
		if($mysqli->query($sql)){
			//init work flow
			$task = workflowInit($_SESSION['employee_id'], 'travel.apply', $mysqli->insert_id);
			//send mail to the first person
			if($task['executerId'] > 0){
				// get all currency type
				$sql = 'SELECT id,name FROM uatme_oa_system_currency';
				$result = $mysqli->query($sql);
				if($result->num_rows > 0){
					while($array = $result->fetch_assoc()){
						$currency[$array['id']] = $array['name'];
					}
				}
				// get all employee
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
								'subject' => '新的差旅申请',
								'body' => '<head>
											<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
											<style>
											.clickbtn{color:blue;cursor:pointer;}
											</style>
											</head>
											<body>
											<h3>'.$_SESSION['employee_namezh'].'('.$_SESSION['employee_name'].') 申请出差。</h3><br/>
											<h4>详情如下</h4>
											<p><table>
												<tr><th>目的地</th><th>出差时间</th><th>费用</th><th>事由</th><th>指定代办</th></tr>
												<tr><td>'.$_POST["travelTarget"].'</td><td>'.$_POST['travelStartDate'].' '.$_POST['travelStartTime'].' 至 '.$_POST['travelEndDate'].' '.$_POST['travelEndTime'].'</td><td>'.$currency[$_POST['travelCurrencyId']].$_POST['travelExpense'].'</td><td>'.$_POST['travelReason'].'</td><td>'.$employee[$_POST['travelAlter']]['namezh'].'('.$employee[$_POST['travelAlter']]['name'].')</td></tr>
											</table></p>
											<p><a class="clickbtn" href="'.WEBSERVER.'api/before.approval.php?s=travel&a=agree&e='.$task['executerId'].'&i='.$task['taskId'].'&k='.$task['authorKey'].'">[同意]</a>
											&nbsp;&nbsp;&nbsp;&nbsp;<a class="clickbtn" href="'.WEBSERVER.'api/before.approval.php?s=travel&a=deny&e='.$task['executerId'].'&i='.$task['taskId'].'&k='.$task['authorKey'].'">[拒绝]</a></p>
											</body>											
											'
								);
						mailSendMail($SMTPConfig);
						$httpstatus = 200;
						$msg = '差旅申请成功！请等待批复结果';
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
				$employee[$array['id']] = $array['namezh'].' - '.$array['name'];
			}
		}
		// get all currency type
		$sql = 'SELECT id,name FROM uatme_oa_system_currency';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$currency[$array['id']] = $array['name'];
			}
		}
		$sql = 'SELECT * FROM uatme_oa_hr_travel_apply WHERE employee_id="'.$_SESSION['employee_id'].'"';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$array['currencyName'] = $currency[$array['currency_id']];
				$array['applyer'] = $employee[$array['employee_id']];
				$array['alter'] = $employee[$array['alternative_employee_id']];
				$array['status'] = $assign['status'][$array['status']];
				$assign['apply'][] = $array;
			}
		}
		$smarty->assign($assign);
		$smarty->display('hr/travel.list.html');
		break;
	default:
		
		break;
}