<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
switch($A){
    case 'exit':
        session_destroy();
        header('location: index.php');
        break;
    case 'verify':
        $query = 'SELECT * FROM uatme_employee WHERE employeeid="'.$_POST['pid'].'" AND pass="'.md5(sha1($_POST['pass'])).'"';
        $result = $mysqli->query($query);
        if($result->num_rows == 1){
            $array = $result->fetch_assoc();
            $_SESSION['user_id'] = $array['id'];
            $_SESSION['user_name'] = $array['name'];
            $_SESSION['user_ifadmin'] = $array['ifadmin'];
            $_SESSION['user_password'] = $array['pass'];
            $_SESSION['user_employeeid'] = $array['employeeid'];
            echo '{"result":1}';
        }else{
            echo '{"result":0}';
        }
        break;
    default:
        $smarty->display('calendar/login.html');
        break;
}
?>
