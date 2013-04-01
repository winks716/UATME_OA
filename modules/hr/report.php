<?php
switch($A){
	case 'get.employee.travel':
		if(!privilegeCheck(array('hr_travel_report_reader'))){
				exit('您无权访问此页面');
		}
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
		if(!privilegeCheck(array('hr_leave_report_reader'))){
				exit('您无权访问此页面');
		}
		//get status
		$status = array('待审批','已通过','已拒绝');
		//get leave type
		$type = basicMysqliQuery('uatme_oa_hr_leave_type');
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
	case 'manager.leave.apply.report': 
		//get location
		$location = basicMysqliQuery('uatme_oa_system_location');
		//get department
		$department = basicMysqliQuery('uatme_oa_system_department','WHERE manager_employee_id="'.$_SESSION['employee_id'].'"');
		foreach($department as $d){
			$departmentarray[] = $d['id'];
		}
		$departmentsql = ' AND department_id IN ('.implode(',',$departmentarray).') ';
		//get employee
		$employee = basicMysqliQuery('uatme_oa_system_employee','WHERE id>1 '.$departmentsql);
		foreach($employee as $d){
			$employeearray[] = $d['id'];
		}
		$employeesql2 = ' AND employee_id IN ('.implode(',',$employeearray).') ';
		//get leave type
		$type = basicMysqliQuery('uatme_oa_hr_leave_type');
		//set status type
		$status = array('待审批','已通过','已拒绝');
		//init data request sql
		$assign['yearSelect'] = $_GET['yearSelect']>0 ? $_GET['yearSelect'] : date('Y');
		$assign['timeSelect'] = $_GET['timeSelect'];
		$assign['typeSelect'] = $_GET['typeSelect'];
		$assign['employeeSelect'] = $_GET['employeeSelect'];
		switch($assign['timeSelect']){
			case '0':
			case '':
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
		$departmentsql = ' AND employee_id';
		//get leave apply
		$sql = ' SELECT * FROM uatme_oa_hr_leave_apply WHERE (start '.$yearsql.') AND (end '.$yearsql.') '.$typesql.$employeesql.$employeesql2;
		echo $sql;
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$apply[] = $array;
				if($array['status']==0 or $array['status']==1){
					//whole company
					$count['whole_company']++;
					$assign['count']['whole_company'] = array('name'=>'全公司', 'count'=>$count['whole_company']);
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
			}
		}
		$assign['location'] = $location;
		$assign['department'] = $department;
		$assign['employee'] = $employee;
		$assign['type'] = $type;
		$smarty->assign($assign);
		$smarty->display('hr/leave.report.html');
		break;
	case 'leave.apply.report': 
		//get location
		$location = basicMysqliQuery('uatme_oa_system_location');
		//get department
		$department = basicMysqliQuery('uatme_oa_system_department');
		//get employee
		$employee = basicMysqliQuery('uatme_oa_system_employee','WHERE id>1');
		//get leave type
		$type = basicMysqliQuery('uatme_oa_hr_leave_type');
		//set status type
		$status = array('待审批','已通过','已拒绝');
		//init data request sql
		$assign['yearSelect'] = $_GET['yearSelect']>0 ? $_GET['yearSelect'] : date('Y');
		$assign['timeSelect'] = $_GET['timeSelect'];
		$assign['typeSelect'] = $_GET['typeSelect'];
		$assign['employeeSelect'] = $_GET['employeeSelect'];
		switch($assign['timeSelect']){
			case '0':
			case '':
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
					$daynumber = caculateDay($array['start'], $array['end']);
					//whole company
					$count['whole_company']+=$daynumber['day'];
					$assign['count']['whole_company'] = array('name'=>'全公司', 'count'=>$count['whole_company']);
					//location
					$count[$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name']]+=$daynumber['day'];
					$assign['count']['location_'.$department[$employee[$array['employee_id']]['department_id']]['location_id']] = array('name'=>$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name'], 'count'=>$count[$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name']]);
					//department
					$count[$department[$employee[$array['employee_id']]['department_id']]['name']]+=$daynumber['day'];
					$assign['count']['department_'.$employee[$array['employee_id']]['department_id']] = array('name'=>$department[$employee[$array['employee_id']]['department_id']]['name'], 'count'=>$count[$department[$employee[$array['employee_id']]['department_id']]['name']]);
					//employee
					$count[$employee[$array['employee_id']]['namezh']]['已使用']+=$daynumber['day'];
					$assign['count']['employee_'.$array['employee_id']] = array('name'=>$employee[$array['employee_id']]['namezh'], 'count'=>$count[$employee[$array['employee_id']]['namezh']]['已使用']);
				}
			}
		}
		$assign['location'] = $location;
		$assign['department'] = $department;
		$assign['employee'] = $employee;
		$assign['type'] = $type;
		$smarty->assign($assign);
		$smarty->display('hr/leave.report.html');
		break;
	case 'manager.travel.apply.report': 
		//get location
		$location = basicMysqliQuery('uatme_oa_system_location');
		//get department
		$department = basicMysqliQuery('uatme_oa_system_department','WHERE manager_employee_id="'.$_SESSION['employee_id'].'"');
		foreach($department as $d){
			$departmentarray[] = $d['id'];
		}
		$departmentsql = ' AND department_id IN ('.implode(',',$departmentarray).') ';
		//get employee
		$employee = basicMysqliQuery('uatme_oa_system_employee','WHERE id>1 '.$departmentsql);
		foreach($employee as $d){
			$employeearray[] = $d['id'];
		}
		$employeesql2 = ' AND employee_id IN ('.implode(',',$employeearray).') ';
		//set status type
		$status = array('待审批','已通过','已拒绝');
		//init data request sql
		$assign['yearSelect'] = $_GET['yearSelect']>0 ? $_GET['yearSelect'] : date('Y');
		$assign['timeSelect'] = $_GET['timeSelect'];
		$assign['typeSelect'] = $_GET['typeSelect'];
		$assign['employeeSelect'] = $_GET['employeeSelect'];
		switch($assign['timeSelect']){
			case '0':
			case '':
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
		$sql = 'SELECT * FROM uatme_oa_hr_travel_apply WHERE (start '.$yearsql.') AND (end '.$yearsql.')'.$typesql.$employeesql.$employeesql2;
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$apply[] = $array;
				if($array['status']==0 or $array['status']==1){
					//whole company
					$count['whole_company']+=$array['expense'];
					$assign['count']['whole_company'] = array('name'=>'全公司','count'=>number_format($count['whole_company'],2));
					//location
					$count[$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name']]+=$array['expense'];
					$assign['count']['location_'.$department[$employee[$array['employee_id']]['department_id']]['location_id']] = array('name'=>$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name'], 'count'=>number_format($count[$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name']],2));
					//department
					$count[$department[$employee[$array['employee_id']]['department_id']]['name']]+=$array['expense'];
					$assign['count']['department_'.$employee[$array['employee_id']]['department_id']] = array('name'=>$department[$employee[$array['employee_id']]['department_id']]['name'], 'count'=>number_format($count[$department[$employee[$array['employee_id']]['department_id']]['name']],2));
					//employee
					$count[$employee[$array['employee_id']]['namezh']]['已申请']+=$array['expense'];
					$assign['count']['employee_'.$array['employee_id']] = array('name'=>$employee[$array['employee_id']]['namezh'], 'count'=>number_format($count[$employee[$array['employee_id']]['namezh']]['已申请'],2));
				}
			}
		}
		$assign['location'] = $location;
		$assign['department'] = $department;
		$assign['employee'] = $employee;
		$smarty->assign($assign);
		$smarty->display('hr/travel.report.html');
		break;
	case 'travel.apply.report': 
		//get location
		$location = basicMysqliQuery('uatme_oa_system_location');
		//get department
		$department = basicMysqliQuery('uatme_oa_system_department');
		//get employee
		$employee = basicMysqliQuery('uatme_oa_system_employee','WHERE id>1');
		//set status type
		$status = array('待审批','已通过','已拒绝');
		//init data request sql
		$assign['yearSelect'] = $_GET['yearSelect']>0 ? $_GET['yearSelect'] : date('Y');
		$assign['timeSelect'] = $_GET['timeSelect'];
		$assign['typeSelect'] = $_GET['typeSelect'];
		$assign['employeeSelect'] = $_GET['employeeSelect'];
		switch($assign['timeSelect']){
			case '0':
			case '':
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
					//whole company
					$count['whole_company']+=$array['expense'];
					$assign['count']['whole_company'] = array('name'=>'全公司','count'=>number_format($count['whole_company'],2));
					//location
					$count[$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name']]+=$array['expense'];
					$assign['count']['location_'.$department[$employee[$array['employee_id']]['department_id']]['location_id']] = array('name'=>$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name'], 'count'=>number_format($count[$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name']],2));
					//department
					$count[$department[$employee[$array['employee_id']]['department_id']]['name']]+=$array['expense'];
					$assign['count']['department_'.$employee[$array['employee_id']]['department_id']] = array('name'=>$department[$employee[$array['employee_id']]['department_id']]['name'], 'count'=>number_format($count[$department[$employee[$array['employee_id']]['department_id']]['name']],2));
					//employee
					$count[$employee[$array['employee_id']]['namezh']]['已申请']+=$array['expense'];
					$assign['count']['employee_'.$array['employee_id']] = array('name'=>$employee[$array['employee_id']]['namezh'], 'count'=>number_format($count[$employee[$array['employee_id']]['namezh']]['已申请'],2));
				}
			}
		}
		$assign['location'] = $location;
		$assign['department'] = $department;
		$assign['employee'] = $employee;
		$smarty->assign($assign);
		$smarty->display('hr/travel.report.html');
		break;
}