<?php
switch($A){
	case 'new.apply':
		//deal with post data
		$result = $mysqli->query('SELECT apply_no FROM uatme_oa_expense_apply WHERE apply_no LIKE "EA-'.$_SESSION['employeeshortname'].'-'.date('ymd').'%" ORDER BY apply_no DESC LIMIT 1');
		if($result->num_rows == 1){
			while($array = $result->fetch_assoc()){
				$apply_no_array = explode('-',$array['apply_no']);
				$apply_subno = ++$apply_no_array[3];
			}
		}else{
			$apply_subno = 1;
		}
		$apply_no = 'EA-'.$_SESSION['employeeshortname'].'-'.date('ymd').'-'.$apply_subno;
		$apply_employeeid = $_SESSION['employeeid'];
		$apply_employeename = $_SESSION['employeename'];
		$apply_date = date('Y-m-d');
		$apply_money = 0;
		$apply_detail = array();
		for($i=0; $i < count($_POST['invoice_no']); $i++){
			$expense_money = trim($_POST['expense_money'][$i]);
			$invoice_no = htmlspecialchars(trim($_POST['invoice_no'][$i]));
			$expense_target = htmlspecialchars(trim($_POST['expense_target'][$i]));
			$expense_date = htmlspecialchars(trim($_POST['expense_date'][$i]));
			//if( is_float($expense_money) && $expense_money != 0 && $invoice_no != ''){
				$apply_money += $expense_money;
				$apply_detail[] = array(
									'invoice_no' => $invoice_no,
									'expense_money' => $expense_money,
									'expense_target' => $expense_target,
									'expense_date' => $expense_date
								);
			//}
		}
		
		//mysqli script
		$sql = 'INSERT INTO uatme_oa_expense_apply (apply_no, apply_employeeid, apply_employeename, apply_money, apply_date)
					VALUES ("'.$apply_no.'", "'.$apply_employeeid.'", "'.$apply_employeename.'", "'.$apply_money.'", "'.$apply_date.'")';
		if($mysqli->query($sql)){
			$apply_id = $mysqli->insert_id;
			foreach($apply_detail as $ad){
				$sql = 'INSERT INTO uatme_oa_expense_apply_detail (apply_id, invoice_no, expense_money, expense_target, expense_date) 
						VALUES ("'.$apply_id.'", "'.$ad['invoice_no'].'", "'.$ad['expense_money'].'", "'.$ad['expense_target'].'", "'.$ad['expense_date'].'")';
				$mysqli->query($sql);
				echo $sql;
			}
			echo '申请成功';
		}
	break;
}