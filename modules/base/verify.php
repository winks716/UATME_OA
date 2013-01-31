<?php
/**
file: verify.php
version: 1
author: vincent.shi
path: /module/base/

description:
verify user identification

usage:
*/

if(($if_oa_maintenance == 1 && 
	$_POST['email'] == 'highsource_oa_admin' && 
	md5(sha1($_POST['password'])) == '19311893da2f53a126c02d287f2e0883') //highsourceoaisinmaintenancenow
	|| $if_oa_maintenance == 0){
	
	//based on who login 
	if($if_oa_maintenance == 1 && $_POST['email'] == 'highsource_oa_admin' && md5(sha1($_POST['password'])) == '19311893da2f53a126c02d287f2e0883'){
		$sql = 'SELECT * FROM uatme_oa_system_employee WHERE email="jane.yang@highsource.com.cn"';
	}else if($if_oa_maintenance == 0){
		$sql = 'SELECT * FROM uatme_oa_system_employee WHERE email="'.htmlspecialchars(trim($_POST['email']), ENT_QUOTES).'@'.COMPANY_DOMAIN.'" AND password="'.md5(sha1($_POST['password'])).'" AND ifleave=0';
	}
	$result = $mysqli->query($sql);
	if($result->num_rows == 1){
		while($array = $result->fetch_assoc()){
			//get user's basic information
			$_SESSION['employee_id'] = $array['id'];
			$_SESSION['employee_no'] = $array['employee_no'];
			$_SESSION['employee_name'] = $array['name'];
			$_SESSION['employee_namezh'] = $array['namezh'];
			$_SESSION['employee_shortname'] = $array['short_name'];
			$_SESSION['alternative_employee_id'] = $array['alternative_employee_id'];
			$_SESSION['employee_email'] = $array['email'];
			$_SESSION['employee_country_id'] = $array['country_id'];
			$_SESSION['employee_location_id'] = $array['location_id'];
			$_SESSION['employee_department_id'] = $array['department_id'];
			$_SESSION['employee_ifleave'] = $array['ifleave'];
			$_SESSION['position'] = array();
			$_SESSION['privilege'] = array();
			//get user's position
			$sql = 'SELECT * FROM uatme_oa_system_position WHERE id IN ('.$array['position_id_list'].')';
			$result_position = $mysqli->query($sql);
			if($result_position->num_rows > 0){
				while($array_position = $result_position->fetch_assoc()){
					$_SESSION['position'][] = $array_position['name'];
				}
			}
			//get user's privilege
			$sql = 'SELECT * FROM uatme_oa_system_privilege WHERE id IN ('.$array['privilege_id_list'].')';
			$result_privilege = $mysqli->query($sql);
			if($result_privilege->num_rows > 0){
				while($array_privilege = $result_privilege->fetch_assoc()){
					$_SESSION['privilege'][] = $array_privilege['name'];
				}
			}

			//special session for popup announce
			$_SESSION['if_announced'] = 0;
		}
	}
	echo "{httpstatus:200}";
}else{
	echo "{httpstatus:503}";
}
