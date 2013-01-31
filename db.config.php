<?php
//设定数据库，http服务器路径
define('WEBSERVER','http://localhost:8080/');
define('DBPATH','localhost');
define('DBUSER','root');
define('DBPASS','');
define('DBNAME','uatme_oa_db');
//链接帐套中心数据库
$mysqli = new mysqli(DBPATH,DBUSER,DBPASS,DBNAME) or die('cannot connect');
$mysqli->query('SET NAMES UTF8');