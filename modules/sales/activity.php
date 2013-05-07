<?php
switch($A){
	case 'record':
	    $assign['customer'] = array(
	        array('id'=>1, 'name'=>'customer1'),
	        array('id'=>2, 'name'=>'customer2'),
	        array('id'=>3, 'name'=>'customer3'),
	        array('id'=>4, 'name'=>'customer4'),
	        array('id'=>5, 'name'=>'customer5'),
	        array('id'=>6, 'name'=>'customer6')
	    );
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
		    $sql = 'INSERT INTO ';
		    //$mysqli->query($sql);
		    $httpstatus = 200;
		    $msg = '活动记录成功！';
		}else{
		    $httpstatus = 500;
		    $error = '记录日期 ('.$date.') 超出允许范围 ('.$minday.'~'.$maxday.')';
		}
		sendResponse($httpstatus, $error, $msg);
		break;
	case 'delete':
		
		break;
	default:
		
		break;
}