<?php
switch($A){
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
}