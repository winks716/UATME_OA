<?php
switch($A){
	case 'record':
	    $myId = $_SESSION['employee_id'];
	    $assign['mycustomer'] = basicMysqliQuery('uatme_oa_sales_customer', 
	                ' WHERE ifavailable="1" AND (owner_employee_id LIKE "" 
	            OR owner_employee_id="0" OR owner_employee_id LIKE "'.$myId.'" 
	            OR owner_employee_id LIKE "%,'.$myId.'" 
	            OR owner_employee_id LIKE "'.$myId.',%" 
	            OR owner_employee_id LIKE "%,'.$myId.',%") ORDER BY name ASC, id ASC');
	    $customer = basicMysqliQuery('uatme_oa_sales_customer', ' WHERE ifavailable="1"');
		$minday = Date('Y-m-d',(time()-Date('w')*24*3600));
		$maxday = Date('Y-m-d',(time()+(6-Date('w'))*24*3600));
	    $activity = basicMysqliQuery('uatme_oa_sales_activity', ' WHERE employee_id="'.$_SESSION["employee_id"].'" AND date BETWEEN "'.$minday.'" AND "'.$maxday.'"');
	    foreach($activity as $a){
	        $a['customerName'] = $customer[$a['customer_id']]['name'];
	        $assign['activity'][] = $a;
	    }
		$smarty->assign($assign);
		$smarty->display('sales/activity.record.html');
		break;
	case 'save':
		$date = $_POST['date'];
		$start = $_POST['start'];
		$end = $_POST['end'];
		$customerId = $_POST['customerId'];
		$detail = $_POST['detail'];
		$minday = Date('Y-m-d',(time()-Date('w')*24*3600));
		$maxday = Date('Y-m-d',(time()+(6-Date('w'))*24*3600));
		if($date >= $minday and $date <= $maxday){
		    $sql = 'INSERT INTO uatme_oa_sales_activity(date, start, end, customer_id, detail, employee_id)
		             VALUES ("'.$date.'", "'.$start.'", "'.$end.'", "'.$customerId.'", "'.$detail.'", "'.$_SESSION["employee_id"].'")';
		    $mysqli->query($sql);
		    $httpstatus = 200;
		    $msg = '活动记录成功！';
		}else{
		    $httpstatus = 500;
		    $error = '记录日期 ('.$date.') 超出允许范围 ('.$minday.'~'.$maxday.')';
		}
		sendResponse($httpstatus, $error, $msg);
		break;
	case 'delete':
		$id = $_POST['id'];
		$sql = 'DELETE FROM uatme_oa_sales_activity WHERE id="'.$id.'"';
		$mysqli->query($sql);
		$httpstatus = 200;
		$msg = '记录成功删除';
		sendResponse($httpstatus, $error, $msg);
		break;
	case 'manager.list':	    
	    $department = basicMysqliQuery('uatme_oa_system_department');
	    $assign['customer'] = basicMysqliQuery('uatme_oa_sales_customer', ' WHERE ifavailable="1"');
	    $assign['employee'] = basicMysqliQuery('uatme_oa_system_employee', ' WHERE ifavailable="1"');
	    $myId = $_SESSION['employee_id'];
	    $myEmployeeId = getMyEmployee($myId);
	    foreach($myEmployeeId as $d=>$e){
	        $activity[$d]['department_name'] = $department[$d]['name'];
	        $activity[$d]['department_id'] = $d;
	        $activity[$d]['activity'] = basicMysqliQuery('uatme_oa_sales_activity', ' WHERE employee_id IN ('.implode(',',$e).') ORDER BY employee_id ASC, createdate DESC');
	    }
	    //print_r($activity);
	    $assign['activity'] = $activity;
		$smarty->assign($assign);
		$smarty->display('sales/activity.manager.list.html');
	    break;
	case 'global.list':
	    $department = basicMysqliQuery('uatme_oa_system_department');
	    $assign['customer'] = basicMysqliQuery('uatme_oa_sales_customer', ' WHERE ifavailable="1"');
	    $assign['employee'] = basicMysqliQuery('uatme_oa_system_employee', ' WHERE ifavailable="1"');
	    $activity = basicMysqliQuery('uatme_oa_sales_activity', ' ORDER BY employee_id ASC, createdate DESC');
	    foreach($activity as $a){
	        $department_id = $assign['employee'][$a['employee_id']]['department_id'];
	        $assign['activity'][$department_id]['department_name'] = $department[$department_id]['name'];
	        $assign['activity'][$department_id]['department_id'] = $department_id;
	        $assign['activity'][$department_id]['activity'][] = $a;
	    }
	    //print_r($activity);
		$smarty->assign($assign);
		$smarty->display('sales/activity.manager.list.html');
	    break;
	default:
		
		break;
}