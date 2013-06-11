<?php
switch($A){
	case 'get.employee.travel':
		//get status
		$status = basicMysqliQuery('uatme_oa_system_apply_status');
		//get travel apply
		$sql = 'SELECT * FROM uatme_oa_hr_travel_apply WHERE employee_id="'.$_POST['id'].'"';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			$msg = '<table><tr><th class="span-2">目的地</th><th class="span-5">起始-结束</th><th class="span-2">费用预算<br/>(机票除外)</th><th class="span-2">状态</th><th>事由</th></tr>';
			while($array = $result->fetch_assoc()){
				$msg .= '<tr><td>'.$array['target'].'</td><td>'.$array['start'].'<br/>至 '.$array['end'].'</td><td>'.$array['expense'].'</td><td>'.$status[$array['status']]['namezh'].'</td><td>'.$array['reason'].'</td></tr>';
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
		$status = basicMysqliQuery('uatme_oa_system_apply_status');
		//get leave type
		$type = basicMysqliQuery('uatme_oa_hr_leave_type');
		//get leave apply
		$sql = 'SELECT * FROM uatme_oa_hr_leave_apply WHERE employee_id="'.$_POST['id'].'"';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			$msg = '<table><tr><th class="span-2">类型</th><th class="span-5">起始-结束</th><th class="span-2">状态</th><th>事由</th></tr>';
			while($array = $result->fetch_assoc()){
				$msg .= '<tr><td>'.$type[$array['type']]['name'].'</td><td>'.$array['start'].'<br/>至 '.$array['end'].'</td><td>'.$status[$array['status']]['namezh'].'</td><td>'.$array['reason'].'</td></tr>';
			}
			$msg .= '</table>';
			$httpstatus = 200;
		}else{
			$msg = '<table><tr><td>无休假申请记录</td></tr></table>';
			$httpstatus = 200;
		}
		sendResponse($httpstatus, $error, $msg);
		break;
	case 'hr.get.employee.travel':
		//get status
		$status = basicMysqliQuery('uatme_oa_system_apply_status');
		//get travel apply
		$sql = 'SELECT * FROM uatme_oa_hr_travel_apply WHERE employee_id="'.$_POST['id'].'"';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			$msg = '<table><tr><th class="span-2">目的地</th><th class="span-5">起始-结束</th><th class="span-2">费用预算<br/>(机票除外)</th><th class="span-4">状态</th><th>事由</th></tr>';
			while($array = $result->fetch_assoc()){
				$msg .= '<tr><td>'.$array['target'].'</td><td>'.$array['start'].'<br/>至 '.$array['end'].'</td><td>'.$array['expense'].'</td><td>'.$status[$array['status']]['namezh'].($array['status']==1 ?'<span class="clickbtn cancelApply" i="'.$array['id'].'" ei="'.$_POST['id'].'">[撤销]</span>':'').'</td><td>'.$array['reason'].'</td></tr>';
			}
			$msg .= '</table>';
			$httpstatus = 200;
		}else{
			$msg = '<table><tr><td>无差旅申请记录</td></tr></table>';
			$httpstatus = 200;
		}
		sendResponse($httpstatus, $error, $msg);
		break;
	case 'hr.get.employee.leave':
		//get status
		$status = basicMysqliQuery('uatme_oa_system_apply_status');
		//get leave type
		$type = basicMysqliQuery('uatme_oa_hr_leave_type');
		//get leave apply
		$sql = 'SELECT * FROM uatme_oa_hr_leave_apply WHERE employee_id="'.$_POST['id'].'"';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			$msg = '<table><tr><th class="span-4">类型</th><th class="span-5">起始-结束</th><th class="span-3">状态</th><th>事由</th></tr>';
			while($array = $result->fetch_assoc()){
				$msg .= '<tr><td><select>';
				foreach($type as $k=>$t){
					$msg .= '<option value="'.$k.'"'.($k==$array['type']?' selected':'').'>'.$t['name'].'</option>';
				}
				$msg .= '</select><span class="clickbtn updateLeaveType" i="'.$array['id'].'" ei="'.$_POST['id'].'">[保存]</span></td><td>'.$array['start'].'<br/>至 '.$array['end'].'</td><td>'.$status[$array['status']]['namezh'].($array['status']==1 ?'<span class="clickbtn cancelApply" i="'.$array['id'].'" ei="'.$_POST['id'].'">[撤销]</span>':'').'</td><td>'.$array['reason'].'</td></tr>';
			}
			$msg .= '</table>';
			$httpstatus = 200;
		}else{
			$msg = '<table><tr><td>无休假申请记录</td></tr></table>';
			$httpstatus = 200;
		}
		sendResponse($httpstatus, $error, $msg);
		break;
	case 'leave.apply.manager.report':
		//get location
		$location = basicMysqliQuery('uatme_oa_system_location');
		//get department
		$department = basicMysqliQuery('uatme_oa_system_department','WHERE manager_employee_id="'.$_SESSION['employee_id'].'"');
		$department = getOwnedDepartment($department);

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
		$status = basicMysqliQuery('uatme_oa_system_apply_status');
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
		$sql = 'SELECT * FROM uatme_oa_hr_leave_apply WHERE (start '.$yearsql.') AND (end '.$yearsql.')'.$typesql.$employeesql.$employeesql2;
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$apply[] = $array;
				if($array['status']==0 or $array['status']==1){
					$daynumber = caculateDay($array['start'], $array['end']);
					//echo 'department_'.$employee[$array['employee_id']]['department_id'].'::'.$department[$employee[$array['employee_id']]['department_id']]['name'].'::'.$daynumber['day'].'----';
					//whole company
					//$count['whole_company']+=$daynumber['day'];
					//$assign['count']['whole_company'] = array('name'=>'全公司', 'count'=>$count['whole_company']);
					//location
					//$count[$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name']]+=$daynumber['day'];
					//$assign['count']['location_'.$department[$employee[$array['employee_id']]['department_id']]['location_id']] = array('name'=>$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name'], 'count'=>$count[$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name']]);
					//department
					$count[$department[$employee[$array['employee_id']]['department_id']]['name']]+=$daynumber['day'];
					$assign['count']['department_'.$employee[$array['employee_id']]['department_id']] = array('name'=>$department[$employee[$array['employee_id']]['department_id']]['name'], 'count'=>$count[$department[$employee[$array['employee_id']]['department_id']]['name']]);
					//employee
					$count[$employee[$array['employee_id']]['namezh']]['已使用']+=$daynumber['day'];
					$assign['count']['employee_'.$array['employee_id']] = array('name'=>$employee[$array['employee_id']]['namezh'], 'count'=>$count[$employee[$array['employee_id']]['namezh']]['已使用']);
				}
			}
		}
		//$assign['location'] = $location;
		$assign['department'] = $department;
		$assign['employee'] = $employee;
		$assign['type'] = $type;
		$smarty->assign($assign);
		$smarty->display('hr/leave.manager.report.html');
		break;
	case 'leave.apply.manager.report.export':
		//get location
		$location = basicMysqliQuery('uatme_oa_system_location');
		//get department
		$department = basicMysqliQuery('uatme_oa_system_department','WHERE manager_employee_id="'.$_SESSION['employee_id'].'"');
		$department = getOwnedDepartment($department);
		
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
		$status = basicMysqliQuery('uatme_oa_system_apply_status');
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
		$sql = 'SELECT * FROM uatme_oa_hr_leave_apply WHERE (start '.$yearsql.') AND (end '.$yearsql.')'.$typesql.$employeesql.$employeesql2;
		$result = $mysqli->query($sql);
		
		
		//INIT EXCEL title, attribute, etc.
		$properties = array('filename'=>'已用休假('.$type[$assign['typeSelect']]['name'].')报表');
		$data = array(
		        array(
		                'title'=>'休假申请',
		                'data'=>array()
		        ),
		        array(
		                'title'=>'已用休假('.$type[$assign['typeSelect']]['name'].')合计',
		                'data'=>array()
		        )
		);
		$data[0]['data'][] = array('('.$type[$assign['typeSelect']]['name'].')休假申请报表('.$yearsql.')');
		$data[0]['data'][] = array('姓名', '开始日期', '结束日期','事由', '指定代办', '审批结果');
		$data[1]['data'][] = array('已用休假('.$type[$assign['typeSelect']]['name'].')合计报表('.$yearsql.')');
		$data[1]['data'][] = array('姓名', '合计(天)');
		
		if($result->num_rows > 0){
		    while($array = $result->fetch_assoc()){
		        $data[0]['data'][] = array(
		                $employee[$array['employee_id']]['namezh'].' ('.$employee[$array['employee_id']]['name'].') ',
		                $array['start'],
		                $array['end'],
		                $array['reason'],
		                $employee[$array['alternative_employee_id']],
		                $status[$array['status']]['namezh']
		        );
		
		        if($array['status']==0 or $array['status']==1){
		            $daynumber = caculateDay($array['start'], $array['end']);
		            //employee
		            $count[$employee[$array['employee_id']]['namezh']]+=$daynumber['day'];
		        }
		    }
		}
		
		foreach($count as $k => $v){
		    $data[1]['data'][] = array($k, $v);
		}
		//print_r($count);
		//print_r($data);
		downloadExcel($properties, $data);
		break;
	case 'leave.apply.hr.report': 
		//get location
		$location = basicMysqliQuery('uatme_oa_system_location');
		//get department
		$department = basicMysqliQuery('uatme_oa_system_department');
		//get employee
		$employee = basicMysqliQuery('uatme_oa_system_employee','WHERE id>1');
		//get leave type
		$type = basicMysqliQuery('uatme_oa_hr_leave_type');
		//set status type
		$status = basicMysqliQuery('uatme_oa_system_apply_status');
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
		$smarty->display('hr/leave.hr.report.html');
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
		$status = basicMysqliQuery('uatme_oa_system_apply_status');
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
	case 'leave.apply.report.export': 
		//get location
		$location = basicMysqliQuery('uatme_oa_system_location');
		//get department
		$department = basicMysqliQuery('uatme_oa_system_department');
		//get employee
		$employee = basicMysqliQuery('uatme_oa_system_employee','WHERE id>1');
		//get leave type
		$type = basicMysqliQuery('uatme_oa_hr_leave_type');
		//set status type
		$status = basicMysqliQuery('uatme_oa_system_apply_status');
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
		
		
		
		//INIT EXCEL title, attribute, etc.
		$properties = array('filename'=>'已用休假('.$type[$assign['typeSelect']]['name'].')报表');
		$data = array(
		        array(
		                'title'=>'休假申请',
		                'data'=>array()
		        ),
		        array(
		                'title'=>'已用休假('.$type[$assign['typeSelect']]['name'].')合计',
		                'data'=>array()
		        )
		);
		$data[0]['data'][] = array('('.$type[$assign['typeSelect']]['name'].')休假申请报表('.$yearsql.')');
		$data[0]['data'][] = array('姓名', '开始日期', '结束日期','事由', '指定代办', '审批结果');
		$data[1]['data'][] = array('已用休假('.$type[$assign['typeSelect']]['name'].')合计报表('.$yearsql.')');
		$data[1]['data'][] = array('姓名', '合计(天)');
		
		if($result->num_rows > 0){
		    while($array = $result->fetch_assoc()){
		        $data[0]['data'][] = array(
		                $employee[$array['employee_id']]['namezh'].' ('.$employee[$array['employee_id']]['name'].') ',
		                $array['start'],
		                $array['end'],
		                $array['reason'],
		                $employee[$array['alternative_employee_id']],
		                $status[$array['status']]['namezh']
		        );
		        
				if($array['status']==0 or $array['status']==1){
					$daynumber = caculateDay($array['start'], $array['end']);
					//employee
					$count[$employee[$array['employee_id']]['namezh']]+=$daynumber['day'];
				}
			}
		}
		
		foreach($count as $k => $v){
			$data[1]['data'][] = array($k, $v);
		}
		//print_r($count);
		//print_r($data);
		downloadExcel($properties, $data);
		break;
	case 'travel.apply.manager.report': 
		//get location
		$location = basicMysqliQuery('uatme_oa_system_location');
		//get department
		$department = basicMysqliQuery('uatme_oa_system_department','WHERE manager_employee_id="'.$_SESSION['employee_id'].'"');
		$department = getOwnedDepartment($department);
		
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
		$status = basicMysqliQuery('uatme_oa_system_apply_status');
		//init data request sql
		$assign['yearSelect'] = $_GET['yearSelect']>0 ? $_GET['yearSelect'] : date('Y');
		$assign['timeSelect'] = $_GET['timeSelect'];
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
		$employeesql = ($assign['employeeSelect']>0) ? (' AND employee_id="'.$assign['employeeSelect'].'"') : '';
		//get travel apply
		$sql = 'SELECT * FROM uatme_oa_hr_travel_apply WHERE (start '.$yearsql.') AND (end '.$yearsql.')'.$employeesql.$employeesql2;
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$apply[] = $array;
				if($array['status']==0 or $array['status']==1){
					//whole company
					//$count['whole_company']+=$array['expense'];
					//$assign['count']['whole_company'] = array('name'=>'全公司','count'=>number_format($count['whole_company'],2));
					//location
					//$count[$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name']]+=$array['expense'];
					//$assign['count']['location_'.$department[$employee[$array['employee_id']]['department_id']]['location_id']] = array('name'=>$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name'], 'count'=>number_format($count[$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name']],2));
					//department
					$count[$department[$employee[$array['employee_id']]['department_id']]['name']]+=$array['expense'];
					$assign['count']['department_'.$employee[$array['employee_id']]['department_id']] = array('name'=>$department[$employee[$array['employee_id']]['department_id']]['name'], 'count'=>number_format($count[$department[$employee[$array['employee_id']]['department_id']]['name']],2));
					//employee
					$count[$employee[$array['employee_id']]['namezh']]['已申请']+=$array['expense'];
					$assign['count']['employee_'.$array['employee_id']] = array('name'=>$employee[$array['employee_id']]['namezh'], 'count'=>number_format($count[$employee[$array['employee_id']]['namezh']]['已申请'],2));
				}
			}
		}
		//$assign['location'] = $location;
		$assign['department'] = $department;
		$assign['employee'] = $employee;
		$smarty->assign($assign);
		$smarty->display('hr/travel.manager.report.html');
		break;
	case 'travel.apply.manager.report.export': 
		//get location
		$location = basicMysqliQuery('uatme_oa_system_location');
		//get department
		$department = basicMysqliQuery('uatme_oa_system_department','WHERE manager_employee_id="'.$_SESSION['employee_id'].'"');
		$department = getOwnedDepartment($department);
		
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
		$status = basicMysqliQuery('uatme_oa_system_apply_status');
		//init data request sql
		$assign['yearSelect'] = $_GET['yearSelect']>0 ? $_GET['yearSelect'] : date('Y');
		$assign['timeSelect'] = $_GET['timeSelect'];
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
		$employeesql = ($assign['employeeSelect']>0) ? (' AND employee_id="'.$assign['employeeSelect'].'"') : '';
		//get travel apply
		$sql = 'SELECT * FROM uatme_oa_hr_travel_apply WHERE (start '.$yearsql.') AND (end '.$yearsql.')'.$employeesql.$employeesql2;
		$result = $mysqli->query($sql);
		
		//INIT EXCEL title, attribute, etc.
		$properties = array('filename'=>'差旅申请报表');
		$data = array(
				array(
						'title'=>'差旅申请',
						'data'=>array()
				),
				array(
						'title'=>'费用合计',
						'data'=>array()
				)
		);
		$data[0]['data'][] = array('差旅申请报表('.$yearsql.')');
		$data[0]['data'][] = array('姓名', '目的地', '时间', '事由', '指定代办', '费用预估', '审批结果');
		$data[1]['data'][] = array('差旅申请费用预估合计报表('.$yearsql.')');
		$data[1]['data'][] = array('姓名', '费用预估合计');
		
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$data[0]['data'][] = array(
										$employee[$array['employee_id']]['namezh'].' ('.$employee[$array['employee_id']]['name'].') ',
										$array['target'],
										$array['start'].'~'.$array['end'],
										$array['reason'],
										$employee[$array['alternative_employee_id']],
										$array['expense'],
				                        $status[$array['status']]['namezh']
						);
				if($array['status']==0 or $array['status']==1){
					//employee
					$data[1]['data'][$array['employee_id']] = array(
																$employee[$array['employee_id']]['namezh'].' ('.$employee[$array['employee_id']]['name'].') ',
																$data[1]['data'][$array['employee_id']][1]+$array['expense']
							);
				}
			}
		}
		//print_r($count);
		//print_r($data);
		downloadExcel($properties, $data);
		break;
	case 'travel.apply.hr.report': 
		//get location
		$location = basicMysqliQuery('uatme_oa_system_location');
		//get department
		$department = basicMysqliQuery('uatme_oa_system_department');
		//get employee
		$employee = basicMysqliQuery('uatme_oa_system_employee','WHERE id>1');
		//set status type
		$status = basicMysqliQuery('uatme_oa_system_apply_status');
		//init data request sql
		$assign['yearSelect'] = $_GET['yearSelect']>0 ? $_GET['yearSelect'] : date('Y');
		$assign['timeSelect'] = $_GET['timeSelect'];
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
		$employeesql = ($assign['employeeSelect']>0) ? (' AND employee_id="'.$assign['employeeSelect'].'"') : '';
		//get travel apply
		$sql = 'SELECT * FROM uatme_oa_hr_travel_apply WHERE (start '.$yearsql.') AND (end '.$yearsql.')'.$employeesql;
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
		$smarty->display('hr/travel.hr.report.html');
		break;
	case 'travel.apply.report': 
		//get location
		$location = basicMysqliQuery('uatme_oa_system_location');
		//get department
		$department = basicMysqliQuery('uatme_oa_system_department');
		//get employee
		$employee = basicMysqliQuery('uatme_oa_system_employee','WHERE id>1');
		//set status type
		$status = basicMysqliQuery('uatme_oa_system_apply_status');
		//init data request sql
		$assign['yearSelect'] = $_GET['yearSelect']>0 ? $_GET['yearSelect'] : date('Y');
		$assign['timeSelect'] = $_GET['timeSelect'];
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
		$employeesql = ($assign['employeeSelect']>0) ? (' AND employee_id="'.$assign['employeeSelect'].'"') : '';
		//get travel apply
		$sql = 'SELECT * FROM uatme_oa_hr_travel_apply WHERE (start '.$yearsql.') AND (end '.$yearsql.')'.$employeesql;
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
	case 'travel.apply.report.export': 
		//get location
		$location = basicMysqliQuery('uatme_oa_system_location');
		//get department
		$department = basicMysqliQuery('uatme_oa_system_department');
		//get employee
		$employee = basicMysqliQuery('uatme_oa_system_employee','WHERE id>1');
		//set status type
		$status = basicMysqliQuery('uatme_oa_system_apply_status');
		//init data request sql
		$assign['yearSelect'] = $_GET['yearSelect']>0 ? $_GET['yearSelect'] : date('Y');
		$assign['timeSelect'] = $_GET['timeSelect'];
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
		$employeesql = ($assign['employeeSelect']>0) ? (' AND employee_id="'.$assign['employeeSelect'].'"') : '';
		//get travel apply
		$sql = 'SELECT * FROM uatme_oa_hr_travel_apply WHERE (start '.$yearsql.') AND (end '.$yearsql.')'.$employeesql;
		$result = $mysqli->query($sql);
		
		//INIT EXCEL title, attribute, etc.
		$properties = array('filename'=>'差旅申请报表');
		$data = array(
				array(
						'title'=>'差旅申请',
						'data'=>array()
				),
				array(
						'title'=>'费用合计',
						'data'=>array()
				)
		);
		$data[0]['data'][] = array('差旅申请报表('.$yearsql.')');
		$data[0]['data'][] = array('姓名', '目的地', '时间', '事由', '指定代办', '费用预估', '审批结果');
		$data[1]['data'][] = array('差旅申请费用预估合计报表('.$yearsql.')');
		$data[1]['data'][] = array('姓名', '费用预估合计');
		
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$data[0]['data'][] = array(
										$employee[$array['employee_id']]['namezh'].' ('.$employee[$array['employee_id']]['name'].') ',
										$array['target'],
										$array['start'].'~'.$array['end'],
										$array['reason'],
										$employee[$array['alternative_employee_id']],
										$array['expense'],
				                        $status[$array['status']]
						);
				if($array['status']==0 or $array['status']==1){
					//employee
					$data[1]['data'][$array['employee_id']] = array(
																$employee[$array['employee_id']]['namezh'].' ('.$employee[$array['employee_id']]['name'].') ',
																$data[1]['data'][$array['employee_id']][1]+$array['expense']
							);
				}
			}
		}
		//print_r($count);
		//print_r($data);
		downloadExcel($properties, $data);
		break;
}