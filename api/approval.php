<?php
//require_once('../db.config.php');
//$sql = 'SELECT id FROM uatme_oa_system_employee WHERE id="'.$_POST['e'].'" AND password="'.$_POST['password'].'"';
//$result = $mysqli->query($sql);
//if($result->num_rows == 1){
	switch($_GET['s']){
		case 'leave':
			switch($_GET['a']){
				case 'agree':
					echo 'leaveagree';
				break;
				case 'deny':
					echo 'leavedeny';
				break;
			}
		break;
		default:
			exit('You are forbidden to access this page, please contact your system administrator!');
		break;
	}
//}else{
//	exit('You are forbidden to access this page, please contact your system administrator!');
//}