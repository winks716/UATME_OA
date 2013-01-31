<?php
/**
file: apply.php
version: 1
author: vincent.shi
path: /module/expense/

description:
popup apply page of one expense item

usage:
add.new			//add new expense apply
save.new		//save new expense apply
list.mine		//list my expense applies
get.detail		//show detail of my expense apply
default			//default action

*/
switch($A){
	case 'add.new':
		$sql = 'SELECT * FROM uatme_oa_expense_apply_typelv1 WHERE available=1';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$assign['apply_type1'][] = $array;
			}
		}
		$sql = 'SELECT * FROM uatme_oa_expense_apply_typelv2 WHERE available=1';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$assign['apply_type2'][] = $array;
			}
		}
		$sql = 'SELECT * FROM uatme_oa_system_currency WHERE available=1 ORDER BY orderby ASC';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$assign['currency_type'][] = $array;
			}
		}
		$sql = 'SELECT * FROM uatme_oa_crm_customer WHERE available=1';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$assign['customer'][] = $array;
			}
		}
		$sql = 'SELECT * FROM uatme_oa_project_project WHERE available=1';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$assign['project'][] = $array;
			}
		}
		$sql = 'SELECT * FROM uatme_oa_expense_apply_detail_typelv1 WHERE available=1';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$assign['expense_type'][] = $array;
			}
		}
		$smarty->assign($assign);
		$smarty->display('expense/apply.add.new.html');
	break;
	case 'save.new':
		//deal with post data
		$result = $mysqli->query('SELECT apply_no FROM uatme_oa_expense_apply WHERE apply_no LIKE "EA-'.$_SESSION['employee_shortname'].'-'.date('ymd').'%" ORDER BY apply_no DESC LIMIT 1');
		if($result->num_rows == 1){
			while($array = $result->fetch_assoc()){
				$apply_no_array = explode('-',$array['apply_no']);
				$apply_subno = ++$apply_no_array[3];
			}
		}else{
			$apply_subno = 1;
		}
		$apply_no = 'EA-'.$_SESSION['employee_shortname'].'-'.date('ymd').'-'.$apply_subno;
		$apply_employee_id = $_SESSION['employee_id'];
		$apply_employee_no = $_SESSION['employee_no'];
		$apply_employee_name = $_SESSION['employee_name'];
		$apply_date = date('Y-m-d');
		$apply_money = 0;
		$apply_type1_id = $_POST['apply_type1_id'];
		$apply_type2_id = $_POST['apply_type2_id'];
		$apply_detail = array();
		for($i=0; $i < count($_POST['invoice_no']); $i++){
			$expense_type_id = $_POST['expense_type_id'][$i];
			$invoice_no = htmlspecialchars(trim($_POST['invoice_no'][$i]));
			$expense_currency = explode('-',$_POST['expense_currency'][$i]);
			$expense_money = trim($_POST['expense_money'][$i]);
			$expense_date = htmlspecialchars(trim($_POST['expense_date'][$i]));
			$expense_target_customer_id = $_POST['expense_target_customer_id'][$i];
			$expense_target_person_name = htmlspecialchars(trim($_POST['expense_target_person_name'][$i]));
			$expense_target_person_position = htmlspecialchars(trim($_POST['expense_target_person_position'][$i]));
			$project_id = $_POST['project_id'][$i];
			$expense_description = htmlspecialchars(trim($_POST['expense_description'][$i]));
			//if( is_float($expense_money) && $expense_money != 0 ){
				$apply_money += $expense_money * $expense_currency[1];
				$apply_detail[] = array(
									'expense_type_id' => $expense_type_id,
									'invoice_no' => $invoice_no,
									'expense_currency_type_id' => $expense_currency[0],
									'expense_currency_rate' => $expense_currency[1],
									'expense_money' => $expense_money,
									'expense_date' => $expense_date,
									'expense_target_customer_id' => $expense_target_customer_id,
									'expense_target_person_name' => $expense_target_person_name,
									'expense_target_person_position' => $expense_target_person_position,
									'project_id' => $project_id,
									'expense_description' => $expense_description
								);
			//}
		}
		
		//mysqli script
		$sql = 'INSERT INTO uatme_oa_expense_apply (apply_no, apply_type1_id, apply_type2_id, apply_employee_id, apply_employee_no, apply_employee_name, apply_money, apply_date)
					VALUES ("'.$apply_no.'", "'.$apply_type1_id.'", "'.$apply_type2_id.'", "'.$apply_employee_id.'", "'.$apply_employee_no.'", "'.$apply_employee_name.'", "'.$apply_money.'", "'.$apply_date.'")';
		if($mysqli->query($sql)){
			$apply_id = $mysqli->insert_id;
			foreach($apply_detail as $ad){
				$sql = 'INSERT INTO uatme_oa_expense_apply_detail (apply_id, expense_type_id, invoice_no, expense_currency_type_id, 
						expense_currency_rate, expense_money, expense_date, expense_target_customer_id, expense_target_person_name,
						expense_target_person_position, project_id, expense_description) 
						VALUES ("'.$apply_id.'", "'.$ad['expense_type_id'].'", "'.$ad['invoice_no'].'", "'.$ad['expense_currency_type_id'].'", 
						"'.$ad['expense_currency_rate'].'", "'.$ad['expense_money'].'", "'.$ad['expense_date'].'", "'.$ad['expense_target_customer_id'].'", "'.$ad['expense_target_person_name'].'", 
						"'.$ad['expense_target_person_position'].'", "'.$ad['project_id'].'", "'.$ad['expense_description'].'")';
				//echo $sql;
				$mysqli->query($sql);
			}
			//call workflow function to deal with approval flow
			workflow_init($apply_employee_id, 'expense.apply', $apply_id);
			
			echo '申请成功';
		}
	break;
	case 'list.mine':
		$sql = 'SELECT * FROM uatme_oa_expense_apply';
		$result = $mysqli->query($sql);
		while($array = $result->fetch_assoc()){
			$array['apply_status'] = $apply_status_array[$array['apply_ifapproval']][$array['apply_ifreimbursed']];
			$assign['expense_apply'][] = $array;
		}		
		$smarty->assign($assign);
		$smarty->display('expense/apply.list.mine.html');
	break;
	case 'get.detail':
		$sql = 'SELECT * FROM uatme_oa_expense_apply WHERE id="'.$_POST['id'].'"';
		$result = $mysqli->query($sql);
		if($result->num_rows == 1){
			while($array = $result->fetch_assoc()){
				$assign['expense_apply'][] = $array;
				$sql_detail = 'SELECT 
								a.*, 
								b.symbol as expense_currency_type, 
								c.name as expense_type, 
								d.name as expense_target_customer, 
								e.name as expense_project 
								FROM (((uatme_oa_expense_apply_detail a LEFT JOIN uatme_oa_system_currency b 
								ON a.expense_currency_type_id=b.id) LEFT JOIN uatme_oa_expense_apply_detail_typelv1 c 
								ON a.expense_type_id=c.id) LEFT JOIN uatme_oa_crm_customer d 
								ON a.expense_target_customer_id=d.id) LEFT JOIN uatme_oa_project_project e
								ON a.project_id=e.id 
								WHERE a.apply_id="'.$array['id'].'"';
				$result_detail = $mysqli->query($sql_detail);
				if($result_detail->num_rows > 0){
					while($array_detail = $result_detail->fetch_assoc()){
						$assign['apply_detail'][] = $array_detail;
					}
				}
			}	
		}
		$smarty->assign($assign);
		$smarty->display('expense/apply.get.detail.html');
	break;
	default:
		echo '';
	break;
}