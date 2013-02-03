<?php /* Smarty version Smarty-3.0.7, created on 2013-02-04 03:59:07
         compiled from "E:\UATME_OA/template/modules\system/employee.position.edit.html" */ ?>
<?php /*%%SmartyHeaderCode:8835510ec18bdaa077-31438949%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c7d9ce24566721714bc9376246e2e4bafeda0374' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\system/employee.position.edit.html',
      1 => 1359921531,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8835510ec18bdaa077-31438949',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div class="container">
	<form id="editPositionForm">
	<input type="hidden" value="<?php echo $_smarty_tpl->getVariable('id')->value;?>
" name="id"/>
	<table>
	<?php  $_smarty_tpl->tpl_vars['p'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('position')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['p']->key => $_smarty_tpl->tpl_vars['p']->value){
?>
	<tr><td><input name="position[]" type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['p']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['p']->value['assigned']==1){?>checked<?php }?>/></td><td><?php echo $_smarty_tpl->tpl_vars['p']->value['name'];?>
</td></tr>
	<?php }} ?>
	</table>
	</form>
</div>