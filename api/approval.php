<?php
//include db config and connect database
require_once('../db.config.php');
require_once('../smtp.config.php');//import shared class files
foreach(glob('../include/class/*/class.*.php') as $f){
	require_once $f;
}
//verify the author key, see if visitor is a validated user.
$taskId = $_GET['i'];
$executerId = $_GET['e'];
$authorKey = $_GET['k'];
$sql = 'SELECT * FROM uatme_oa_workflow_task WHERE id="'.$taskId.'" AND employee_id="'.$executerId.'" AND author_key="'.$authorKey.'"';
$result = $mysqli->query($sql);
//approval start
//if user is validated
if($result->num_rows == 1){
	while($array = $result->fetch_assoc()){
		switch($_GET['s']){
			case 'leave':
				switch($_GET['a']){
					case 'agree':
						echo 'leaveagree';
						$sql = 'UPDATE uatme_oa_workflow_task SET status="2", author_key="'.md5(sha1($array['author_key'].date('Y-m-d H:i:s'))).'" WHERE id="'.$array['id'].'"';
						$mysqli->query($sql);
						if($array['ifend']!=1){
							//find the next task, send the mail
							$sql = 'SELECT * FROM uatme_oa_workflow_task WHERE document_typelv1_id="'.$array['document_typelv1_id'].'" AND document_id="'.$array['document_id'].'" ORDER BY orderby LIMIT 1';
							$result = $mysqli->query($sql);
						}else{
							//update document status, record the leave, send mail to applyer and asistant
							//search which db table should be used
							$sql = 'SELECT * FROM uatme_oa_workflow_document_typelv1 WHERE id="'.$array['document_typelv1_id'].'"';
							$result = $mysqli->query($sql);
							if($result->num_rows == 1){
								while($array1 = $result->fetch_assoc()){									
									//update document status
									$sql = 'UPDATE '.$array1['db_table_name'].' SET status="1" WHERE id="'.$array['document_id'].'"';
									if($mysqli->query($sql)){
										//get task info
										$sql = 'SELECT * FROM '.$array1['db_table_name'].' WHERE id="'.$array['document_id'].'"';
										$result = $mysqli->query($sql);
										if($result->num_rows == 1){
											while($array = $result->fetch_assoc()){
												$apply = $array;
											}
										}
										//get leave type info
										$sql = 'SELECT id,name FROM uatme_oa_hr_leave_type';
										$result = $mysqli->query($sql);
										if($result->num_rows > 0){
											while($array = $result->fetch_assoc()){
												$leaveType[$array['id']] = $array['name'];
											}
										}
										//get employee info
										$sql = 'SELECT id,name,namezh FROM uatme_oa_system_employee';
										$result = $mysqli->query($sql);
										if($result->num_rows > 0){
											while($array = $result->fetch_assoc()){
												$employee[$array['id']] = array('name'=>$array['name'],'namezh'=>$array['namezh']);
											}
										}
										//get email, name info of applyer
										$sql = 'SELECT email,name,namezh FROM uatme_oa_system_employee WHERE id="'.$apply['employee_id'].'"';
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
												</table></p>
												</body>');
												mailSendMail($SMTPConfig);
											}
										}
									}
								}
							}
						}
						break;
					case 'deny':
						echo 'leavedeny';
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