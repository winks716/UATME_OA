<?php
/*
*  Config.php
*
*  Main config file.
*
*  @author UATME studio <uatmestudio@163.com>
*  @copyright  2012-2013 UATME studio
*  @version  Release: $Revision: 1.0 $
*/



/* 
 * 主配置文件
 */

//开启或关闭php报错
//ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('error_reporting', 0);

//设定本地时间
date_default_timezone_set('Asia/Shanghai');


//设定session启动
session_start();

//设定默认页面编码为utf8
header("Content-type: text/html; charset=utf-8");

//设定静态文档根目录变量
define('DOCROOT', dirname(__FILE__));

//if in maintenance
require_once DOCROOT.'/maintenance.config.php';

//if in maintenance
require_once DOCROOT.'/smtp.config.php';

//import shared class files
foreach(glob(DOCROOT.'/include/class/*/class.*.php') as $f){
	require_once $f;
}

//导入共享function文件
foreach(glob(DOCROOT.'/include/*.function.php') as $f){
    require_once $f;
}

//导入Smarty类文件
foreach(glob(DOCROOT.'/Smarty/smarty.class.php') as $f){
    require_once $f;
}

//导入Zend类文件
foreach(glob(DOCROOT.'/Zend/*.php') as $f){
    require_once $f;
}

require_once DOCROOT.'/db.config.php';

//初始 Smarty 设置
$smarty = new smarty();
$smarty->setTemplateDir(DOCROOT.'/template/modules');
$smarty->setCompileDir(DOCROOT.'/template/compile');
$smarty->setCacheDir(DOCROOT.'/template/cache');
$smarty->left_delimiter = '<{';
$smarty->right_delimiter = '}>';

//导入配置文件，开始设置初始环境
foreach(glob(DOCROOT.'/config/*.config.php') as $conf){
    require_once $conf;
}

//设置公司名称，域名等全局信息
define('COMPANY_DOMAIN','highsource.com');

//设置多国语言包
//获得语言包种类
foreach($language as $k=>$v){
    $langList[] = array('v'=>$k,'n'=>$v['name']);
}
//print_r($languagelist);
$smarty->assign('langList', $langList);
//获取当前用户选择的语言包设置
$_SESSION['lang'] = isset($_POST['lang']) ? $_POST['lang'] : ($_SESSION['lang'] ? $_SESSION['lang'] : 'zh_CN');
$smarty->assign('langSelected',$_SESSION['lang']);
//设定用户语言包，默认使用简体中文
if(is_array($language[$_SESSION['lang']])){
    $smarty->assign('lang',$language[$_SESSION['lang']]);//zh_CN,us_EN
}else{
    $smarty->assign('lang',$language['zh_CN']);
}

//设置多组织配置文件（注意，此文件要与数据库配合，有数据有配置才正确缺一不可。因为使用的是组织名称作为数据库前缀）
$smarty->assign('accountlist',$accountlist);


//全局样式配置
//当前默认样式
$_SESSION['themeName'] = (isset($_SESSION['themeName']) && $_SESSION['themeName']!='') ? $_SESSION['themeName'] : 'redmond';
//样式数组
$themeList = array(
				//0=>array('name'=>'highsource','bgcolor'=>'blue'),
				//1=>array('name'=>'cupertino','bgcolor'=>'#deedf7'),
				2=>array('name'=>'redmond','bgcolor'=>'#5c9ccc'),
				3=>array('name'=>'smoothness','bgcolor'=>'#CCCCCC'),
				4=>array('name'=>'ui-lightness','bgcolor'=>'orange')
				);
$smarty->assign('themeList',$themeList);
				

//全局数据配置

//init apply status metrics
$apply_status_array = array(
	0=>array(0=>'待审批', 1=>'待审批'),
	1=>array(0=>'可领取', 1=>'已领取'),
	2=>array(0=>'已拒绝', 1=>'已拒绝')
);
?>