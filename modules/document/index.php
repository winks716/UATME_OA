<?php
//获取国家，地区信息
$sql = 'SELECT * FROM uatme_oa_system_country';
$result = $mysqli->query($sql);
while($array = $result->fetch_assoc()){
	$sql_location = 'SELECT * FROM uatme_oa_system_location WHERE country_id="'.$array['id'].'"';
	$result_location = $mysqli->query($sql_location);
	while($array_location = $result_location->fetch_assoc()){
		$location[] = $array_location;
	}
	$assign['country'][] = array('id'=>$array['id'], 'name'=>$array['name'], 'location'=>$location);
}
//获取部门信息
$sql = 'SELECT * FROM uatme_oa_system_department';
$result = $mysqli->query($sql);
while($array = $result->fetch_assoc()){
	$assign['department'][] = $array;
}

//获取文档列表
//获取过滤参数，设定默认值
$country_id_condition = $_REQUEST['country_id'] ? ' AND country_id="'.$_REQUEST['country_id'].'" ' : '';
$location_id_condition = $_REQUEST['location_id'] ? ' AND location_id="'.$_REQUEST['location_id'].'" ' : '';
$department_id_condition = $_REQUEST['department_id'] ? ' AND department_id="'.$_REQUEST['department_id'].'" ' : '';
$keyword_condition = $_REQUEST['keyword'] ? ' AND name LIKE "%'.$_REQUEST['keyword'].'%" ' : '';
//将选择的值赋予模板
$assign['countrySelected'] = $_REQUEST['country_id'];
$assign['locationSelected'] = $_REQUEST['location_id'];
$assign['departmentSelected'] = $_REQUEST['department_id'];
$assign['keywordInput'] = $_REQUEST['keyword'];
//数据查询
$sql = 'SELECT * FROM uatme_oa_document_document WHERE 1' . $country_id_condition . $location_id_condition . $department_id_condition . $keyword_condition;
$result = $mysqli->query($sql);
if($result->num_rows > 0){
	while($array = $result->fetch_assoc()){
		$assign['document'][] = $array;
	}
}

$smarty->assign($assign);
$smarty->display('document/index.html');