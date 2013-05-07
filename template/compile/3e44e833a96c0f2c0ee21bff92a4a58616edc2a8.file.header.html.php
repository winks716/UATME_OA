<?php /* Smarty version Smarty-3.0.7, created on 2013-05-07 22:30:53
         compiled from "E:\UATME_OA/template/modules\header.html" */ ?>
<?php /*%%SmartyHeaderCode:117375189101d3e1324-87761811%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3e44e833a96c0f2c0ee21bff92a4a58616edc2a8' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\header.html',
      1 => 1367937015,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '117375189101d3e1324-87761811',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html>
  <head>
    <title>汉盛OA</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link media="screen, projection" type="text/css" href="css/blueprint/screen.css" rel="stylesheet" />
	<link media="print" type="text/css" href="css/blueprint/print.css" rel="stylesheet" />
	<?php  $_smarty_tpl->tpl_vars['t'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('themeList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['t']->key => $_smarty_tpl->tpl_vars['t']->value){
?>
    <link media="screen" title="<?php echo $_smarty_tpl->tpl_vars['t']->value['name'];?>
" href="css/jqueryui/<?php echo $_smarty_tpl->tpl_vars['t']->value['name'];?>
/jquery-ui-1.9.1.custom.min.css" rel="<?php if ($_smarty_tpl->getVariable('session')->value['themeName']!=$_smarty_tpl->tpl_vars['t']->value['name']){?>alternate <?php }?>stylesheet" type="text/css" />
	<?php }} ?>
    <link media="screen" href="css/global.css" rel="stylesheet" type="text/css" />
    <link media="screen" href="css/calendar.main.css" rel="stylesheet" type="text/css" />
    <script src="js/jquery-1.7.2.min.js"></script>
    <script src="js/jquery-ui-1.9.1.custom.min.js"></script>
    <script src="js/global.js"></script>
    <script src="js/timepicker.jquery.js"></script>
  </head>
  <body>