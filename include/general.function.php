<?php
/**
file: general.function.php
version: 1
author: vincent.shi
path: /include/

description:
general function set

List:
-------------------------------------------------------
sendResponse($httpstatus=200, $error='', $msg='')
//Description: send the response with JSON format
//Acception: $httpstatus=200/500; $error='error msg'; $msg='successful msg'
//Return: string in JSON format, like "{httpstatus:200, error:'server busy, try again later', msg:'save done'}"
--------------------------------------------------------
basicMysqliQuery($dbtable='', $where='')
//Description: carry out basic mysql query by mysqli function
//Acception: $dbtable='database tablename'; $where='query condition string'
//Return: 2D array indexed by id, like array('1'=>array('id'=>1,'name'=>'vincent'),'3'=>array('id'=>3,'name'=>'winky'))
---------------------------------------------------------
privilegeCheck($privilege=array())
//Description: check if visitor meets the privilege request
//Acception: an array like array('club_admin','hr_admin'...)
//Return: if check passed, return 1;  if check failed, return 0;
---------------------------------------------------------
supervisorCheck($employee_id=0, $supervisor_id=0)
//Description: check if supervisor_id is the supervisor of employee_id
//Acception: 2 Int variable
//Return: if check passed, return true; if check failed, return false;
---------------------------------------------------------
caculateDay($starttimestamp='0000-00-00 00:00:00', $endtimestamp='0000-00-00 00:00:00'){
//Description: caculate day number from start timestamp and end timestamp, type='yyyy-mm-dd hh:mm:ss'
//Acception: timestamp string
//Return: array containing the data of day, hour, minute, second
------------------------------------------------------
downloadExcel($properties = array(), $data = array()){
//Description: render array data into excel format and download
//Acception: 
	// The acceptable data should look like below format
	$properties = array(
		'filename'=>'excel',
		'creator'=>'UATME_OA',
		'lastmodifiedby'=>'UATME_OA',
		'title'=>'excel',
		'subject'=>'excel',
		'description'=>'excel',
		'keywords'=>'excel',
		'category'=>'excel'
	);
	$data = array(
		array(
			'title'=>'sheet1',
			'data'=>array(
				array('Hello','world!','Hello','world!'),
				array('Miscellaneous glyphs'),
				array('éàèùâêîôûëïüÿäöüç'),
				array('试试看中文好用不')
			)
		),
		array(
			'title'=>'工作表sheet2',
			'data'=>array(
				array('你好','world!','搞什么飞机','world!'),
				array('Miscellaneous glyphs'),
				array('éàèùâêîôûëïüÿäöüç'),
				array('试试看中文好用不')
			)
		)
	);
//Return: downloadable excel file
------------------------------------------------------
getOwnedDepartment($depart = array())
//Description: get all the department under control recursively, from a parent department to all sub-department
//Acception: department array, indexed by department_id, like array(5=>array(5,'hr','human resource',0,...),8=>array(8,'sales','sales',0,...)), means accept multiple array if one take charge of two departments at the same time.
//Return: a merged array, same format as $depart, combined with new sub-department into it.
------------------------------------------------------
getDateSQLByMonth($yearSelect = 0, $timeSelect = 0)
//Description: get the date period SQL string by select a month name
//Acception: number, from ''/0 to 12, means select nothing, Jan, Feb, Mar, ..., Dec
//Return: sql condition string for selection, like, "BETWEEN XXXX-XX-XX XX:XX:XX AND YYYY-YY-YY YY:YY:YY"
------------------------------------------------------
getDateSQLByQuarter($yearSelect = 0, $timeSelect = 0)
//Description: get the date period SQL string by select a quarter name
//Acception: number, from ''/0, 1 to 4, means select nothing, 1st quarter, 2nd quarter, ..., 4th quarter
//Return: sql condition string for selection, like, "BETWEEN XXXX-XX-XX XX:XX:XX AND YYYY-YY-YY YY:YY:YY"
--------------------------------------------------------
getUsedAnnualLeave($employee_id = 0, $ifOnlyApproved = 1)
//Description: get specific employee's used annual leave number
//Acception: Int, >0, employee id;    Int, ==1, only approved apply will be counted, Else, approving not end will also be counted
//Return: if successful, Float, number of used annual leave; Else, 'N/A'
------------------------------------------------------------------
getRestAnnualLeave($employee_id = 0)
//Description: get specific employee's rest annual leave number
//Acception: Int, >0, employee id
//Return: if successful, Float, number of rest annual leave; Else, 'N/A'

*/


//send the response with JSON format
function sendResponse($httpstatus=200, $error='', $msg=''){
echo "{";
echo 	"httpstatus: " . $httpstatus . ",\n";
echo	"error: '" . $error . "',\n";
echo	"msg: '" . $msg . "'\n";
echo "}";
}

