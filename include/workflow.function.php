<?php
/**
file: workflow.function.php

version: 1
author: vincent.shi
path: /include/

description:
workflow function list

usage:
workflow_init			//init workflow task based on workflow template


*/

function workflowForward(){
	
}

function workflowBackward(){
	
}

function workflowInit($apply_employee_id, $apply_type_english, $apply_id){
	global $mysqli;
	//workflow
	//step1 select document type from workflow dbtable
	$sql = 'SELECT id FROM uatme_oa_workflow_document_typelv1 WHERE name_english="'.$apply_type_english.'"';
	$result = $mysqli->query($sql);
	if($result->num_rows == 1){
		while($array = $result->fetch_assoc()){
			$document_typelv1_id = $array['id'];
		}
	}
	//step2 select workflow template based on document type id
	$sql_workflow_template = 'SELECT * FROM uatme_oa_workflow_template WHERE document_typelv1_id="'.$document_typelv1_id.'" ORDER BY orderby ASC';
	$result_workflow_template = $mysqli->query($sql_workflow_template);
	if($result_workflow_template->num_rows > 0){
		//init task counter, if no task setup, just update document ifapproval status as approved.
		$task_counter = 0;
		while($array_workflow_template = $result_workflow_template->fetch_assoc()){
			//for each step2 result line, carry out step3
			//step3, approval executer choosing logic
			//echo $array_workflow_template['workflow_type'];
			switch($array_workflow_template['workflow_type']){
				case '0':
					//0 means approval executer should be his/her department manager. Employee_id follow workflow_type should be 0 and useless
					//get document owner employee_id, get his/her department_id, get manager_id of the department as approval executer
					$sql = 'SELECT manager_employee_id FROM uatme_oa_system_department WHERE id=(
								SELECT department_id FROM uatme_oa_system_employee WHERE id="'.$apply_employee_id.'"
								)';
								//echo $sql;
					$result = $mysqli->query($sql);
					if($result->num_rows == 1){
						while($array = $result->fetch_assoc()){
							$executer_employee_id = $array['manager_employee_id'];
						}
					}
				break;
				case '1':
					//1 means certain specific position people will execute the approval. Employee_id follow workflow_type should be related to system_position.id
					//get position id, get id of employees who have this position id. 
					//If more than 1 person matched, select the least task left one. 
					//If no one get, please go manager approval workflow, means 0.
					$sql = 'SELECT id FROM uatme_oa_system_employee WHERE 
							position_id_list = "'.$array_workflow_template['owner_id'].'" OR 
							position_id_list LIKE "%,'.$array_workflow_template['owner_id'].'" OR 
							position_id_list LIKE "%,'.$array_workflow_template['owner_id'].',%" OR 
							position_id_list like "'.$array_workflow_template['owner_id'].',%"';
							//echo $sql;
					$result = $mysqli->query($sql);
					if($result->num_rows == 1){
						while($array = $result->fetch_assoc()){
							$executer_employee_id = $array['id'];
						}
					}else if($result->num_rows > 1){
						$executer_employee_id_set = '';
						while($array = $result->fetch_assoc()){
							$executer_employee_id_set .= $array['id'].',';
						}
						$executer_employee_id_set = substring($executer_employee_id_set,0,-1);
						$sql = 'SELECT employee_id FROM uatme_oa_workflow_task WHERE employee_id IN ('.$executer_employee_id_set.') AND 
								status=0 
								GROUP BY employee_id 
								ORDER BY COUNT(employee_id) ASC 
								LIMIT 1';
								//echo $sql;
						$result = $mysqli->query($sql);
						if($result->num_rows == 1){
							while($array = $result->fetch_assoc()){
								$executer_employee_id = $array['employee_id'];
							}
						}
					}else if($result->num_rows == 0){
						$sql = 'SELECT manager_employee_id FROM uatme_oa_system_department WHERE id=(
									SELECT department_id FROM uatme_oa_system_employee WHERE id="'.$apply_employee_id.'"
									)';
									//echo $sql;
						$result = $mysqli->query($sql);
						if($result->num_rows == 1){
							while($array = $result->fetch_assoc()){
								$executer_employee_id = $array['manager_employee_id'];
							}
						}
					}
				break;
				case '2':
					$executer_employee_id = $array_workflow_template['owner_id'];
				break;
				default:
					//default action
				break;
			}
			//check if executer is the same person as logined user, means me. If so, skip this executer.
			//echo $executer_employee_id;
			if($executer_employee_id != $apply_employee_id){
				//check if executer is leave or not?
				$sql = 'SELECT ifleave,alternative_employee_id FROM uatme_oa_system_employee WHERE id="'.$executer_employee_id.'" AND ifleave=1';
				//echo $sql;
				$result = $mysqli->query($sql);
				if($result->num_rows == 1){
					while($array = $result->fetch_assoc()){
						$executer_employee_id = $array['alternative_employee_id'];
					}
				}
				//insert a new task for selected executer
				$sql = 'INSERT INTO uatme_oa_workflow_task(document_typelv1_id, document_id, employee_id, orderby, created_date, updated_date)
						VALUES ("'.$document_typelv1_id.'", "'.$apply_id.'", "'.$executer_employee_id.'", "'.$array_workflow_template['orderby'].'", "'.Date('Y-m-d H:i:s').'", "'.Date('Y-m-d H:i:s').'")';
						//echo $sql;
				$mysqli->query($sql);
				//increase task counter
				$task_counter++;
			}
		}
		if($task_counter != 0){
			//mark the first task as proceeding status, means status=1
			$sql = 'UPDATE uatme_oa_workflow_task SET status=1 WHERE document_id="'.$apply_id.'" ORDER BY orderby ASC LIMIT 1';
			$mysqli->query($sql);
			//mark the last task as end point
			$sql = 'UPDATE uatme_oa_workflow_task SET ifend=1 WHERE document_id="'.$apply_id.'" ORDER BY orderby DESC LIMIT 1';
			$mysqli->query($sql);
		}else{
			//just update document status as approved
		}
	}
}