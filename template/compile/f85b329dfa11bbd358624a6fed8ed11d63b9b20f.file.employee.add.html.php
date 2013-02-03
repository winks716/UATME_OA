<?php /* Smarty version Smarty-3.0.7, created on 2013-02-04 03:25:30
         compiled from "E:\UATME_OA/template/modules\system/employee.add.html" */ ?>
<?php /*%%SmartyHeaderCode:21692510eb9aa0e2084-52357908%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f85b329dfa11bbd358624a6fed8ed11d63b9b20f' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\system/employee.add.html',
      1 => 1359918926,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21692510eb9aa0e2084-52357908',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div class="container">
<form id="addEmployeeForm">
	<table>
		<tr><th>中文名</th><td><input name="namezh"/></td></tr>
		<tr><th>英文名</th><td><input name="name"/></td></tr>
		<tr><th>英文缩写</th><td><input name="short_name"/></td></tr>
		<tr><th>电子邮箱</th><td><input name="email"/></td></tr>
		<tr><th>工号</th><td><input name="employee_no"/></td></tr>
		<tr><th>在职</th><td><input type="radio" name="ifleave" value="0" checked/>是&nbsp;&nbsp;<input type="radio" name="ifleave" value="1"/>否</td></tr>
		<tr><th>部门</th><td><select name="department_id">
								<option value="0">-请选择员工所属部门-</option>
								<?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('department')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value){
?>
								<option value="<?php echo $_smarty_tpl->tpl_vars['d']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['d']->value['name'];?>
</option>
								<?php }} ?>
							</select></td></tr>
	</table>
</form>
</div>