//carry out basic mysql query by mysqli function
function basicMysqliQuery($dbtable='', $where=''){
	global $mysqli;
	$return = array();
	$sql = 'SELECT * FROM '.$dbtable.' '.$where;
	$result = $mysqli->query($sql);
	if($result->num_rows > 0){
		while($array = $result->fetch_assoc()){
			$return[$array['id']] = $array;
		}
	}
	return $return;
}

//check if visitor meets the privilege request
function privilegeCheck($privilege=array()){
	$return = 1;
	foreach($privilege as $p){
		if(!in_array($p, $_SESSION['privilege'])){
			$return = 0;
		}
	}
	return $return;
}

//check if supervisor_id is the supervisor of employee_id
function supervisorCheck($employee_id=0, $supervisor_id=0){
    //check if operator is the supervisor of applier
    $sql = 'SELECT id FROM uatme_oa_system_department
	            WHERE id=(SELECT department_id FROM uatme_oa_system_employee WHERE id="'.$employee_id.'")
	                    AND manager_employee_id="'.$supervisor_id.'"';
    $result = $mysqli->query($sql);
    if($result->num_rows > 0){
        return true;
    }else{
        return false;
    }
}

//per employee_id, return the array of employee_id of who working for me
function getMyEmployee($employee_id=0){
    $department = basicMysqliQuery('uatme_oa_system_department', ' WHERE manager_employee_id="'.$employee_id.'"');
    foreach($department as $d){
        $employee = basicMysqliQuery('uatme_oa_system_employee', ' WHERE department_id="'.$d['id'].'"');
        foreach($employee as $e){
            $myEmployeeId[$d['id']][] = $e['id'];
        }
    }
    return $myEmployeeId;
}

//check if visitor meets the privilege request of module,submodule and action.
//this should be an update of function privilegeCheck
function msaAuth($M, $S, $A, $session){
	$return = 0;
	if($M == '' && $S == '' && $A == ''){
		$return = 1;
	}else{
		global $mysqli;
		$sql = 'SELECT DISTINCT privilege_acceptable FROM uatme_oa_system_module WHERE module="'.$M.'" AND submodule="'.$S.'" AND action="'.$A.'" AND available="1" AND privilege_acceptable!="" AND privilege_acceptable!="0"';
		if($result = $mysqli->query($sql)){
			if($result->num_rows == 1){
				while($array = $result->fetch_assoc()){
					$privilege = explode(',',$array['privilege_acceptable'].',0');
					foreach($session as $k => $p){
						if(in_array($k,$privilege)){
							$return = 1; //visitor's privilege session has at least one match the module privilege setting.
						}
					}
				}
			}else{
				if($result->num_rows > 1){
					$return = 0; //module privilege setting not unique, this is a big error!
					exit('系统0认证服务忙，请联系管理员');
				}else{
					$return = 1; //no module privilege setting, means everyone could visit, if it's there.
				}
			}		
		}else{
			$return = 0; //means db action wrong
			exit('系统0数据服务忙，请联系管理员');
		}
	}
	return $return;
}

//caculate day number from start timestamp and end timestamp
function caculateDay($starttimestamp='0000-00-00 00:00:00', $endtimestamp='0000-00-00 00:00:00'){
	$start = explode(' ', $starttimestamp);
	$end = explode(' ', $endtimestamp);
	$startdate = explode('-', $start[0]);
	$starttime = explode(':', $start[1]);
	$enddate = explode('-', $end[0]);
	$endtime = explode(':', $end[1]);
	
	$startsecond = mktime($starttime[0],$starttime[1],$starttime[2],$startdate[1],$startdate[2],$startdate[0]);
	$endsecond = mktime($endtime[0],$endtime[1],$endtime[2],$enddate[1],$enddate[2],$enddate[0]);
	
	$second = $endsecond-$startsecond;
	//echo $second,'秒';
	$minute = $second/60;
	//echo $minute,'分';
	$hour = $minute/60;
	//echo $hour,'小时';
	if($start[0]!=$end[0]){
		$day = ((($hour%24)/8) + floor($hour/24));
	}else{
		$day = $hour/8;
	}
	//echo $day,'天';
	$return = array('second'=>$second, 'minute'=>$minute, 'hour'=>$hour, 'day'=>$day);
	return $return;
}

//render array data into excel format and let user download
function downloadExcel($properties = array(), $data = array()){	
	// init excel column name
	$col1 = array('','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	$col2 = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	$col = array();
	foreach($col1 as $c1){
		foreach($col2 as $c2){
			$col[] = $c1.$c2;
		}
	}

	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator($properties['creator'])
								 ->setLastModifiedBy($properties['lastmodifiedby'])
								 ->setTitle($properties['title'])
								 ->setSubject($properties['subject'])
								 ->setDescription($properties['description'])
								 ->setKeywords($properties['keywords'])
								 ->setCategory($properties['category']);
	
	// go throw 3 level data array, level1 for sheet, level2 for row in one sheet, level3 for cell in one row
	// go throw sheet
	$i = 0;
	foreach($data as $sheet){
		// if worksheet more than 1, create the new one at last position
		if($i > 0){
			$objPHPExcel->createSheet();
		}
		
		// go throw every row in this sheet
		$j = 1;
		foreach($sheet['data'] as $row){
		
			// go throw every cell in this row
			$k = 0;
			foreach($row as $cell){
				$objPHPExcel->setActiveSheetIndex($i)->setCellValue($col[$k].$j,$cell);
				$k++;
			}
			$j++;
		}
		
		//set the sheet title
		$objPHPExcel->getActiveSheet()->setTitle($sheet['title']);
		$i++;
	}

	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$properties['filename'].'.xls"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');

	exit;
}

