<?php
switch($A){
    case 'employee.list':
        $employee_id = (isset($_GET['eid']) && $_GET['eid']>0) ? $_GET['eid'] : 0;
        $assign['employee_list'] = basicMysqliQuery('uatme_oa_system_employee', ' WHERE ifcheckedin=0 AND ifavailable=0 ');
        $assign['employee_infor']['basic'] = basicMysqliQuery('uatme_oa_hr_employee_basic_info', ' WHERE employee_id="'.$employee_id.'"');
        $assign['employee_infor']['education'] = basicMysqliQuery('uatme_oa_hr_employee_education_info', ' WHERE employee_id="'.$employee_id.'"');
        $assign['employee_infor']['skill'] = basicMysqliQuery('uatme_oa_hr_employee_skill_info', ' WHERE employee_id="'.$employee_id.'"');
        $smarty->assign($assign);
        $smarty->display('hr/checkin.employee.list.html');
        break;
    case 'employee.basic.edit':
        $smarty->assign($assign);
        $smarty->display('hr/checkin.basic.edit.html');
        break;
    case 'employee.education.edit':
        $smarty->assign($assign);
        $smarty->display('hr/checkin.education.edit.html');
        break;
    case 'employee.skill.edit':
        $smarty->assign($assign);
        $smarty->display('hr/checkin.skill.edit.html');
        break;
}