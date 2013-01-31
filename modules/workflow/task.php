<?php
/**
file: task.php
version: 1
author: vincent.shi
path: /module/workflow/

description:
workflow

usage:


*/

switch($A){
	default:
		$sql = 'INSERT INTO uatme_oa_workflow_task(document_typelv1_id, document_id, employee_id, status, ifend)
				VALUES (1, 1, 1, 0, 1)';
		$mysqli->query($sql);
	break;
}