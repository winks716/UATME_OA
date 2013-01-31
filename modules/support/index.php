<?php

switch($A){
	case 'email.advice':
		$smarty->assign($assign);
		$smarty->display('support/email.advice.html');
	break;
	default:
		//define if admin privilege
		if(in_array('support_admin', $_SESSION['privilege']) || $_SESSION['if_support_admin']==1){
			$_SESSION['if_support_admin'] = 1;
			$assign['ifadmin'] = 1;
		}else{
			$assign['ifadmin'] = 0;
		}

		if($_SESSION['if_support_admin'] == 1){
			$sql = 'SELECT * FROM uatme_oa_support_question ORDER BY id DESC LIMIT 60';
		}else{
			$sql = 'SELECT * FROM uatme_oa_support_question WHERE employee_id="'.$_SESSION['employee_id'].'" ORDER BY id DESC LIMIT 30';
		}
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$assign['question'][] = $array;
			}
		}

		$smarty->assign($assign);
		$smarty->display('support/index.html');
	break;
}
