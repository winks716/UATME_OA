<?php
switch($A){
	case 'leave': 
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
		$sql = 'SELECT * FROM uatme_oa_system_employee WHERE id>0';
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
		//get leave apply
		$yearsql = ($_POST['year']>0) ? (' BETWEEN "'.$_POST['year'].'-01-01 00:00:00" AND "'.$_POST['year'].'-12-31 23:59:59"') : (' BETWEEN "'.date('Y').'-01-01 00:00:00" AND "'.date('Y').'-12-31 23:59:59"');
		$typesql = ($_POST['type']>0) ? (' AND type="'.$_POST['type'].'"') : '';
		$sql = 'SELECT * FROM uatme_oa_hr_leave_apply WHERE (start '.$yearsql.') AND (end '.$yearsql.')'.$typesql;
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
		/*//get location
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
		$sql = 'SELECT * FROM uatme_oa_system_employee WHERE id>0';
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
		//get leave apply
		$yearsql = ($_POST['year']>0) ? (' BETWEEN "'.$_POST['year'].'-01-01 00:00:00" AND "'.$_POST['year'].'-12-31 23:59:59"') : (' BETWEEN "'.date('Y').'-01-01 00:00:00" AND "'.date('Y').'-12-31 23:59:59"');
		$typesql = ($_POST['type']>0) ? (' AND type="'.$_POST['type'].'"') : '';
		$sql = 'SELECT * FROM uatme_oa_hr_leave_apply WHERE (start '.$yearsql.') AND (end '.$yearsql.')'.$typesql;
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
		$smarty->assign($assign);
		$smarty->display('hr/leave.report.html');*/
		break;
	case 'travel':
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
		$sql = 'SELECT * FROM uatme_oa_system_employee WHERE id>0';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$employee[$array['id']] = $array;
			}
		}
		$assign['location'] = $location;
		$assign['department'] = $department;
		$assign['employee'] = $employee;
		$smarty->assign($assign);
		$smarty->display('hr/travel.report.html');
		/*//get location
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
		$sql = 'SELECT * FROM uatme_oa_system_employee WHERE id>0';
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
		$sql = 'SELECT * FROM uatme_oa_hr_travel_apply WHERE (start BETWEEN "'.date('Y').'-01-01 00:00:00" AND "'.date('Y').'-12-31 23:59:59") AND (end BETWEEN "'.date('Y').'-01-01 00:00:00" AND "'.date('Y').'-12-31 23:59:59")';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$apply[] = $array;
				$count[$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name']]+=$array['expense'];
				$count[$department[$employee[$array['employee_id']]['department_id']]['name']]+=$array['expense'];
				if($array['status']==0 or $array['status']==1){
					$count[$employee[$array['employee_id']]['namezh']]['已申请']+=$array['expense'];
				}
				$count[$employee[$array['employee_id']]['namezh']][$type[$array['type']]['name']][$status[$array['status']]]+=$array['expense'];
			}
		}
		echo '<pre>';
		print_r($count);
		echo '</pre>';*/
		break;
}