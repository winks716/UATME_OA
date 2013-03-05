<?php
//include db config and connect database
require_once('../db.config.php');
require_once('../smtp.config.php');
//import shared class files
foreach(glob('../include/class/*/class.*.php') as $f){
	require_once $f;
}
require_once('../include/mail.function.php');
//verify the author key, see if visitor is a validated user.
$taskId = $_POST['i'];
$executerId = $_POST['e'];
$authorKey = $_POST['k'];
$sql = 'SELECT * FROM uatme_oa_workflow_task WHERE id="'.$taskId.'" AND employee_id="'.$executerId.'" AND author_key="'.$authorKey.'"';
$result = $mysqli->query($sql);
//approval start
//if user is validated
if($result->num_rows == 1){
	while($array = $result->fetch_assoc()){
		switch($_POST['s']){
			case 'leave':
				switch($_POST['a']){
					case 'agree':
						$sql = 'UPDATE uatme_oa_workflow_task SET status="2", author_key="'.md5(sha1($array['author_key'].date('Y-m-d H:i:s'))).'", updated_date="'.date('Y-m-d H:i:s').'", comment="'.$_POST['comment'].'" WHERE id="'.$array['id'].'"';
						$mysqli->query($sql);
						if($array['ifend']!=1){
							//find the next task, send the mail
							$sql = 'SELECT * FROM uatme_oa_workflow_task WHERE document_typelv1_id="'.$array['document_typelv1_id'].'" AND document_id="'.$array['document_id'].'" ORDER BY orderby LIMIT 1';
							$result = $mysqli->query($sql);
							//echo $sql;
						}else{
							//update document status, record the leave, send mail to applyer and asistant
							//search which db table should be used
							$sql = 'SELECT * FROM uatme_oa_workflow_document_typelv1 WHERE id="'.$array['document_typelv1_id'].'"';
							$result = $mysqli->query($sql);
							//echo $sql;
							if($result->num_rows == 1){
								while($array1 = $result->fetch_assoc()){									
									//update document status
									$sql = 'UPDATE '.$array1['db_table_name'].' SET status="1", comment="'.$_POST['comment'].'" WHERE id="'.$array['document_id'].'"';
									//echo $sql;
									if($mysqli->query($sql)){
										//update employee's leave number
										//get task info
										$sql = 'SELECT * FROM '.$array1['db_table_name'].' WHERE id="'.$array['document_id'].'"';
										//echo $sql;
										$result = $mysqli->query($sql);
										if($result->num_rows == 1){
											while($array = $result->fetch_assoc()){
												$apply = $array;
											}
										}
										//get leave type info
										$sql = 'SELECT id,name FROM uatme_oa_hr_leave_type';
										//echo $sql;
										$result = $mysqli->query($sql);
										if($result->num_rows > 0){
											while($array = $result->fetch_assoc()){
												$leaveType[$array['id']] = $array['name'];
											}
										}
										//get employee info
										$sql = 'SELECT id,name,namezh FROM uatme_oa_system_employee';
										//echo $sql;
										$result = $mysqli->query($sql);
										if($result->num_rows > 0){
											while($array = $result->fetch_assoc()){
												$employee[$array['id']] = array('name'=>$array['name'],'namezh'=>$array['namezh']);
											}
										}
										//get email, name info of applyer
										$sql = 'SELECT email,name,namezh FROM uatme_oa_system_employee WHERE id="'.$apply['employee_id'].'"';
										//echo $sql;
										$result = $mysqli->query($sql);
										if($result->num_rows == 1){
											while($array = $result->fetch_assoc()){
												//send the mail to applyer and asistant
												$SMTPConfig = array(
														'sendto' => array(array('mail'=>$array['email'],'name'=>$array['name'])),
														'subject' => $array['namezh'].'('.$array['name'].')的假期申请已经审批通过',
														'body' => '<head>
												<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
												</head>
												<body>
												<h3>'.$array['namezh'].'('.$array['name'].')的休假申请已经审批通过。</h3><br/>
												<h4>详情如下</h4>
												<p><table>
													<tr><th>假期类型</th><th>休假时间</th><th>指定代办</th><th>事由</th></tr>
													<tr><td>'.$leaveType[$apply["type"]].'</td><td>'.$apply['start'].' 至 '.$apply['end'].'</td><td>'.$employee[$apply['alternative_employee_id']]['namezh'].'('.$employee[$apply['alternative_employee_id']]['name'].')</td><td>'.$apply['reason'].'</td></tr>
													<tr><th colspan="4">
														审批备注:<br/>
														'.$employee[$apply['apply_employee_id']]['namezh'].'('.$employee[$apply['apply_employee_id']]['name'].')："'.$_POST['comment'].'"
														</th></tr>
												</table></p>
												</body>');
												//echo '<pre>';
												//print_r($SMTPConfig);
												//echo '</pre>';
												mailSendMail($SMTPConfig);
												echo '<head>
												<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
												</head>
												<body>假期申请审批通过</body>';
											}
										}
									}
								}
							}
						}
						break;
					case 'deny':
						$sql = 'UPDATE uatme_oa_workflow_task SET status="2", author_key="'.md5(sha1($array['author_key'].date('Y-m-d H:i:s'))).'", updated_date="'.date('Y-m-d H:i:s').'" WHERE id="'.$array['id'].'"';
						$mysqli->query($sql);
						if($array['ifend']!=1){
							//find the next task, send the mail
							$sql = 'SELECT * FROM uatme_oa_workflow_task WHERE document_typelv1_id="'.$array['document_typelv1_id'].'" AND document_id="'.$array['document_id'].'" ORDER BY orderby LIMIT 1';
							$result = $mysqli->query($sql);
							//echo $sql;
						}else{
							//update document status, record the leave, send mail to applyer and asistant
							//search which db table should be used
							$sql = 'SELECT * FROM uatme_oa_workflow_document_typelv1 WHERE id="'.$array['document_typelv1_id'].'"';
							$result = $mysqli->query($sql);
							//echo $sql;
							if($result->num_rows == 1){
								while($array1 = $result->fetch_assoc()){									
									//update document status
									$sql = 'UPDATE '.$array1['db_table_name'].' SET status="2", comment="'.$_POST['comment'].'" WHERE id="'.$array['document_id'].'"';
									//echo $sql;
									if($mysqli->query($sql)){
										//get task info
										$sql = 'SELECT * FROM '.$array1['db_table_name'].' WHERE id="'.$array['document_id'].'"';
										//echo $sql;
										$result = $mysqli->query($sql);
										if($result->num_rows == 1){
											while($array = $result->fetch_assoc()){
												$apply = $array;
											}
										}
										//get leave type info
										$sql = 'SELECT id,name FROM uatme_oa_hr_leave_type';
										//echo $sql;
										$result = $mysqli->query($sql);
										if($result->num_rows > 0){
											while($array = $result->fetch_assoc()){
												$leaveType[$array['id']] = $array['name'];
											}
										}
										//get employee info
										$sql = 'SELECT id,name,namezh FROM uatme_oa_system_employee';
										//echo $sql;
										$result = $mysqli->query($sql);
										if($result->num_rows > 0){
											while($array = $result->fetch_assoc()){
												$employee[$array['id']] = array('name'=>$array['name'],'namezh'=>$array['namezh']);
											}
										}
										//get email, name info of applyer
										$sql = 'SELECT email,name,namezh FROM uatme_oa_system_employee WHERE id="'.$apply['employee_id'].'"';
										//echo $sql;
										$result = $mysqli->query($sql);
										if($result->num_rows == 1){
											while($array = $result->fetch_assoc()){
												//send the mail to applyer and asistant
												$SMTPConfig = array(
														'sendto' => array(array('mail'=>$array['email'],'name'=>$array['name'])),
														'subject' => $array['namezh'].'('.$array['name'].')的假期申请已被退回',
														'body' => '<head>
												<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
												</head>
												<body>
												<h3>'.$array['namezh'].'('.$array['name'].')的休假申请已被退回。</h3><br/>
												<h4>详情如下</h4>
												<p><table>
													<tr><th>假期类型</th><th>休假时间</th><th>指定代办</th><th>事由</th></tr>
													<tr><td>'.$leaveType[$apply["type"]].'</td><td>'.$apply['start'].' 至 '.$apply['end'].'</td><td>'.$employee[$apply['alternative_employee_id']]['namezh'].'('.$employee[$apply['alternative_employee_id']]['name'].')</td><td>'.$apply['reason'].'</td></tr>
													<tr><th colspan="4">
														审批备注:<br/>
														'.$employee[$apply['apply_employee_id']]['namezh'].'('.$employee[$apply['apply_employee_id']]['name'].')："'.$_POST['comment'].'"
														</th></tr>
												</table></p>
												</body>');
												//echo '<pre>';
												//print_r($SMTPConfig);
												//echo '</pre>';
												mailSendMail($SMTPConfig);
												echo '<head>
												<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
												</head>
												<body>假期申请审批拒绝</body>';
											}
										}
									}
								}
							}
						}
						break;
				}
				break;
				
			//差旅审批
			case 'travel':
				switch($_POST['a']){
					case 'agree':
						$sql = 'UPDATE uatme_oa_workflow_task SET status="2", author_key="'.md5(sha1($array['author_key'].date('Y-m-d H:i:s'))).'", updated_date="'.date('Y-m-d H:i:s').'", comment="'.$_POST['comment'].'" WHERE id="'.$array['id'].'"';
						$mysqli->query($sql);
						if($array['ifend']!=1){
							//find the next task, send the mail
							$sql = 'SELECT * FROM uatme_oa_workflow_task WHERE document_typelv1_id="'.$array['document_typelv1_id'].'" AND document_id="'.$array['document_id'].'" ORDER BY orderby LIMIT 1';
							$result = $mysqli->query($sql);
							//echo $sql;
						}else{
							//update document status, record the leave, send mail to applyer and asistant
							//search which db table should be used
							$sql = 'SELECT * FROM uatme_oa_workflow_document_typelv1 WHERE id="'.$array['document_typelv1_id'].'"';
							$result = $mysqli->query($sql);
							//echo $sql;
							if($result->num_rows == 1){
								while($array1 = $result->fetch_assoc()){									
									//update document status
									$sql = 'UPDATE '.$array1['db_table_name'].' SET status="1", comment="'.$_POST['comment'].'" WHERE id="'.$array['document_id'].'"';
									//echo $sql;
									if($mysqli->query($sql)){
										//update employee's leave number
										//get task info
										$sql = 'SELECT * FROM '.$array1['db_table_name'].' WHERE id="'.$array['document_id'].'"';
										//echo $sql;
										$result = $mysqli->query($sql);
										if($result->num_rows == 1){
											while($array = $result->fetch_assoc()){
												$apply = $array;
											}
										}
										//get leave type info
										$sql = 'SELECT id,name FROM uatme_oa_system_currency';
										//echo $sql;
										$result = $mysqli->query($sql);
										if($result->num_rows > 0){
											while($array = $result->fetch_assoc()){
												$currency[$array['id']] = $array['name'];
											}
										}
										//get employee info
										$sql = 'SELECT id,name,namezh FROM uatme_oa_system_employee';
										//echo $sql;
										$result = $mysqli->query($sql);
										if($result->num_rows > 0){
											while($array = $result->fetch_assoc()){
												$employee[$array['id']] = array('name'=>$array['name'],'namezh'=>$array['namezh']);
											}
										}
										//get email, name info of applyer
										$sql = 'SELECT email,name,namezh FROM uatme_oa_system_employee WHERE id="'.$apply['employee_id'].'"';
										//echo $sql;
										$result = $mysqli->query($sql);
										if($result->num_rows == 1){
											while($array = $result->fetch_assoc()){
												//send the mail to applyer and asistant
												$SMTPConfig = array(
														'sendto' => array(array('mail'=>$array['email'],'name'=>$array['name'])),
														'subject' => $array['namezh'].'('.$array['name'].')的差旅申请已经审批通过',
														'body' => '<head>
												<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
												</head>
												<body>
												<h3>'.$array['namezh'].'('.$array['name'].')的差旅申请已经审批通过。</h3><br/>
												<h4>详情如下</h4>
												<p><table>
													<tr><th>目的地</th><th>差旅时间</th><th>预估费用</th><th>指定代办</th><th>事由</th></tr>
													<tr><td>'.$apply["target"].'</td><td>'.$apply['start'].' 至 '.$apply['end'].'</td><td>'.$currency[$apply["currency_id"]].$apply["expense"].'</td><td>'.$employee[$apply['alternative_employee_id']]['namezh'].'('.$employee[$apply['alternative_employee_id']]['name'].')</td><td>'.$apply['reason'].'</td></tr>
													<tr><th colspan="4">
														审批备注:<br/>
														'.$employee[$apply['apply_employee_id']]['namezh'].'('.$employee[$apply['apply_employee_id']]['name'].')："'.$_POST['comment'].'"
														</th></tr>
												</table></p>
												</body>');
												//echo '<pre>';
												//print_r($SMTPConfig);
												//echo '</pre>';
												mailSendMail($SMTPConfig);
												echo '<head>
												<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
												</head>
												<body>差旅申请审批通过</body>';
											}
										}
									}
								}
							}
						}
						break;
					case 'deny':
						$sql = 'UPDATE uatme_oa_workflow_task SET status="2", author_key="'.md5(sha1($array['author_key'].date('Y-m-d H:i:s'))).'", updated_date="'.date('Y-m-d H:i:s').'" WHERE id="'.$array['id'].'"';
						$mysqli->query($sql);
						if($array['ifend']!=1){
							//find the next task, send the mail
							$sql = 'SELECT * FROM uatme_oa_workflow_task WHERE document_typelv1_id="'.$array['document_typelv1_id'].'" AND document_id="'.$array['document_id'].'" ORDER BY orderby LIMIT 1';
							$result = $mysqli->query($sql);
							//echo $sql;
						}else{
							//update document status, record the leave, send mail to applyer and asistant
							//search which db table should be used
							$sql = 'SELECT * FROM uatme_oa_workflow_document_typelv1 WHERE id="'.$array['document_typelv1_id'].'"';
							$result = $mysqli->query($sql);
							//echo $sql;
							if($result->num_rows == 1){
								while($array1 = $result->fetch_assoc()){									
									//update document status
									$sql = 'UPDATE '.$array1['db_table_name'].' SET status="2", comment="'.$_POST['comment'].'" WHERE id="'.$array['document_id'].'"';
									//echo $sql;
									if($mysqli->query($sql)){
										//get task info
										$sql = 'SELECT * FROM '.$array1['db_table_name'].' WHERE id="'.$array['document_id'].'"';
										//echo $sql;
										$result = $mysqli->query($sql);
										if($result->num_rows == 1){
											while($array = $result->fetch_assoc()){
												$apply = $array;
											}
										}
										//get leave type info
										$sql = 'SELECT id,name FROM uatme_oa_system_currency';
										//echo $sql;
										$result = $mysqli->query($sql);
										if($result->num_rows > 0){
											while($array = $result->fetch_assoc()){
												$currency[$array['id']] = $array['name'];
											}
										}
										//get employee info
										$sql = 'SELECT id,name,namezh FROM uatme_oa_system_employee';
										//echo $sql;
										$result = $mysqli->query($sql);
										if($result->num_rows > 0){
											while($array = $result->fetch_assoc()){
												$employee[$array['id']] = array('name'=>$array['name'],'namezh'=>$array['namezh']);
											}
										}
										//get email, name info of applyer
										$sql = 'SELECT email,name,namezh FROM uatme_oa_system_employee WHERE id="'.$apply['employee_id'].'"';
										//echo $sql;
										$result = $mysqli->query($sql);
										if($result->num_rows == 1){
											while($array = $result->fetch_assoc()){
												//send the mail to applyer and asistant
												$SMTPConfig = array(
														'sendto' => array(array('mail'=>$array['email'],'name'=>$array['name'])),
														'subject' => $array['namezh'].'('.$array['name'].')的差旅申请已被退回',
														'body' => '<head>
												<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
												</head>
												<body>
												<h3>'.$array['namezh'].'('.$array['name'].')的差旅申请已被退回。</h3><br/>
												<h4>详情如下</h4>
												<p><table>
													<tr><th>目的地</th><th>差旅时间</th><th>预估费用</th><th>指定代办</th><th>事由</th></tr>
													<tr><td>'.$apply['target'].'</td><td>'.$apply['start'].' 至 '.$apply['end'].'</td><td>'.$currency[$apply['currency_id']].$apply['expense'].'</td><td>'.$employee[$apply['alternative_employee_id']]['namezh'].'('.$employee[$apply['alternative_employee_id']]['name'].')</td><td>'.$apply['reason'].'</td></tr>
													<tr><th colspan="4">
														审批备注:<br/>
														'.$employee[$apply['apply_employee_id']]['namezh'].'('.$employee[$apply['apply_employee_id']]['name'].')："'.$_POST['comment'].'"
														</th></tr>
												</table></p>
												</body>');
												//echo '<pre>';
												//print_r($SMTPConfig);
												//echo '</pre>';
												mailSendMail($SMTPConfig);
												echo '<head>
												<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
												</head>
												<body>差旅申请审批拒绝</body>';
											}
										}
									}
								}
							}
						}
						break;
				}
				break;
			default:
				exit('You are forbidden to access this page, please contact your system administrator!');
				break;
		}	
	}
}else{//if user is illegal
	exit('You are forbidden to access this page, please contact your system administrator!');
}