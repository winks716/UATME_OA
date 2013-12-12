<?php

/*
 * exam 
 */

switch($A){
    case 'exam':
        $examToken = (isset($_GET['t']) && $_GET['t']!='') ? $_GET['t'] : '';
        $examTemplate = 'exam/exam.exam.html';
        $i = 1;
        if($examToken){
            //find exam match the token, then show the exam
            $sql = 'SELECT examId FROM uatme_oa_oexam_exam_employee WHERE employeeId="'.$_SESSION['employee_id'].'" AND examToken="'.$examToken.'"';
            $resultPaper = $mysqli->query($sql);
            if($resultPaper->num_rows == 1){
            	while($arrayPaper = $resultPaper->fetch_assoc()){
            		$assign['examId'] = $arrayPaper['examId'];
            	}
            	
            	//find related exam info, question info and option info
            	//exam
            	$sqlExam = 'SELECT * FROM uatme_oa_oexam_exam WHERE id="'.$assign['examId'].'"';
            	$resultExam = $mysqli->query($sqlExam);
            	if($resultExam->num_rows == 1){
            	    while ($arrayExam = $resultExam->fetch_assoc()){
            	        //question
            	        $sqlQuestion = 'SELECT * FROM uatme_oa_oexam_question WHERE examId="'.$arrayExam['id'].'"';
            	        $resultQuestion = $mysqli->query($sqlQuestion);
            	        if($resultQuestion->num_rows > 0){
            	            while ($arrayQuestion = $resultQuestion->fetch_assoc()){
            	                //option
            	                $sqlOption = 'SELECT * FROM uatme_oa_oexam_option WHERE questionId="'.$arrayQuestion['id'].'"';
            	                $resultOption = $mysqli->query($sqlOption);
            	                if($resultOption->num_rows > 0){
            	                    while ($arrayOption = $resultOption->fetch_assoc()){
            	                        $arrayQuestion['option'][] = $arrayOption;
            	                    }
            	                }else{
                                    $examTemplate = 'exam/exam.none.html';
                                }
                                $arrayQuestion['i'] = $i++;
            	                $arrayExam['question'][] = $arrayQuestion;
            	            }
            	        }else{
                            $examTemplate = 'exam/exam.none.html';
                        }
            	        $assign['exam'][] = $arrayExam;
            	    }
            	}else{
                    $examTemplate = 'exam/exam.none.html';
                }
            	
            }else{
                $examTemplate = 'exam/exam.none.html';
            }
        }else{
            $examTemplate = 'exam/exam.none.html';
        }
		$smarty->assign($assign);
		$smarty->display($examTemplate);
        break;
    case 'default':
        
        break;
}