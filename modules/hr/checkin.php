<?php
switch($A){
    case 'employee.list':
        $employee_id = (isset($_GET['eid']) && $_GET['eid']>0) ? $_GET['eid'] : 0;
        $assign['employee_list'] = basicMysqliQuery('uatme_oa_system_employee', ' WHERE ifcheckedin=0 AND ifavailable=0 ');
        $assign['employee_infor']['basic'] = basicMysqliQuery('uatme_oa_hr_employee_basic_info', ' WHERE employee_id="'.$employee_id.'"');
        $assign['employee_infor']['education'] = basicMysqliQuery('uatme_oa_hr_employee_education_info', ' WHERE employee_id="'.$employee_id.'"');
        $assign['employee_infor']['skill'] = basicMysqliQuery('uatme_oa_hr_employee_skill_info', ' WHERE employee_id="'.$employee_id.'"');
        $assign['jd'] = basicMysqliQuery('uatme_oa_hr_JD', ' WHERE ifavailable=1 ');
        $smarty->assign($assign);
        $smarty->display('hr/checkin.employee.list.html');
        break;
    case 'employee.basic.editinit':
        $assign['employee_id'] = (isset($_GET['eid']) && $_GET['eid']>0) ? $_GET['eid'] : 0;
        $assign['basic'] = basicMysqliQuery('uatme_oa_hr_employee_basic_info', ' WHERE employee_id="'.$assign['employee_id'].'"');
        $assign['jd'] = basicMysqliQuery('uatme_oa_hr_JD', ' WHERE ifavailable=1 ');
        $smarty->assign($assign);
        $smarty->display('hr/checkin.basic.edit.html');
        break;
    case 'employee.basic.editsave':
        $sql = 'UPDATE uatme_oa_hr_employee_basic_info SET mobile="'.$_POST['mobile'].'", telephone="'.$_POST['telephone'].'", address="'.$_POST['address'].'", marriage="'.$_POST['marriage'].'", native="'.$_POST['native'].'", jdid="'.$_POST['jdid'].'" WHERE employee_id="'.$_POST['employee_id'].'"';
        $mysqli->query($sql);
        sendResponse(200,'','基本信息成功更新');
        break;
    case 'employee.education.addinit':
        $assign['employee_id'] = (isset($_GET['eid']) && $_GET['eid']>0) ? $_GET['eid'] : 0;
        $smarty->assign($assign);
        $smarty->display('hr/checkin.education.add.html');
        break;
    case 'employee.education.addsave':
        $sql = 'INSERT INTO uatme_oa_hr_employee_education_info(school, major, start, end, comment, employee_id) VALUES ("'.$_POST['school'].'", "'.$_POST['major'].'", "'.$_POST['start'].'", "'.$_POST['end'].'", "'.$_POST['comment'].'", "'.$_POST['employee_id'].'")';
        $mysqli->query($sql);
        sendResponse(200,'','教育背景成功添加');
        break;
    case 'employee.skill.addinit':
        $assign['employee_id'] = (isset($_GET['eid']) && $_GET['eid']>0) ? $_GET['eid'] : 0;
        $smarty->assign($assign);
        $smarty->display('hr/checkin.skill.add.html');
        break;
    case 'employee.skill.addsave':
        $sql = 'INSERT INTO uatme_oa_hr_employee_skill_info(skill, level, time, employee_id) VALUES ("'.$_POST['skill'].'", "'.$_POST['level'].'", "'.$_POST['time'].'", "'.$_POST['employee_id'].'")';
        $mysqli->query($sql);
        sendResponse(200,'','技能信息成功添加');
        break;
}