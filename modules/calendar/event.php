<?php
switch($A){
    case 'save.event'://event.add
        //需要提供事件的title,detail,year,month,day,hour,minute,end_hour,end_minute,lasting,legendid,ifshare,progress,priority等
        //最重要要提供发布者的登录工号、密码、公司account，否则不可以发布
        //是否需要进行握手动作？再议
        if($_SESSION['user_id']){
            $meid = $_SESSION['user_id'];
            //检查必要字段非空
            if(($title = trim($_POST['title'])) == '') exit(' title Necessarily !!! <br/><br/><a href="index.php">[返回日历]</a>');
            if(($year = trim($_POST['Year'])) == '') exit(' year Necessarily !!! <br/><br/><a href="index.php">[返回日历]</a>');
            if(($month = trim($_POST['Month'])) == '') exit(' month Necessarily !!! <br/><br/><a href="index.php">[返回日历]</a>');
            if(($day = trim($_POST['Day'])) == '') exit(' day Necessarily !!! <br/><br/><a href="index.php">[返回日历]</a>');
            if(($hour = trim($_POST['hour'])) == '') exit(' hour Necessarily !!! <br/><br/><a href="index.php">[返回日历]</a>');
            if(($minute = trim($_POST['minute'])) == '') exit(' minute Necessarily !!! <br/><br/><a href="index.php">[返回日历]</a>');
            if(($end_hour = trim($_POST['end_hour'])) == '') exit(' end_hour Necessarily !!! <br/><br/><a href="index.php">[返回日历]</a>');
            if(($end_minute = trim($_POST['end_minute'])) == '') exit(' end_minute Necessarily !!! <br/><br/><a href="index.php">[返回日历]</a>');
            //检查非必要字段，为空需要定义默认值
            //事件持续天数，默认=1
            $lasting = $_POST['lasting'] >= 1 ? floor($_POST['lasting']) : 1;
            //事件持续类型，=0工作日持续，=1自然天持续，默认=0
            $lastingtype = $_POST['lastingtype'] == 1 ? $_POST['lastingtype'] : 0;
            //事件内容，默认“无”
            $detail = $_POST['detail'];
            //事件类型，默认=1，具体要看管理员对图例的设置
            $legendid = $_POST['legendid'] >= 1 ? floor($_POST['legendid']) : 1;
            //事件共享类型，=1共享，0不共享，默认=0
            $ifshare = $_POST['ifshare'] == 1 ? $_POST['ifshare'] : 0;
            //事件进度，默认=0
            $progress = (($_POST['progress'] >= 0) and ($_POST['progress'] <= 100)) ? floor($_POST['progress']/10)*10 : 0;
            //事件紧急度，默认=0
            $priority = in_array($_POST['priority'],array(-2,-1,1,2)) ? $_POST['priority'] : 0;
            //事件需要@谁，不同人的工号之间用英文逗号分隔，寻找每个工号对应的uatme_employee.id后，供后面的插入代码循环
            $eidstr = '"'.str_replace(',','","',$_POST['at']).'"';//为每个工号加上引号
            $query = 'SELECT id FROM uatme_employee WHERE employeeid IN ('.$eidstr.') OR name IN ('.$eidstr.')';
            $result = $mysqli->query($query);
            if($result->num_rows > 0){
                while($array = $result->fetch_assoc()){
                    $eid[] = $array['id'];
                }
            }

            //检查事件起始结束时间的合法性
            if($hour.$minute >= $end_hour.$end_minute){
                exit('结束时间不能小于或等于开始时间！<br/><br/><a href="index.php">[返回日历]</a>');
            }
            //获取时间持续天数为循环上限,生成需要插入事件的日期数组
            $timestamp = strtotime($year.'-'.$month.'-'.$day);
            for($i=1; $i<=$lasting; $i++){
                //判断重复时间是采用工作日还是自然天，如果重复模式是工作日且当前天是休息日，则自动顺延到最近的下一个工作日
                if($_POST['lastingtype']==0){//如果选择的是工作日，那么要进行周末和节假日的跳过
                    while(date('w',$timestamp)==0 or date('w',$timestamp)==6){
                        $timestamp = $timestamp + 86400;//如果是周日或周六，顺延
                    }
                    //判断是否是节假日
                    $query = 'SELECT date FROM uatme_holiday WHERE holidaydate="'.date('Y-m-d',$timestamp).'"';
                    //echo $query;
                    $result = $mysqli->query($query);
                    $num_rows = $result->num_rows;
                    while($num_rows){
                        $timestamp = $timestamp + 86400;//如果是节假日，顺延
                        $query = 'SELECT date FROM uatme_holiday WHERE holidaydate="'.date('Y-m-d',$timestamp).'"';
                        //echo $query;
                        $result = $mysqli->query($query);
                        $num_rows = $result->num_rows;
                    }
                }
                $date[] = array('y'=>date('Y',$timestamp),'m'=>date('n',$timestamp),'d'=>date('j',$timestamp));//将日期压入数组
                $timestamp = $timestamp + 86400;//获取下一天
            }

            //循环每一天，为当事人插入事件
            foreach($date as $d){
                $query = 'INSERT INTO uatme_calendar_event(eid,year,month,day,hour,minute,end_hour,end_minute,legendid,ifshare,title,detail,priority,progress)  VALUES("'.$meid.'","'.$d['y'].'","'.$d['m'].'","'.$d['d'].'","'.$hour.'","'.$minute.'","'.$end_hour.'","'.$end_minute.'","'.$legendid.'","'.$ifshare.'","'.$title.'","'.$detail.'","'.$priority.'","'.$progress.'")';
                //echo $query;
                $mysqli->query($query);
                //获取当事人的事件的id后，为每一个选中的用户插入事件，标识被@
                $at_id = $mysqli->insert_id;
                $query = '';//重新初始化sql脚本
                foreach($eid as $oeid){
                    if($oeid != $meid)//自己不@自己
                        $query .= '("'.$oeid.'","'.$d['y'].'","'.$d['m'].'","'.$d['d'].'","'.$hour.'","'.$minute.'","'.$end_hour.'","'.$end_minute.'","'.$legendid.'","'.$ifshare.'","'.$title.'","'.$detail.'","'.$priority.'","'.$progress.'","'.$at_id.'","'.$meid.'"),';
                }
                $query = substr($query,0,-1);//去除最后一个逗号
                //如果有需要@的人，则合并最后的sql语句，然后执行
                if(trim($query) != ''){
                    $query = 'INSERT INTO uatme_calendar_event(eid,year,month,day,hour,minute,end_hour,end_minute,legendid,ifshare,title,detail,priority,progress,at_id,at_eid)  VALUES '.$query;
                    //echo $query;
                    $mysqli->query($query);
                }
            }

            header('Location: index.php');
        }else{
            exit('NO Permission !!! <br/><br/><a href="index.php">[返回日历]</a>');
        }
        break;
    default:
        $assign['employeeid'] = $_SESSION['user_employeeid'];
        $assign['password'] = $_SESSION['user_password'];
        $assign['account'] = $_SESSION['account'];
        $smarty->assign($assign);

        $query = 'SELECT * FROM uatme_view_department_employee WHERE eid!="'.$_SESSION['user_id'].'"';
        $result = $mysqli->query($query);
        if($result->num_rows > 0){
            while($array = $result->fetch_assoc()){
                $employee[$array['dname']]['did'] = $array['did'];
                $employee[$array['dname']]['dname'] = $array['dname'];
                $employee[$array['dname']]['employee'][] = array('eid'=>$array['eid'],'ename'=>$array['ename'],'epid'=>$array['epid']);
                //print_r($array);
            }
        }
        $smarty->assign('employee',$employee);
        //print_r($employee);

        //设定图例颜色的数组
        $query = 'SELECT * FROM uatme_legend';
        $result = $mysqli->query($query);
        if($result->num_rows > 0){
            while($array = $result->fetch_assoc()){
                $legend[] = $array;
            }
        }
        $smarty->assign('legend',$legend);

        $smarty->display('calendar/event.add.html');
        break;
}
?>
