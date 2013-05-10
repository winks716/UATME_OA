<?php
switch($A){
	case 'doc.company_rules':
		$assign['list_type'] = '规章制度';
		break;
	case 'doc.employee_handbook':
		$assign['list_type'] = '员工手册';
		break;
	case 'doc.branch_guide':
		$assign['list_type'] = '各地分公司指南';
		break;
	case 'doc.traffic_law':
		$assign['list_type'] = '交通法规';
		break;
	case 'doc.document_download':
		$assign['list_type'] = '表格下载';
		break;
	case 'doc.tech_handbook':
		$assign['list_type'] = '技术文档';
		break;
	case 'doc.purchase_handbook':
		$assign['list_type'] = '采购文档';
		break;
	case 'doc.hr_handbook':
		$assign['list_type'] = '人事文档';
		break;
	case 'doc.admin_handbook':
		$assign['list_type'] = '行政文档';
		break;
	case 'doc.sales_handbook':
		$assign['list_type'] = '销售文档';
		break;
}

if(isset($assign['list_type']) && $assign['list_type']!=''){
	//数据查询
	$sql = 'SELECT a.* FROM uatme_oa_document_document a LEFT JOIN uatme_oa_document_type b ON a.type_id=b.id WHERE b.name="'.$assign['list_type'].'" ORDER BY orderby ASC';
	$result = $mysqli->query($sql);
	if($result->num_rows > 0){
		while($array = $result->fetch_assoc()){
			$assign['document'][] = $array;
		}
	}
	//get document type list
	$sql = 'SELECT * FROM uatme_oa_document_type WHERE available=1';
	$result = $mysqli->query($sql);
	if($result->num_rows > 0){
		while($array = $result->fetch_assoc()){
			$assign['type'][] = $array;
		}
	}

	$smarty->assign($assign);
	$smarty->display('document/setup.html');
}