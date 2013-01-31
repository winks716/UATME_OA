<?php
/*
 * 子路由文件
 */
$S = isset ($_GET['s']) ? $_GET['s'] : 'main';
require_once DOCROOT.'/modules/calendar/'.$S.'.php';

?>
