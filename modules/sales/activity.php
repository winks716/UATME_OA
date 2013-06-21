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
		$minday = Date('Y-m-d',(time()-(Date('w')+7)*24*3600));
		$maxday = Date('Y-m-d',(time()+(13-Date('w'))*24*3600));
	    $activity = basicMysqliQuery('uatme_oa_sales_activity', ' WHERE employee_id="'.$_SESSION["employee_id"].'" AND date BETWEEN "'.$minday.'" AND "'.$maxday.'"');
	    
	    $deletableminday = Date('Y-m-d',(time()-(Date('w', $selectedUTCStamp)+7)*24*3600));
	    $deletablemaxday = Date('Y-m-d',(time()+(13-Date('w', $selectedUTCStamp))*24*3600));
	    foreach($activity as $a){
	        if($a['date'] >= $deletableminday and $a['date'] <= $deletablemaxday and $a['iflogicaldeleted']==0){
	            $a['deletable'] = 1;
	        }else{
	            $a['deletable'] = 0;
	        }
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
    		//add event for last week
    		$minday1 = Date('Y-m-d',(time()-(Date('w')+7)*24*3600));
    		$maxday1 = Date('Y-m-d',(time()-(Date('w')+1)*24*3600));
    		if($date >= $minday1 and $date <= $maxday1){
    		    $sql = 'INSERT INTO uatme_oa_sales_activity(date, start, end, customer_id, detail, employee_id, ifafterwards)
    		             VALUES ("'.$date.'", "'.$start.'", "'.$end.'", "'.$customerId.'", "'.$detail.'", "'.$_SESSION["employee_id"].'", 1)';
    		    $mysqli->query($sql);
    		    $httpstatus = 200;
    		    $msg = '活动记录成功！';  		    
    		}else{	
        		$minday2 = Date('Y-m-d',(time()-Date('w')*24*3600));
        		$maxday2 = Date('Y-m-d',(time()+(13-Date('w'))*24*3600));
        		if($date >= $minday2 and $date <= $maxday2){
        		    $sql = 'INSERT INTO uatme_oa_sales_activity(date, start, end, customer_id, detail, employee_id)
        		             VALUES ("'.$date.'", "'.$start.'", "'.$end.'", "'.$customerId.'", "'.$detail.'", "'.$_SESSION["employee_id"].'")';
        		    $mysqli->query($sql);
        		    $httpstatus = 200;
        		    $msg = '活动记录成功！';
        		}else{
        		    $httpstatus = 500;
        		    $error = '记录日期 ('.$date.') 超出允许范围 ('.$minday1.'~'.$maxday2.')';
        		}
    		}
		}else{
		    $httpstatus = 500;
		    $error = '客户关联失败，请检查客户是否存在';
		}
		sendResponse($httpstatus, $error, $msg);
		break;
	case 'delete':
		$id = $_POST['id'];
		$activity = basicMysqliQuery('uatme_oa_sales_activity', ' WHERE id="'.$id.'" ');
		//logical delete event for last week
		$minday1 = Date('Y-m-d',(time()-(Date('w')+7)*24*3600));
		$maxday1 = Date('Y-m-d',(time()-(Date('w')+1)*24*3600));
		if($activity[$id]['date'] >= $minday1 and $activity[$id]['date'] <= $maxday1){
    		$sql = 'UPDATE uatme_oa_sales_activity SET iflogicaldeleted="1" WHERE id="'.$id.'"';
    		$mysqli->query($sql);
    		$httpstatus = 200;
    		$msg = '记录删除标记成功';
		}else{
		    //real delete
		    $minday2 = Date('Y-m-d',(time()-Date('w')*24*3600));
		    $maxday2 = Date('Y-m-d',(time()+(13-Date('w'))*24*3600));
		    if($activity[$id]['date'] >= $minday2 and $activity[$id]['date'] <= $maxday2){
        		$sql = 'DELETE FROM uatme_oa_sales_activity WHERE id="'.$id.'"';
        		$mysqli->query($sql);
        		$httpstatus = 200;
        		$msg = '记录成功删除';
		    }else{
    		    $httpstatus = 500;
    		    $error = '选定的记录日期 ('.$date.') 超出允许删除范围 ('.$minday1.'~'.$maxday2.')';
		    }
		}
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
	    $wherePeriod = ' AND (date BETWEEN "'.$minday.'" AND "'.$maxday.'") ';
	    $assign['selectedPeriod'] = $minday . ' ~ ' . $maxday;
	    //echo $wherePeriod;    
	    
	    $department = getOwnedDepartment(basicMysqliQuery('uatme_oa_system_department', ' WHERE manager_employee_id="'.$_SESSION['employee_id'].'" '));
	    foreach($department as $k=>$v){
	        $departmentId[] = $k;
	    }
	    
	    $assign['customer'] = basicMysqliQuery('uatme_oa_sales_customer', ' WHERE ifavailable="1"');
	    $assign['employee'] = basicMysqliQuery('uatme_oa_system_employee', ' WHERE department_id IN ('.implode(',',$departmentId).') AND ifavailable="1"');
	    foreach($assign['employee'] as $k=>$v){
	        $employeeId[] = $k;
	    }
	    
	    $activity = basicMysqliQuery('uatme_oa_sales_activity',  ' WHERE employee_id IN ('.implode(',',$employeeId).') '.$wherePeriod.' ORDER BY employee_id ASC, date DESC');
	    foreach($activity as $a){
	        $department_id = $assign['employee'][$a['employee_id']]['department_id'];
	        $assign['activity'][$department_id]['department_name'] = $department[$department_id]['name'];
	        $assign['activity'][$department_id]['department_id'] = $department_id;
	        $assign['activity'][$department_id]['activity'][] = $a;
	    }
	    
		$smarty->assign($assign);
		$smarty->display('sales/activity.manager.list.html');
	    break;
	case 'manager.list.export':
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
	    $wherePeriod = ' AND (date BETWEEN "'.$minday.'" AND "'.$maxday.'") ';
	    $assign['selectedPeriod'] = $minday . ' ~ ' . $maxday;
	    //echo $wherePeriod;    
	    
	    $department = getOwnedDepartment(basicMysqliQuery('uatme_oa_system_department', ' WHERE manager_employee_id="'.$_SESSION['employee_id'].'" '));
	    foreach($department as $k=>$v){
	        $departmentId[] = $k;
	    }
	    
	    $assign['customer'] = basicMysqliQuery('uatme_oa_sales_customer', ' WHERE ifavailable="1"');
	    $assign['employee'] = basicMysqliQuery('uatme_oa_system_employee', ' WHERE department_id IN ('.implode(',',$departmentId).') AND ifavailable="1"');
	    foreach($assign['employee'] as $k=>$v){
	        $employeeId[] = $k;
	    }
	    
	    $activity = basicMysqliQuery('uatme_oa_sales_activity',  ' WHERE employee_id IN ('.implode(',',$employeeId).') '.$wherePeriod.' ORDER BY employee_id ASC, date DESC');
	    
	    //INIT EXCEL title, attribute, etc.
	    $properties = array('filename'=>'销售日志报表');
	    $data = array(
	    		array(
	    				'title'=>'销售日志',
	    				'data'=>array()
	    		)
	    );
	    $data[0]['data'][] = array('销售日志报表('.$assign['selectedPeriod'].')');
	    $data[0]['data'][] = array('姓名', '日期', '时间', '客户', '详情');
	    
    	foreach($activity as $a){
    		$data[0]['data'][] = array(
    				$assign['employee'][$a['employee_id']]['namezh'].' ('.$assign['employee'][$a['employee_id']]['name'].') ',
    				$a['date'],
    				$a['start'].'~'.$a['end'],
    				$assign['customer'][$a['customer_id']]['name'],
    				$a['detail']
    		);
    	}
	    //print_r($count);
	    //print_r($data);
	    downloadExcel($properties, $data);
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
	    $wherePeriod = ' AND (date BETWEEN "'.$minday.'" AND "'.$maxday.'") ';
	    $assign['selectedPeriod'] = $minday . ' ~ ' . $maxday;
	    //echo $wherePeriod;	
	    
	    $department = basicMysqliQuery('uatme_oa_system_department');
	    $assign['customer'] = basicMysqliQuery('uatme_oa_sales_customer', ' WHERE ifavailable="1"');
	    $assign['employee'] = basicMysqliQuery('uatme_oa_system_employee', ' WHERE ifavailable="1"');
	    $activity = basicMysqliQuery('uatme_oa_sales_activity',  ' WHERE 1'.$wherePeriod.' ORDER BY employee_id ASC, date DESC');
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
	case 'global.list.export':
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
	    $wherePeriod = ' AND (date BETWEEN "'.$minday.'" AND "'.$maxday.'") ';
	    $assign['selectedPeriod'] = $minday . ' ~ ' . $maxday;
	    //echo $wherePeriod;	
	    
	    $department = basicMysqliQuery('uatme_oa_system_department');
	    $assign['customer'] = basicMysqliQuery('uatme_oa_sales_customer', ' WHERE ifavailable="1"');
	    $assign['employee'] = basicMysqliQuery('uatme_oa_system_employee', ' WHERE ifavailable="1"');
	    $activity = basicMysqliQuery('uatme_oa_sales_activity',  ' WHERE 1'.$wherePeriod.' ORDER BY employee_id ASC, date DESC');
	    
	    //INIT EXCEL title, attribute, etc.
	    $properties = array('filename'=>'销售日志报表');
	    $data = array(
	    		array(
	    				'title'=>'销售日志',
	    				'data'=>array()
	    		)
	    );
	    $data[0]['data'][] = array('销售日志报表('.$assign['selectedPeriod'].')');
	    $data[0]['data'][] = array('姓名', '日期', '时间', '客户', '详情');
	    
    	foreach($activity as $a){
    		$data[0]['data'][] = array(
    				$assign['employee'][$a['employee_id']]['namezh'].' ('.$assign['employee'][$a['employee_id']]['name'].') ',
    				$a['date'],
    				$a['start'].'~'.$a['end'],
    				$assign['customer'][$a['customer_id']]['name'],
    				$a['detail']
    		);
    	}
	    //print_r($count);
	    //print_r($data);
	    downloadExcel($properties, $data);
	    break;
	case 'self.list':
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
	    $wherePeriod = ' AND (date BETWEEN "'.$minday.'" AND "'.$maxday.'") ';
	    $assign['selectedPeriod'] = $minday . ' ~ ' . $maxday;
	    //echo $wherePeriod;	
	    
	    $department = basicMysqliQuery('uatme_oa_system_department');
	    $assign['customer'] = basicMysqliQuery('uatme_oa_sales_customer', ' WHERE ifavailable="1"');
	    $assign['activity'] = basicMysqliQuery('uatme_oa_sales_activity', ' WHERE employee_id="'.$_SESSION['employee_id'].'" '.$wherePeriod.' ORDER BY date DESC');
	    
	    //mark which activity are deleteable
	    $deletableminday = Date('Y-m-d',(time()-(Date('w', $selectedUTCStamp)+7)*24*3600));
	    $deletablemaxday = Date('Y-m-d',(time()+(13-Date('w', $selectedUTCStamp))*24*3600));
	    foreach($assign['activity'] as $a){
	        if($a['date'] >= $deletableminday and $a['date'] <= $deletablemaxday and $a['iflogicaldeleted']==0){
	            $assign['activity'][$a['id']]['deletable'] = 1;
	        }else{
	            $assign['activity'][$a['id']]['deletable'] = 0;
	        }
	    }
	    
	    //print_r($activity);
		$smarty->assign($assign);
		$smarty->display('sales/activity.self.list.html');
	    break;
	case 'self.list.export':
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
	    $wherePeriod = ' AND (date BETWEEN "'.$minday.'" AND "'.$maxday.'") ';
	    $assign['selectedPeriod'] = $minday . ' ~ ' . $maxday;
	    //echo $wherePeriod;	
	    
	    $department = basicMysqliQuery('uatme_oa_system_department');
	    $assign['customer'] = basicMysqliQuery('uatme_oa_sales_customer', ' WHERE ifavailable="1"');
	    $activity = basicMysqliQuery('uatme_oa_sales_activity', ' WHERE employee_id="'.$_SESSION['employee_id'].'" '.$wherePeriod.' ORDER BY date DESC');
	    	    
	    //INIT EXCEL title, attribute, etc.
	    $properties = array('filename'=>'销售日志报表');
	    $data = array(
	    		array(
	    				'title'=>'销售日志',
	    				'data'=>array()
	    		)
	    );
	    $data[0]['data'][] = array('销售日志报表('.$assign['selectedPeriod'].')');
	    $data[0]['data'][] = array('日期', '时间', '客户', '详情');
	    
    	foreach($activity as $a){
    		$data[0]['data'][] = array(
    				$a['date'],
    				$a['start'].'~'.$a['end'],
    				$assign['customer'][$a['customer_id']]['name'],
    				$a['detail']
    		);
    	}
	    //print_r($count);
	    //print_r($data);
	    downloadExcel($properties, $data);
	    break;
	default:
		
		break;
}