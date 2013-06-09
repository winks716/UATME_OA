<?php 
if($_SESSION['if_system_admin'] == 1){
	switch($A){
		case 'employee.list':
			$sql = 'SELECT * from uatme_oa_system_department';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					$department[$array['id']] = $array['name'];
				}
			}
			$sql = 'SELECT * from uatme_oa_system_employee WHERE id!=1 ORDER BY ifleave ASC, name ASC';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					$array['department_name'] = $department[$array['department_id']];
					$assign['employee'][] = $array;
				}
			}			
			$smarty->assign($assign);
			$smarty->display('system/employee.list.html');
			break;
		case 'employee.add.init':
			$sql = 'SELECT * from uatme_oa_system_department ORDER BY location_id ASC';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					$assign['department'][] = $array;
				}
			}	
			$smarty->assign($assign);
			$smarty->display('system/employee.add.html');
			break;
		case 'employee.add.save':
			$sql = 'INSERT INTO uatme_oa_system_employee (name, namezh, short_name, email, employee_no, department_id, ifleave) 
					VALUES ("'.$_POST['name'].'", 
							"'.$_POST['namezh'].'", 
							"'.$_POST['short_name'].'",
							"'.$_POST['email'].'",
							"'.$_POST['employee_no'].'",
							"'.$_POST['department_id'].'",
							"'.$_POST['ifleave'].'")';
			if($mysqli->query($sql)){
				if($mysqli->insert_id > 0){
					$httpstatus = 200;
					$msg = '添加用户成功';
				}else{
					$httpstatus = 500;
					$error = '服务器忙，请稍后再试';
				}
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'employee.delete':
			$sql = 'DELETE FROM uatme_oa_system_employee WHERE id="'.$_POST['id'].'" LIMIT 1';
			if($mysqli->query($sql)){
				if($mysqli->affected_rows == 1){
					$httpstatus = 200;
					$msg = '删除用户成功';
				}else{
					$httpstatus = 500;
					$error = '服务器忙，请稍后再试';
				}
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'employee.edit.init':
			$sql = 'SELECT * from uatme_oa_system_department ORDER BY location_id ASC';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					$assign['department'][] = $array;
				}
			}
			$sql = 'SELECT * from uatme_oa_system_employee WHERE id="'.$_POST['id'].'"';
			$result = $mysqli->query($sql);
			if($result->num_rows == 1){
				while($array = $result->fetch_assoc()){
					$assign['employee'][] = $array;
				}
			}	
			$smarty->assign($assign);
			$smarty->display('system/employee.edit.html');
			break;
		case 'employee.save':
			$sql = 'UPDATE uatme_oa_system_employee
					SET name="'.$_POST['name'].'", 
							namezh="'.$_POST['namezh'].'", 
									short_name="'.$_POST['short_name'].'", 
											email="'.$_POST['email'].'", 
													employee_no="'.$_POST['employee_no'].'",
															department_id="'.$_POST['department_id'].'",
																	ifleave="'.$_POST['ifleave'].'"  
																		 WHERE id="'.$_POST['id'].'" LIMIT 1';
			if($mysqli->query($sql)){
				$httpstatus = 200;
				$msg = '保存用户成功';
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'employee.privilege.edit':
			//get employee's privilege
			$sql = 'SELECT privilege_id_list,id from uatme_oa_system_employee WHERE id="'.$_POST['id'].'"';
			$result = $mysqli->query($sql);
			if($result->num_rows == 1){
				while($array = $result->fetch_assoc()){
					$employeePrivilege = $array['privilege_id_list'];
					$assign['id'] = $array['id'];
				}
			}
			$employeePrivilege = explode(',',$employeePrivilege);
			//get privileges information
			$sql = 'SELECT * from uatme_oa_system_privilege';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					if(in_array($array['id'], $employeePrivilege)){
						$array['assigned'] = 1;
					}else{
						$array['assigned'] = 0;
					}
					$assign['privilege'][] = $array;
				}
			}
			$smarty->assign($assign);
			$smarty->display('system/employee.privilege.edit.html');
			break;
		case 'employee.privilege.save':
			$privilege = implode(',',$_POST['privilege']);
			$sql = 'UPDATE uatme_oa_system_employee SET privilege_id_list="'.$privilege.'" WHERE id="'.$_POST['id'].'" LIMIT 1';
			if($mysqli->query($sql)){
				$httpstatus = 200;
				$msg = '保存权限成功';
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'employee.position.edit':
			//get employee's privilege
			$sql = 'SELECT position_id_list,id from uatme_oa_system_employee WHERE id="'.$_POST['id'].'"';
			$result = $mysqli->query($sql);
			if($result->num_rows == 1){
				while($array = $result->fetch_assoc()){
					$employeePosition = $array['position_id_list'];
					$assign['id'] = $array['id'];
				}
			}
			$employeePosition = explode(',',$employeePosition);
			//get privileges information
			$sql = 'SELECT * from uatme_oa_system_position';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					if(in_array($array['id'], $employeePosition)){
						$array['assigned'] = 1;
					}else{
						$array['assigned'] = 0;
					}
					$assign['position'][] = $array;
				}
			}
			$smarty->assign($assign);
			$smarty->display('system/employee.position.edit.html');
			break;
		case 'employee.position.save':
			$position = implode(',',$_POST['position']);
			$sql = 'UPDATE uatme_oa_system_employee SET position_id_list="'.$position.'" WHERE id="'.$_POST['id'].'" LIMIT 1';
			if($mysqli->query($sql)){
				$httpstatus = 200;
				$msg = '保存权限成功';
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'employee.password.reset':
			$sql = 'UPDATE uatme_oa_system_employee SET password="'.md5(sha1('123456')).'" WHERE id="'.$_POST['id'].'" LIMIT 1';
			if($mysqli->query($sql)){
				$httpstatus = 200;
				$msg = '重置密码成功';
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'announce.list':
			$sql = 'SELECT * from uatme_oa_system_announce ORDER BY id DESC LIMIT 20';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					$assign['announce'][] = $array;
				}
			}				
			$smarty->assign($assign);
			$smarty->display('system/announce.list.html');
			break;
		case 'announce.add':
			$sql = 'INSERT INTO uatme_oa_system_announce (title, detail, start_date, end_date, publisher_employee_id) 
					VALUES ("'.$_POST['title'].'", 
							"'.$_POST['detail'].'", 
							"'.$_POST['start_date'].'",
							"'.$_POST['end_date'].'",
							"'.$_SESSION['employee_id'].'")';
			if($mysqli->query($sql)){
				if($mysqli->insert_id > 0){
					$httpstatus = 200;
					$msg = '添加通告成功';
				}else{
					$httpstatus = 500;
					$error = '服务器忙，请稍后再试';
				}
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'announce.delete':
			$sql = 'DELETE FROM uatme_oa_system_announce WHERE id="'.$_POST['id'].'" LIMIT 1';
			if($mysqli->query($sql)){
				if($mysqli->affected_rows == 1){
					$httpstatus = 200;
					$msg = '删除通告成功';
				}else{
					$httpstatus = 500;
					$error = '服务器忙，请稍后再试';
				}
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'announce.save':
			$sql = 'UPDATE uatme_oa_system_announce 
					SET title="'.$_POST['title'].'", 
							detail="'.$_POST['detail'].'", 
									start_date="'.$_POST['start_date'].'", 
											end_date="'.$_POST['end_date'].'", 
													publisher_employee_id="'.$_SESSION['employee_id'].'"   
																 WHERE id="'.$_POST['id'].'" LIMIT 1';
			if($mysqli->query($sql)){
				$httpstatus = 200;
				$msg = '保存通告成功';
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'country.list':
			$sql = 'SELECT * from uatme_oa_system_country';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					$assign['country'][] = $array;
				}
			}			
			$smarty->assign($assign);
			$smarty->display('system/country.list.html');
			break;
		case 'country.add':
			$sql = 'INSERT INTO uatme_oa_system_country(name, nameshort, available) VALUES("'.$_POST['name'].'","'.$_POST['nameshort'].'","'.($_POST['available']==1?1:0).'")';
			$mysqli->query($sql);
			if($mysqli->insert_id > 0){
				$httpstatus = 200;
				$msg = '添加国家成功！';
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试！';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'country.save':
			$sql = 'UPDATE uatme_oa_system_country
					SET name="'.$_POST['name'].'", 
							nameshort="'.$_POST['nameshort'].'",  
									available="'.($_POST['available']==1?1:0).'" 
											WHERE id="'.$_POST['id'].'" LIMIT 1';
			if($mysqli->query($sql)){
				$httpstatus = 200;
				$msg = '保存国家成功';
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'location.list':
			$sql = 'SELECT * FROM uatme_oa_system_country';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					$assign['country'][] = $array;
				}
			}	
			$sql = 'SELECT * from uatme_oa_system_location';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					$assign['location'][] = $array;
				}
			}			
			$smarty->assign($assign);
			$smarty->display('system/location.list.html');
			break;
		case 'location.add':
			$sql = 'INSERT INTO uatme_oa_system_location(name, nameshort, country_id, available) VALUES("'.$_POST['name'].'","'.$_POST['nameshort'].'","'.$_POST['parentid'].'","'.($_POST['available']==1?1:0).'")';
			$mysqli->query($sql);
			if($mysqli->insert_id > 0){
				$httpstatus = 200;
				$msg = '添加地区成功！';
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试！';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'location.save':
			$sql = 'UPDATE uatme_oa_system_location
					SET name="'.$_POST['name'].'", 
							nameshort="'.$_POST['nameshort'].'", 
									country_id="'.$_POST['parentid'].'",  
											available="'.($_POST['available']==1?1:0).'" 
													WHERE id="'.$_POST['id'].'" LIMIT 1';
			if($mysqli->query($sql)){
				$httpstatus = 200;
				$msg = '保存地区成功';
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试！';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'department.list':	
			$sql = 'SELECT * from uatme_oa_system_employee WHERE id!=1 AND ifavailable=1 ORDER BY ifleave ASC, name ASC';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					$assign['employee'][] = $array;
				}
			}	
			$sql = 'SELECT * FROM uatme_oa_system_location';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					$assign['location'][] = $array;
				}
			}
			$sql = 'SELECT * from uatme_oa_system_department';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					$assign['department'][] = $array;
				}
			}			
			$smarty->assign($assign);
			$smarty->display('system/department.list.html');
			break;
		case 'department.add':
			$sql = 'INSERT INTO uatme_oa_system_department(name, nameshort, manager_employee_id, location_id, available) VALUES("'.$_POST['name'].'","'.$_POST['nameshort'].'","'.$_POST['manageremployeeid'].'","'.$_POST['parentid'].'","'.($_POST['available']==1?1:0).'")';
			$mysqli->query($sql);
			if($mysqli->insert_id > 0){
				$httpstatus = 200;
				$msg = '添加部门成功！';
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试！';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'department.save':
			$sql = 'UPDATE uatme_oa_system_department
					SET name="'.$_POST['name'].'", 
							nameshort="'.$_POST['nameshort'].'", 
									manager_employee_id="'.$_POST['manageremployeeid'].'",
											location_id="'.$_POST['parentid'].'",  
													available="'.($_POST['available']==1?1:0).'" 
															WHERE id="'.$_POST['id'].'" LIMIT 1';
			if($mysqli->query($sql)){
				$httpstatus = 200;
				$msg = '保存部门成功';
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试！';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'position.list':
			$sql = 'SELECT * FROM uatme_oa_system_department';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					$assign['department'][] = $array;
				}
			}
			$sql = 'SELECT * FROM uatme_oa_system_position';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					$assign['position'][] = $array;
				}
			}				
			$smarty->assign($assign);
			$smarty->display('system/position.list.html');
			break;
		case 'position.add':
			$sql = 'INSERT INTO uatme_oa_system_position(name, nameshort, department_id, available) VALUES("'.$_POST['name'].'","'.$_POST['nameshort'].'","'.$_POST['parentid'].'","'.($_POST['available']==1?1:0).'")';
			$mysqli->query($sql);
			if($mysqli->insert_id > 0){
				$httpstatus = 200;
				$msg = '添加职位成功！';
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试！';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'position.save':
			$sql = 'UPDATE uatme_oa_system_position
					SET name="'.$_POST['name'].'", 
							nameshort="'.$_POST['nameshort'].'", 
									department_id="'.$_POST['parentid'].'",  
											available="'.($_POST['available']==1?1:0).'" 
													WHERE id="'.$_POST['id'].'" LIMIT 1';
			if($mysqli->query($sql)){
				$httpstatus = 200;
				$msg = '保存职位成功';
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试！';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'privilege.list':			
			$smarty->assign($assign);
			$smarty->display('system/privilege.list.html');
			break;
		case 'invoice.list':			
			$smarty->assign($assign);
			$smarty->display('system/invoice.list.html');
			break;
		case 'currency.list':
			$sql = 'SELECT * from uatme_oa_system_currency WHERE available=1 ORDER BY orderby';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					$assign['currency'][] = $array;
				}
			}		
			$smarty->assign($assign);
			$smarty->display('system/currency.list.html');
			break;
		case 'currency.add':
			$sql = 'SELECT orderby FROM uatme_oa_system_currency ORDER BY orderby DESC limit 1';
			$result = $mysqli->query($sql);
			if($result->num_rows == 1){
				while($array=$result->fetch_assoc()){
					$orderby = $array['orderby']+1;
				}
			}
			$sql = 'INSERT INTO uatme_oa_system_currency (name, symbol, rate, date, orderby) 
					VALUES ("'.$_POST['name'].'", 
							"'.$_POST['symbol'].'", 
							"'.$_POST['rate'].'",
							"'.date('Y-m-d H:i:s').'",
							"'.$orderby.'")';
			if($mysqli->query($sql)){
				if($mysqli->insert_id > 0){
					$httpstatus = 200;
					$msg = '添加汇率成功';
				}else{
					$httpstatus = 500;
					$error = '服务器忙，请稍后再试';
				}
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'currency.save':
			$sql = 'SELECT * FROM uatme_oa_system_currency WHERE id="'.$_POST['id'].'" AND available=1';
			$result = $mysqli->query($sql);
			if($result->num_rows == 1){
				while($array = $result->fetch_assoc()){
					$name = $array['name'];
					$symbol = $array['symbol'];
					$orderby = $array['orderby'];
					$sql = 'UPDATE uatme_oa_system_currency SET available=0 WHERE id="'.$_POST['id'].'" AND available=1';
					$mysqli->query($sql);
					$sql = 'INSERT INTO uatme_oa_system_currency (name, symbol, rate, date, orderby) VALUES("'.$name.'","'.$symbol.'","'.$_POST['rate'].'","'.date('Y-m-d H:i:s').'","'.$orderby.'")';
					if($mysqli->query($sql)){
						if($mysqli->insert_id > 0){
							$httpstatus = 200;
							$msg = '保存汇率成功';
						}else{
							$httpstatus = 500;
							$error = '服务器忙，请稍后再试';
						}
					}else{
						$httpstatus = 500;
						$error = '服务器忙，请稍后再试';
					}
				}
			}else{
				$httpstatus = 500;
				$error = '服务器忙，请稍后再试';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
		case 'company.structure.list':
		    $assign['employee'] = basicMysqliQuery('uatme_oa_system_employee');
		    foreach($assign['employee'] as $e){
		        $assign['departMember'][$e['department_id']][] = $e;
		    }
		    
		    $depart1 = basicMysqliQuery('uatme_oa_system_department', ' WHERE parent_id="0" ORDER BY id ');
		    foreach($depart1 as $k=>$v){
		        $depart1_id[] = $k;
		    }
		    $depart2 = basicMysqliQuery('uatme_oa_system_department', ' WHERE parent_id in ('.implode(',',$depart1_id).') ORDER BY id ');
		    foreach($depart2 as $k=>$v){
		        $depart2_id[] = $k;
		    }
		    $depart3 = basicMysqliQuery('uatme_oa_system_department', ' WHERE parent_id in ('.implode(',',$depart2_id).') ORDER BY id ');
		    $assign['depart1'] = $depart1;
		    $assign['depart2'] = $depart2;
		    $assign['depart3'] = $depart3;
			$smarty->assign($assign);
			$smarty->display('system/company.structure.list.html');
		    break;
	}		
}else{
	exit('您没有管理权限');
}
