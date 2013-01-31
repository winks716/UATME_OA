<?php
/**
file: approval.php
version: 1
author: vincent.shi
path: /module/expense/

description:
approval action of expense

usage:
list.mine			//list expense apply needed my approval
agree				//agree expense approval
refuse				//refuse expense approval
get.detail			//get the detail of apply needed approval
default				//default action

*/
switch($A){
	case 'list.mine':
		//$sql = 'SELECT * FROM uatme_oa_expense_apply WHERE apply_ifapproval=0 ORDER BY apply_date DESC';
		$sql = 'SELECT a.*, b.id, b.apply_no, b.apply_money, b.apply_date, b.apply_employee_no, b.apply_employee_name FROM uatme_oa_workflow_task a LEFT JOIN uatme_oa_expense_apply b ON a.document_id=b.id WHERE a.employee_id="'.$_SESSION['employee_id'].'" AND a.status=1';
		$result = $mysqli->query($sql);
		while($array = $result->fetch_assoc()){
			$assign['apply_approval'][] = $array;
		}				
		$smarty->assign($assign);
		$smarty->display('expense/approval.list.mine.html');
	break;
	case 'agree':
		$sql = 'UPDATE uatme_oa_expense_apply SET apply_ifapproval=1 WHERE id="'.$_POST['id'].'"';
		if($mysqli->query($sql)){
			echo '审批完成，已通过';
		}
	break;
	case 'refuse':
		$sql = 'UPDATE uatme_oa_expense_apply SET apply_ifapproval=2 WHERE id="'.$_POST['id'].'"';
		if($mysqli->query($sql)){
			echo '审批完成，已拒绝';
		}
	break;
	case 'get.detail':
		$sql = 'SELECT * FROM uatme_oa_expense_apply WHERE id="'.$_POST['id'].'"';
		$result = $mysqli->query($sql);
		if($result->num_rows == 1){
			while($array = $result->fetch_assoc()){
				$assign['expense_apply'][] = $array;
				$sql_detail = 'SELECT * FROM uatme_oa_expense_apply_detail WHERE apply_id="'.$array['id'].'"';
				$result_detail = $mysqli->query($sql_detail);
				if($result_detail->num_rows > 0){
					while($array_detail = $result_detail->fetch_assoc()){
						$assign['apply_detail'][] = $array_detail;
					}
				}
			}	
		}
		$smarty->assign($assign);
		$smarty->display('expense/approval.get.detail.html');
	break;
	default:
		echo '';
	break;
}