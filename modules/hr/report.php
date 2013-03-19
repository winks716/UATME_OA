<?php
if($_SESSION['if_hrreport_admin'] == 1){
switch($A){
	case 'get.employee.travel':
		//get status
		$status = array('待审批','已通过','已拒绝');
		//get travel apply
		$sql = 'SELECT * FROM uatme_oa_hr_travel_apply WHERE employee_id="'.$_POST['id'].'"';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			$msg = '<table><tr><th class="span-2">目的地</th><th class="span-8">起始-结束</th><th class="span-2">费用预算<br/>(机票除外)</th><th class="span-2">状态</th><th>事由</th></tr>';
			while($array = $result->fetch_assoc()){
				$msg .= '<tr><td>'.$array['target'].'</td><td>'.$array['start'].' 至 '.$array['end'].'</td><td>'.$array['expense'].'</td><td>'.$status[$array['status']].'</td><td>'.$array['reason'].'</td></tr>';
			}
			$msg .= '</table>';
			$httpstatus = 200;
		}else{
			$msg = '<table><tr><td>无差旅申请记录</td></tr></table>';
			$httpstatus = 200;
		}
		sendResponse($httpstatus, $error, $msg);
		break;
	case 'get.employee.leave':
		//get status
		$status = array('待审批','已通过','已拒绝');
		//get leave type
		$sql = 'SELECT * FROM uatme_oa_hr_leave_type';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$type[$array['id']] = $array;
			}
		}
		//get leave apply
		$sql = 'SELECT * FROM uatme_oa_hr_leave_apply WHERE employee_id="'.$_POST['id'].'"';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			$msg = '<table><tr><th class="span-2">类型</th><th class="span-8">起始-结束</th><th class="span-2">状态</th><th>事由</th></tr>';
			while($array = $result->fetch_assoc()){
				$msg .= '<tr><td>'.$type[$array['type']]['name'].'</td><td>'.$array['start'].' 至 '.$array['end'].'</td><td>'.$status[$array['status']].'</td><td>'.$array['reason'].'</td></tr>';
			}
			$msg .= '</table>';
			$httpstatus = 200;
		}else{
			$msg = '<table><tr><td>无休假申请记录</td></tr></table>';
			$httpstatus = 200;
		}
		sendResponse($httpstatus, $error, $msg);
		break;
	case 'leave.apply.report': 
		//get location
		$sql = 'SELECT * FROM uatme_oa_system_location';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$location[$array['id']] = $array;
			}
		} 
		//get department
		$sql = 'SELECT * FROM uatme_oa_system_department';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$department[$array['id']] = $array;
			}
		} 
		//get employee
		$sql = 'SELECT * FROM uatme_oa_system_employee WHERE id>1';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$employee[$array['id']] = $array;
			}
		}
		//get leave type
		$sql = 'SELECT * FROM uatme_oa_hr_leave_type';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$type[$array['id']] = $array;
			}
		}
		//set status type
		$status = array('待审批','已通过','已拒绝');
		//init data request sql
		$assign['yearSelect'] = $_GET['yearSelect']>0 ? $_GET['yearSelect'] : date('Y');
		$assign['timeSelect'] = $_GET['timeSelect'];
		$assign['typeSelect'] = $_GET['typeSelect'];
		$assign['employeeSelect'] = $_GET['employeeSelect'];
		switch($assign['timeSelect']){
			case '0':
				$yearsql = ' BETWEEN "'.$assign['yearSelect'].'-01-01 00:00:00" AND "'.$assign['yearSelect'].'-12-31 23:59:59"';
				break;
			case '1':
				$yearsql = ' BETWEEN "'.$assign['yearSelect'].'-01-01 00:00:00" AND "'.$assign['yearSelect'].'-03-31 23:59:59"';
				break;
			case '2':
				$yearsql = ' BETWEEN "'.$assign['yearSelect'].'-04-01 00:00:00" AND "'.$assign['yearSelect'].'-06-31 23:59:59"';
				break;
			case '3':
				$yearsql = ' BETWEEN "'.$assign['yearSelect'].'-07-01 00:00:00" AND "'.$assign['yearSelect'].'-09-31 23:59:59"';
				break;
			case '4':
				$yearsql = ' BETWEEN "'.$assign['yearSelect'].'-10-01 00:00:00" AND "'.$assign['yearSelect'].'-12-31 23:59:59"';
				break;
		}
		$typesql = ($assign['typeSelect']>0) ? (' AND type="'.$assign['typeSelect'].'"') : '';
		$employeesql = ($assign['employeeSelect']>0) ? (' AND employee_id="'.$assign['employeeSelect'].'"') : '';
		//get leave apply
		$sql = 'SELECT * FROM uatme_oa_hr_leave_apply WHERE (start '.$yearsql.') AND (end '.$yearsql.')'.$typesql.$employeesql;

		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$apply[] = $array;
				if($array['status']==0 or $array['status']==1){
					//location
					$count[$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name']]++;
					$assign['count']['location_'.$department[$employee[$array['employee_id']]['department_id']]['location_id']] = array('name'=>$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name'], 'count'=>$count[$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name']]);
					//department
					$count[$department[$employee[$array['employee_id']]['department_id']]['name']]++;
					$assign['count']['department_'.$employee[$array['employee_id']]['department_id']] = array('name'=>$department[$employee[$array['employee_id']]['department_id']]['name'], 'count'=>$count[$department[$employee[$array['employee_id']]['department_id']]['name']]);
					//employee
					$count[$employee[$array['employee_id']]['namezh']]['已使用']++;
					$assign['count']['employee_'.$array['employee_id']] = array('name'=>$employee[$array['employee_id']]['namezh'], 'count'=>$count[$employee[$array['employee_id']]['namezh']]['已使用']);
				}
				$count[$employee[$array['employee_id']]['namezh']][$type[$array['type']]['name']][$status[$array['status']]]++;
			}
		}
		$assign['location'] = $location;
		$assign['department'] = $department;
		$assign['employee'] = $employee;
		$assign['type'] = $type;
		$smarty->assign($assign);
		$smarty->display('hr/leave.report.html');
		break;
	case 'travel.apply.report': 
		//get location
		$sql = 'SELECT * FROM uatme_oa_system_location';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$location[$array['id']] = $array;
			}
		} 
		//get department
		$sql = 'SELECT * FROM uatme_oa_system_department';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$department[$array['id']] = $array;
			}
		} 
		//get employee
		$sql = 'SELECT * FROM uatme_oa_system_employee WHERE id>1';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$employee[$array['id']] = $array;
			}
		}
		//get leave type
		$sql = 'SELECT * FROM uatme_oa_hr_leave_type';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$type[$array['id']] = $array;
			}
		}
		//set status type
		$status = array('待审批','已通过','已拒绝');
		//init data request sql
		$assign['yearSelect'] = $_GET['yearSelect']>0 ? $_GET['yearSelect'] : date('Y');
		$assign['timeSelect'] = $_GET['timeSelect'];
		$assign['typeSelect'] = $_GET['typeSelect'];
		$assign['employeeSelect'] = $_GET['employeeSelect'];
		switch($assign['timeSelect']){
			case '0':
				$yearsql = ' BETWEEN "'.$assign['yearSelect'].'-01-01 00:00:00" AND "'.$assign['yearSelect'].'-12-31 23:59:59"';
				break;
			case '1':
				$yearsql = ' BETWEEN "'.$assign['yearSelect'].'-01-01 00:00:00" AND "'.$assign['yearSelect'].'-03-31 23:59:59"';
				break;
			case '2':
				$yearsql = ' BETWEEN "'.$assign['yearSelect'].'-04-01 00:00:00" AND "'.$assign['yearSelect'].'-06-31 23:59:59"';
				break;
			case '3':
				$yearsql = ' BETWEEN "'.$assign['yearSelect'].'-07-01 00:00:00" AND "'.$assign['yearSelect'].'-09-31 23:59:59"';
				break;
			case '4':
				$yearsql = ' BETWEEN "'.$assign['yearSelect'].'-10-01 00:00:00" AND "'.$assign['yearSelect'].'-12-31 23:59:59"';
				break;
		}
		$typesql = ($assign['typeSelect']>0) ? (' AND type="'.$assign['typeSelect'].'"') : '';
		$employeesql = ($assign['employeeSelect']>0) ? (' AND employee_id="'.$assign['employeeSelect'].'"') : '';
		//get travel apply
		$sql = 'SELECT * FROM uatme_oa_hr_travel_apply WHERE (start '.$yearsql.') AND (end '.$yearsql.')'.$typesql.$employeesql;
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$apply[] = $array;
				if($array['status']==0 or $array['status']==1){
					//location
					$count[$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name']]+=$array['expense'];
					$assign['count']['location_'.$department[$employee[$array['employee_id']]['department_id']]['location_id']] = array('name'=>$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name'], 'count'=>$count[$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name']]);
					//department
					$count[$department[$employee[$array['employee_id']]['department_id']]['name']]+=$array['expense'];
					$assign['count']['department_'.$employee[$array['employee_id']]['department_id']] = array('name'=>$department[$employee[$array['employee_id']]['department_id']]['name'], 'count'=>$count[$department[$employee[$array['employee_id']]['department_id']]['name']]);
					//employee
					$count[$employee[$array['employee_id']]['namezh']]['已申请']+=$array['expense'];
					$assign['count']['employee_'.$array['employee_id']] = array('name'=>$employee[$array['employee_id']]['namezh'], 'count'=>$count[$employee[$array['employee_id']]['namezh']]['已申请']);
				}
				$count[$employee[$array['employee_id']]['namezh']][$type[$array['type']]['name']][$status[$array['status']]]+=$array['expense'];
			}
		}
		$assign['location'] = $location;
		$assign['department'] = $department;
		$assign['employee'] = $employee;
		$assign['type'] = $type;
		$smarty->assign($assign);
		$smarty->display('hr/travel.report.html');
		break;
}
}else{
	echo exit('您无权访问此页面!');
}