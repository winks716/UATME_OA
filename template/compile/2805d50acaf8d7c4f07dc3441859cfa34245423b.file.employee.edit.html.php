<?php /* Smarty version Smarty-3.0.7, created on 2013-02-04 03:32:56
         compiled from "E:\UATME_OA/template/modules\system/employee.edit.html" */ ?>
<?php /*%%SmartyHeaderCode:29546510ebb6833eaa6-74865408%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2805d50acaf8d7c4f07dc3441859cfa34245423b' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\system/employee.edit.html',
      1 => 1359919964,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '29546510ebb6833eaa6-74865408',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div class="container">
<form id="editEmployeeForm">
	<?php  $_smarty_tpl->tpl_vars['e'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('employee')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['e']->key => $_smarty_tpl->tpl_vars['e']->value){
?>
	<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['e']->value['id'];?>
"/>
	<table>
		<tr><th>中文名</th><td><input name="namezh" value="<?php echo $_smarty_tpl->tpl_vars['e']->value['namezh'];?>
"/></td></tr>
		<tr><th>英文名</th><td><input name="name" value="<?php echo $_smarty_tpl->tpl_vars['e']->value['name'];?>
"/></td></tr>
		<tr><th>英文缩写</th><td><input name="short_name" value="<?php echo $_smarty_tpl->tpl_vars['e']->value['short_name'];?>
"/></td></tr>
		<tr><th>电子邮箱</th><td><input name="email" value="<?php echo $_smarty_tpl->tpl_vars['e']->value['email'];?>
"/></td></tr>
		<tr><th>工号</th><td><input name="employee_no" value="<?php echo $_smarty_tpl->tpl_vars['e']->value['employee_no'];?>
"/></td></tr>
		<tr><th>在职</th><td><input type="radio" name="ifleave" value="0" <?php if ($_smarty_tpl->tpl_vars['e']->value['ifleave']==0){?>checked<?php }?>/>是&nbsp;&nbsp;
							<input type="radio" name="ifleave" value="1" <?php if ($_smarty_tpl->tpl_vars['e']->value['ifleave']==1){?>checked<?php }?>/>否</td></tr>
		<tr><th>部门</th><td><select name="department_id">
								<option value="0">-请选择员工所属部门-</option>
								<?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('department')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value){
?>
								<option value="<?php echo $_smarty_tpl->tpl_vars['d']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['d']->value['id']==$_smarty_tpl->tpl_vars['e']->value['department_id']){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['d']->value['name'];?>
</option>
								<?php }} ?>
							</select></td></tr>
	</table>
	<?php }} ?>
</form>
</div>