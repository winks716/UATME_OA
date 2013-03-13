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
		$sql = 'SELECT * FROM uatme_oa_hr_leave_apply WHERE (start BETWEEN "'.date('Y').'-01-01 00:00:00" AND "'.date('Y').'-12-31 23:59:59") AND (end BETWEEN "'.date('Y').'-01-01 00:00:00" AND "'.date('Y').'-12-31 23:59:59")';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$apply[] = $array;
				$count[$location[$department[$employee[$array['employee_id']]['department_id']]['location_id']]['name']]++;
				$count[$department[$employee[$array['employee_id']]['department_id']]['name']]++;
				if($array['status']==0 or $array['status']==1){
					$count[$employee[$array['employee_id']]['namezh']]['已使用']++;
				}
				$count[$employee[$array['employee_id']]['namezh']][$type[$array['type']]['name']][$status[$array['status']]]++;
			}
		}
		echo '<pre>';
		print_r($count);
		echo '</pre>';
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
		echo '</pre>';
		break;
}