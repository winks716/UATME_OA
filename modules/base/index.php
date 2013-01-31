<?php
/**
file: index.php
version: 1
author: vincent.shi
path: /module/base/

description:
default route or action file for base module

usage:
default			//default action

*/
switch($A){
	case 'default':

		
		$smarty->assign($assign);
		$smarty->display('base/index.html');
	break;
}