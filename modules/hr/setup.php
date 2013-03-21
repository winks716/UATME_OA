<?php
//setup all kinds of leave
switch($A){
	case 'annual.setup':
		if(!privilegeCheck(array('hr_annual_admin'))){
				exit('您无权访问此页面');
		}
		//all employee
		$sql = 'SELECT id,name,namezh FROM uatme_oa_system_employee WHERE ifleave=0 AND id!=1 ORDER BY name';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$assign['employee'][] = $array;
			}
		}
		//totally how many annual leave available (including used or unused)
		$sql = 'SELECT * FROM uatme_oa_hr_leave_employee WHERE leave_type_id=(SELECT id FROM uatme_oa_hr_leave_type WHERE name="年假" LIMIT 1) AND ifavailable=1';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$assign['total'][$array['employee_id']] = $array['count'];
			}
		}
		//has used annual leave
		$sql = 'SELECT count(*) as leavecount, employee_id FROM uatme_oa_hr_leave_apply WHERE status=1 AND ((start BETWEEN "'.Date('Y').'-01-01 00:00:00" AND "'.Date('Y').'-12-31 23:59:59") OR (end BETWEEN "'.Date('Y').'-01-01 00:00:00" AND "'.Date('Y').'-12-31 23:59:59")) AND type=(SELECT id FROM uatme_oa_hr_leave_type WHERE name="年假" LIMIT 1) GROUP BY employee_id';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$assign['used'][$array['employee_id']] = $array['leavecount'];
			}
		}
		$smarty->assign($assign);
		$smarty->display('hr/annual.list.html');
		break;
	case 'annual.save':
		if(!privilegeCheck(array('hr_annual_admin'))){
				exit('您无权访问此页面');
		}
		//test if there is a record for employee
		$sql = 'SELECT * FROM uatme_oa_hr_leave_employee WHERE employee_id="'.$_POST['id'].'" AND leave_type_id=(SELECT id FROM uatme_oa_hr_leave_type WHERE name="年假" LIMIT 1)';
		$result = $mysqli->query($sql);
		if($result->num_rows == 1){
			while($array = $result->fetch_assoc()){
				$sql = 'UPDATE uatme_oa_hr_leave_employee SET count="'.$_POST['count'].'" WHERE id="'.$array['id'].'"';
				if($mysqli->query($sql)){
					$httpstatus = 200;
					$msg = '年假修改保存成功';
				}else{
					$httpstatus = 500;
					$error = '服务器忙，请稍后再试';
				}					
			}
		}else{
			$sql = 'INSERT INTO uatme_oa_hr_leave_employee(leave_type_id, employee_id, count) VALUES((SELECT id FROM uatme_oa_hr_leave_type WHERE name="年假" LIMIT 1), "'.$_POST['id'].'", "'.$_POST['count'].'")';
			if($mysqli->query($sql)){
				$httpstatus = 200;
				$msg = '年假修改保存成功';
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试';
			}
		}

		sendResponse($httpstatus, $error, $msg);
		break;
	default:
		exit('访问受限，请联系管理员');
		break;
}