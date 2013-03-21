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
