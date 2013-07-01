<?php
switch($A){
    case 'employee.basic':
        $assign['employee'] = basicMysqliQuery('uatme_oa_system_employee', ' WHERE ifcheckin=0 AND ifavailable=0 ');
        $smarty->assign($assign);
        $smarty->display('hr/employee.basic.html');
        break;
    case 'employee.education':
        
        break;
    case 'employee.skill':
        
        break;
}