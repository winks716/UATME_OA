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
		$customerName = $_POST['customerName'];
		if($customerId==0 && $customerName!=''){
		    $sql = 'INSERT INTO uatme_oa_sales_customer (name, owner_employee_id) VALUES ("'.$customerName.'", "'.$_SESSION['employee_id'].'")';
		    $mysqli->query($sql);
		    $customerId = $mysqli->insert_id;
		}
		if($customerId > 0){
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
		}else{
		    $httpstatus = 500;
		    $error = '客户关联失败，请检查客户是否存在';
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
	    //get selected date and caculate the week including the date
	    $selectedDate = $_GET['selectedDate']!='' ? $_GET['selectedDate'] : date('Y-n-j');
	    $assign['selectedDate'] = $selectedDate;
	    $selectedDateArray = explode('-', $selectedDate);
	    $selectedYear = $selectedDateArray[0];
	    $selectedMonth = $selectedDateArray[1];
	    $selectedDay = $selectedDateArray[2];
	    $selectedUTCStamp = mktime(0,0,0,$selectedMonth, $selectedDay, $selectedYear); 
		$minday = Date('Y-m-d',($selectedUTCStamp-Date('w', $selectedUTCStamp)*24*3600));
		$maxday = Date('Y-m-d',($selectedUTCStamp+(6-Date('w', $selectedUTCStamp))*24*3600));
	    $wherePeriod = ' AND (createdate BETWEEN "'.$minday.'" AND "'.$maxday.'") ';
	    $assign['selectedPeriod'] = $minday . ' ~ ' . $maxday;
	    //echo $wherePeriod;    
	    
	    $department = basicMysqliQuery('uatme_oa_system_department');
	    $assign['customer'] = basicMysqliQuery('uatme_oa_sales_customer', ' WHERE ifavailable="1"');
	    $assign['employee'] = basicMysqliQuery('uatme_oa_system_employee', ' WHERE ifavailable="1"');
	    $myId = $_SESSION['employee_id'];
	    $myEmployeeId = getMyEmployee($myId);
	    foreach($myEmployeeId as $d=>$e){
	        $activity[$d]['department_name'] = $department[$d]['name'];
	        $activity[$d]['department_id'] = $d;
	        $activity[$d]['activity'] = basicMysqliQuery('uatme_oa_sales_activity', ' WHERE employee_id IN ('.implode(',',$e).') '.$wherePeriod.' ORDER BY employee_id ASC, createdate DESC');
	    }
	    //print_r($activity);
	    $assign['activity'] = $activity;
		$smarty->assign($assign);
		$smarty->display('sales/activity.manager.list.html');
	    break;
	case 'global.list':
	    //get selected date and caculate the week including the date
	    $selectedDate = $_GET['selectedDate']!='' ? $_GET['selectedDate'] : date('Y-n-j');
	    $assign['selectedDate'] = $selectedDate;
	    $selectedDateArray = explode('-', $selectedDate);
	    $selectedYear = $selectedDateArray[0];
	    $selectedMonth = $selectedDateArray[1];
	    $selectedDay = $selectedDateArray[2];
	    $selectedUTCStamp = mktime(0,0,0,$selectedMonth, $selectedDay, $selectedYear); 
		$minday = Date('Y-m-d',($selectedUTCStamp-Date('w', $selectedUTCStamp)*24*3600));
		$maxday = Date('Y-m-d',($selectedUTCStamp+(6-Date('w', $selectedUTCStamp))*24*3600));
	    $wherePeriod = ' AND (createdate BETWEEN "'.$minday.'" AND "'.$maxday.'") ';
	    $assign['selectedPeriod'] = $minday . ' ~ ' . $maxday;
	    //echo $wherePeriod;	
	    
	    $department = basicMysqliQuery('uatme_oa_system_department');
	    $assign['customer'] = basicMysqliQuery('uatme_oa_sales_customer', ' WHERE ifavailable="1"');
	    $assign['employee'] = basicMysqliQuery('uatme_oa_system_employee', ' WHERE ifavailable="1"');
	    $activity = basicMysqliQuery('uatme_oa_sales_activity',  ' WHERE 1'.$wherePeriod.' ORDER BY employee_id ASC, createdate DESC');
	    foreach($activity as $a){
	        $department_id = $assign['employee'][$a['employee_id']]['department_id'];
	        $assign['activity'][$department_id]['department_name'] = $department[$department_id]['name'];
	        $assign['activity'][$department_id]['department_id'] = $department_id;
	        $assign['activity'][$department_id]['activity'][] = $a;
	    }
	    //print_r($activity);
		$smarty->assign($assign);
		$smarty->display('sales/activity.global.list.html');
	    break;
	default:
		
		break;
}