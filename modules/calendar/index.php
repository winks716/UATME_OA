<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//设定年
$yRequest = isset($_REQUEST['year']) ? trim($_REQUEST['year']) : date('Y');
$assign['year'] = ($yRequest>=2010 and $yRequest<=2016) ? $yRequest : date('Y');
//设定月
$mRequest = isset($_REQUEST['month']) ? trim($_REQUEST['month']) : date('n');
$assign['month'] = ($mRequest>=1 and $mRequest<=12) ? $mRequest : date('n');
//设定目标月首天的timestamp
$timestamp = mktime(0,0,0,$assign['month'],1,$assign['year']);
$assign['timestamp'] = $timestamp;

//获取当前的年份\月份\日期，以便用户快速回到当前和将今天背景染成深色
$yearNow = date('Y');
$monthNow = date('m');
$dayNow = date('d');
$assign['yearNow'] = $yearNow;
$assign['monthNow'] = $monthNow;
$assign['dayNow'] = $dayNow;

//获取目标月首天星期几，即周日偏移量
$dFirst = date('w',$timestamp);
//获取目标月的总天数
$dMonth = date('t',$timestamp);
//计算日历总格数
//算法是目标月天数$dMonth+目标月的周日偏移量$dFirst；除以7向上取整；再乘以7
//先计算最大行数
$rMax = ceil(($dMonth+$dFirst)/7);
$assign['rMax'] = $rMax;
//再计算日历格子总数
$dMax = $rMax*7;
//循环开始设置月份数据数组
//算法是只有在周日偏移量 to 周日偏移量+目标月天数中的日子才会设定为正常日历数据，否则标记为空白日历格
for($i = 0; $i < $dMax; $i++){
    if(($i < $dFirst) or ($i >= $dFirst + $dMonth)){
        $date[floor($i/7)][$i % 7] = 0;
    }else{
        $date[floor($i/7)][$i % 7] = $i - $dFirst +1;
    }
}
$assign['date'] = $date;
//设定用来循环一周的下标数组
$assign['day'] = array(0,1,2,3,4,5,6);


//获取排序关键字和排序方式
$assign['key'] = isset($_REQUEST['key']) ? trim($_REQUEST['key']) : 'time';
switch($assign['key']){
    case 'priority':
        $assign['order'] = ' ORDER BY priority DESC,hour ASC,minute ASC,end_hour ASC,end_minute ASC,progress ASC ';
        break;
    case 'progress':
        $assign['order'] = ' ORDER BY progress ASC,hour ASC,minute ASC,end_hour ASC,end_minute ASC,priority DESC ';
        break;
    default:
        $assign['order'] = ' ORDER BY hour ASC,minute ASC,end_hour ASC,end_minute ASC,priority DESC,progress ASC ';
        break;
}

//获取查看的事件图例类型，0=全部查看，>0代表查看某一种图例事件
/*$assign['legendid'] = isset($_REQUEST['legendid']) ? $_REQUEST['legendid'] : 0;
if($assign['legendid']==0){
    $legendstr = ' 1 ';
}else{
    $legendstr = ' legendid="'.$_REQUEST['legendid'].'" ';
}

//来判断是否是admin，来决定是否开启全局监视
if($_SESSION['user_ifadmin']==1 and $ifglobal==1){
    $noadminsql = ' 1 ';
}else{
    $noadminsql = ' (eid="'.$_SESSION['employee_id'].'" OR ifshare="1") ';
}*/

//在这里为有日历事件的数据格数组进行赋值
//获取数据库数据
//选取规则：自己的(自己添加的或者被别人@的)，或者public类型的，在目标月时间范围内的
/*$query = 'SELECT * FROM uatme_view_calendar_event WHERE '.$noadminsql.' AND '.$legendstr.' AND (year="'.$assign['year'].'") AND (month="'.$assign['month'].'") AND (day BETWEEN "1" AND "31") '.$assign['order'];
$result = $mysqli->query($query);
if($result->num_rows > 0){
    while($array = $result->fetch_assoc()){
        $data[$array['day']][] = $array;
    }
}
$assign['data'] = $data;*/

//获取目标月的节日信息
/*$query = 'SELECT * FROM uatme_holiday WHERE holidaydate BETWEEN "'.$assign['year'].'-'.$assign['month'].'-01" AND "'.$assign['year'].'-'.$assign['month'].'-31"';
$result = $mysqli->query($query);
if($result->num_rows > 0){
    while($array = $result->fetch_assoc()){
        $holiday[date('j',strtotime($array['holidaydate']))] = $array['name'];
    }
}
$assign['holiday'] = $holiday;*/

//设定图例颜色的数组
/*$query = 'SELECT * FROM uatme_legend';
$result = $mysqli->query($query);
if($result->num_rows > 0){
    while($array = $result->fetch_assoc()){
        $legend[] = $array;
    }
}
$assign['legend'] = $legend;*/

//根据通知的有效时间和目前时间的对比，获取最新的通知
/*$query = 'SELECT * FROM uatme_view_inform_employee WHERE publishdate >= ('.time().'-(lasting+1)*86400) AND publishdate <= '.time().' ORDER BY publishdate DESC, lasting DESC';
$result = $mysqli->query($query);
if($result->num_rows > 0){
    while($array = $result->fetch_assoc()){
        $array['publishdate'] = date('Y-m-d',$array['publishdate']);
        $inform[] = $array;
    }
}
$assign['inform'] = $inform;*/

//smarty模板
$smarty->assign($assign);
$smarty->display('calendar/main.html');