//get owned department information for one person recursively
function getOwnedDepartment($depart = array()){
    $departId = array();
    foreach($depart as $k=>$d){
        $departId[] = $k;
    }
    $result = basicMysqliQuery('uatme_oa_system_department', ' WHERE parent_id IN ('.implode(',',$departId).') ');
    if(count($result) > 0){
        $depart = $depart + getOwnedDepartment($result);
    }
    return $depart;
}

//Description: get the date period SQL string by select a month name
function getDateSQLByMonth($yearSelect = 0, $timeSelect = 0){
    $yearsql = '';
    if(!$yearSelect){
        $yearSelect = date('Y');
    }
    switch($timeSelect){
        case '0':
        case '':
            $yearsql = ' BETWEEN "'.$yearSelect.'-01-01 00:00:00" AND "'.$yearSelect.'-12-31 23:59:59"';
            break;
        case '1':
        case '2':
        case '3':
        case '4':
        case '5':
        case '6':
        case '7':
        case '8':
        case '9':
            $yearsql = ' BETWEEN "'.$yearSelect.'-0'.$timeSelect.'-01 00:00:00" AND "'.$yearSelect.'-0'.$timeSelect.'-31 23:59:59"';
            break;
        case '10':
        case '11':
        case '12':
            $yearsql = ' BETWEEN "'.$yearSelect.'-'.$timeSelect.'-01 00:00:00" AND "'.$yearSelect.'-'.$timeSelect.'-31 23:59:59"';
            break;
    }
    return $yearsql;
}

//Description: get the date period SQL string by select a quarter name
function getDateSQLByQuarter($yearSelect = 0, $timeSelect = 0){
    $yearsql = '';
    if(!$yearSelect){
        $yearSelect = date('Y');
    }
    switch($timeSelect){
        case '0':
        case '':
            $yearsql = ' BETWEEN "'.$yearSelect.'-01-01 00:00:00" AND "'.$yearSelect.'-12-31 23:59:59"';
            break;
        case '1':
        case '2':
        case '3':
            $yearsql = ' BETWEEN "'.$yearSelect.'-0'.(($timeSelect-1)*3+1).'-01 00:00:00" AND "'.$yearSelect.'-0'.($timeSelect*3).'-31 23:59:59"';
            break;
        case '4':
            $yearsql = ' BETWEEN "'.$yearSelect.'-'.(($timeSelect-1)*3+1).'-01 00:00:00" AND "'.$yearSelect.'-'.($timeSelect*3).'-31 23:59:59"';
            break;
    }
    return $yearsql;
}

function getUsedAnnualLeave($employee_id = 0, $ifOnlyApproved = 1){
    if($employee_id > 0){
        global $mysqli;
        $usedAnnualLeave= 0;
        $sql = 'SELECT start, end FROM uatme_oa_hr_leave_apply WHERE employee_id="'.$employee_id.'" AND (status IN (1'.((!$ifOnlyApproved)?',2':'').')) AND ((start BETWEEN "'.Date('Y').'-01-01 00:00:00" AND "'.Date('Y').'-12-31 23:59:59") OR (end BETWEEN "'.Date('Y').'-01-01 00:00:00" AND "'.Date('Y').'-12-31 23:59:59")) AND type=(SELECT id FROM uatme_oa_hr_leave_type WHERE name="年假" LIMIT 1)';
        $result = $mysqli->query($sql);
        if($result->num_rows > 0){
            while($array = $result->fetch_assoc()){
                $return = caculateDay($array['start'], $array['end']);
                $usedAnnualLeave += $return['day'];
            }
        }
        return $usedAnnualLeave;
    }else{
        return 'N/A';
    }    
}

function getRestAnnualLeave($employee_id = 0){
    if($employee_id > 0){
        $totalAnnualLeave = 0;
        $usedAnnualLeave = getUsedAnnualLeave($employee_id);
        $totalAnnualLeaveResult = basicMysqliQuery('uatme_oa_hr_leave_employee', ' WHERE employee_id="'.$employee_id.'" AND leave_type_id=(SELECT id FROM uatme_oa_hr_leave_type WHERE name="年假" LIMIT 1) AND ifavailable=1 ');
        foreach($totalAnnualLeaveResult as $t){
            $totalAnnualLeave += $t['count'];
        }
        $restAnnualLeave = $totalAnnualLeave - $usedAnnualLeave;
        return $restAnnualLeave;
    }else{
        return 'N/A';
    }
}