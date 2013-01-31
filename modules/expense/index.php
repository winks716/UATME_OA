<?php
/**
file: index.php
version: 1
author: vincent.shi
path: /module/expense/

description:
sub default page of expense module, show level one module tabs.

usage:
default			//default route action, show expense level1 module tabs

*/
switch($A){
	default:
		$sql = 'SELECT * FROM uatme_oa_expense_modulelv1';
		$result = $mysqli->query($sql);
		while($array = $result->fetch_assoc()){
			$assign['tab'][] = $array;
		}
		$smarty->assign($assign);
		$smarty->display('expense/index.html');
	break;
}