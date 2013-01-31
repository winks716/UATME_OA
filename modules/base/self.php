<?php
switch($A){
	case 'edit':
		$assign['id_prefix'] = 'self_edit_';
		$smarty->assign($assign);
		$smarty->display('base/self.edit.html');
	break;
	case 'save':
		//echo "{httpstatus:500}";
		$sql = 'UPDATE uatme_oa_system_employee SET password="'.md5(sha1($_POST['newPassword'])).'" WHERE id="'.$_SESSION['employee_id'].'" and password="'.md5(sha1($_POST['oldPassword'])).'"';
		$result = $mysqli->query($sql);
		if($mysqli->affected_rows == 1){
			echo "{httpstatus:200}";
		}else{
			echo "{httpstatus:500}";
		}
	break;
}