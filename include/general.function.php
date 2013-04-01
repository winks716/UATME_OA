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
------------------------------------------------------
function caculateDay($starttimestamp='0000-00-00 00:00:00', $endtimestamp='0000-00-00 00:00:00'){
//Description: caculate day number from start timestamp and end timestamp, type='yyyy-mm-dd hh:mm:ss'
//Acception: timestamp string
//Return: array containing the data of day, hour, minute, second
------------------------------------------------------
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
	$day = $hour/8;
	//echo $day,'天';
	$return = array('second'=>$second, 'minute'=>$minute, 'hour'=>$hour, 'day'=>$day);
	return $return;
}
