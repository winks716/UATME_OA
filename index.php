<?php
/*
*  Index.php
*  
*  Main route file, per the request parameter, load different pages.
*
*  @author UATME studio <uatmestudio@163.com>
*  @copyright  2012-2013 UATME studio
*  @version  Release: $Revision: 1.0 $
*/


/* 
 * 主路由文件
 */
 
//获取配置文件
require_once 'config.php';

//GET data filter, avoid script attack and sql attack.
foreach($_GET as $k=>$v){
	$_GET[$k] = htmlspecialchars($v);
}

//获取路由设置参数
$M = isset ($_GET['m']) ? $_GET['m'] : 'base';
$S = isset ($_GET['s']) ? $_GET['s'] : 'self';
$A = isset ($_GET['a']) ? $_GET['a'] : 'edit';
//设置smarty模板
$smarty->assign('request_module',$M);
$smarty->assign('request_submodule',$S);
$smarty->assign('request_action',$A);

//判断用户准入
if(isset($_SESSION['employee_id']) && $_SESSION['employee_id']>0){
	
	//判断用户是否有权限访问他输入的地址模块
	if(!msaAuth($M, $S, $A, $_SESSION['privilege'])){
		header('Location: 401.html');
	}
	
	
    //设定模板的帐套变量
    //此地如果如此运用，需要account.config.php中的帐套字段o严格唯一！！
    //foreach($accountlist as $a){
    //    if($a['v']==$_SESSION['account']){
    //        $_SESSION['user_accountname'] = $a['n'];
    //        $smarty->assign('user_accountname', $_SESSION['user_accountname']);
    //    }
    //}
    //设定模板的公用变量
	//将session渲染进模板
	
	//POST data filter, avoid script attack and sql attack.
	foreach($_POST as $k=>$v){
		if(is_string($v)){
			$_POST[$k] = htmlspecialchars($v);
		}
	}
	
	//define privilege session
	//define privilege condition search for menu and submenu
	$menu_sql = ' AND ( privilege_acceptable="" OR privilege_acceptable="0" ';
	foreach($_SESSION['privilege'] as $k => $p){
		$_SESSION['if_'.$p] = 1;
		$privilege_sql .= ' OR privilege_acceptable LIKE "'.$k.'" OR privilege_acceptable LIKE "%,'.$k.'" OR privilege_acceptable LIKE "'.$k.',%" OR privilege_acceptable LIKE "%,'.$k.',%" ';
	}
	if($privilege_sql != ''){
		$menu_sql .= $privilege_sql.')';
	}else{
		$menu_sql .= ')';
	}
	
	//assign session value into template
    $smarty->assign('session', $_SESSION);
	
	//initial topNavigation data
	//search module
	$sql = 'SELECT * FROM uatme_oa_system_module WHERE available=1 AND ifnav=1 AND parent_id=0 '.$menu_sql.' ORDER BY orderby ASC';
	$result = $mysqli->query($sql);
	if($result->num_rows > 0){
		while($array = $result->fetch_assoc()){
			$assign['tab'][] = $array;
			$assign['menu'][] = $array;
			$sql_submenu = 'SELECT * FROM uatme_oa_system_module WHERE available=1 and parent_id="'.$array['id'].'" '.$menu_sql.' ORDER BY orderby ASC';
			$result_submenu = $mysqli->query($sql_submenu);
			if($result_submenu->num_rows > 0){
				while($array_submenu = $result_submenu->fetch_assoc()){
					$assign['submenu'][$array['id']][] = $array_submenu;
				}
			}
		}
	}
	
	//search company announce
	if($_SESSION['if_announced'] == 0){
		$sql = 'SELECT * FROM uatme_oa_system_announce WHERE start_date<="'.date('Y-m-d').'" AND end_date>="'.date('Y-m-d').'" ORDER BY update_date DESC';
		$result = $mysqli->query($sql);
		while($array = $result->fetch_assoc()){
			$assign['announce'][] = $array;
		}
		$_SESSION['if_announced'] = 1;
	}
	
    //导入指定的模块
    //if(IS_M){
	//require_once DOCROOT.'/mobile/'.$M.'/'.$S.'.php';
    //}else{
    	require_once DOCROOT.'/modules/'.$M.'/'.$S.'.php';
    //}
    
    
}else{
	if($S == 'verify'){
		require_once DOCROOT.'/modules/base/verify.php';
		//print_r($_SESSION);
	}else if($S == 'login'){
		//if(IS_M){
		//require_once DOCROOT.'/mobile/calendar/login.php';
		//}else{
			require_once DOCROOT.'/modules/base/login.php';
		//}
	}else{
		header('Location: index.php?m=base&s=login');
	}
}
?>
