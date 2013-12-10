<?php

/*
 * exam 
 */

switch($A){
    case 'exam':
        $examToken = (isset($_GET['t']) && $_GET['t']!='') ? $_GET['t'] : '';
        if($examToken){
            //find exam match the token, then show the exam
            $sql = 'SELECT * FROM uatme_oa_oexam_exam_employee WHERE employeeId="'.$_SESSION['employee_id'].'" AND examToken="'.$examToken.'"';
            $result = $mysqli->query($sql);
            if($result->num_rows > 0){
            	while($array = $result->fetch_assoc()){
            		$assign['leave'][] = $array;
            	}
            }
        }
		$smarty->assign($assign);
		$smarty->display('exam/exam.exam.html');
        break;
}