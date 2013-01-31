<?php
/**
file: list.php
version: 1
author: vincent.shi
path: /module/expense/

description:
preparation for list page of expense.
mine.apply: my expense apply
mine.approval: apply need my approval

usage:

*/

//init status metrics
$apply_status_array = array(
	0=>array(0=>'待审批', 1=>'待审批'),
	1=>array(0=>'可领取', 1=>'已领取'),
	2=>array(0=>'已拒绝', 1=>'已拒绝')
);

switch($A){
	case 'mine.apply':
		$sql = 'SELECT * FROM uatme_oa_expense_apply';
		$result = $mysqli->query($sql);
		while($array = $result->fetch_assoc()){
			$array['apply_status'] = $apply_status_array[$array['apply_ifapproval']][$array['apply_ifreimbursed']];
			$assign['expense_apply'][] = $array;
		}		
		$smarty->assign($assign);
		$smarty->display('expense/list.apply.html');
	break;
	case 'mine.approval':
		$sql = 'SELECT * FROM uatme_oa_expense_apply WHERE apply_ifapproval=0 ORDER BY apply_date DESC';
		$result = $mysqli->query($sql);
		while($array = $result->fetch_assoc()){
			$assign['apply_approval'][] = $array;
		}				
		$smarty->assign($assign);
		$smarty->display('expense/list.approval.html');
	break;
}