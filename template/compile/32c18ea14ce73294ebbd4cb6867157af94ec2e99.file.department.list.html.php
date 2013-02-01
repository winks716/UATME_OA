<?php /* Smarty version Smarty-3.0.7, created on 2013-02-02 02:10:05
         compiled from "E:\UATME_OA/template/modules\system/department.list.html" */ ?>
<?php /*%%SmartyHeaderCode:14221510c04fd43dcd5-53941481%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '32c18ea14ce73294ebbd4cb6867157af94ec2e99' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\system/department.list.html',
      1 => 1359742201,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14221510c04fd43dcd5-53941481',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template("header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("topnav.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>


<div class="container">
	<table>
		<tr>
			<th>部门名称</th>
			<th>部门缩写</th>
			<th>部门经理</th>
			<th>所属地区</th>
			<th>可用</th>
			<th>操作</th>
		</tr>
		<tr>
			<td><input id="name" class="span-2"/>+新增</td>
			<td><input id="nameshort" class="span-3"/></td>
			<td><select id="manageremployeeid">
				<?php  $_smarty_tpl->tpl_vars['e'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('employee')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['e']->key => $_smarty_tpl->tpl_vars['e']->value){
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['e']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['e']->value['name'];?>
 - <?php echo $_smarty_tpl->tpl_vars['e']->value['namezh'];?>
</option>
				<?php }} ?>
				</select></td>
			<td><select id="parentid">
				<?php  $_smarty_tpl->tpl_vars['l'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('location')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['l']->key => $_smarty_tpl->tpl_vars['l']->value){
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['l']->value['name'];?>
</option>
				<?php }} ?>
				</select></td>
			<td><input id="available" type="checkbox" value="1" checked/></td>
			<td><span class="clickbtn span-2" id="addDepartment"> [添加] </span></td>
		</tr>
		<?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('department')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value){
?>
		<tr>
			<td><input value="<?php echo $_smarty_tpl->tpl_vars['d']->value['name'];?>
" class="span-2 name"/></td>
			<td><input value="<?php echo $_smarty_tpl->tpl_vars['d']->value['nameshort'];?>
" class="span-3 nameshort"/></td>
			<td><select class="manageremployeeid">
				<?php  $_smarty_tpl->tpl_vars['e'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('employee')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['e']->key => $_smarty_tpl->tpl_vars['e']->value){
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['e']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['d']->value['manager_employee_id']==$_smarty_tpl->tpl_vars['e']->value['id']){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['e']->value['name'];?>
 - <?php echo $_smarty_tpl->tpl_vars['e']->value['namezh'];?>
</option>
				<?php }} ?>
				</select></td>
			<td><select class="parentid">
				<?php  $_smarty_tpl->tpl_vars['l'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('location')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['l']->key => $_smarty_tpl->tpl_vars['l']->value){
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['d']->value['location_id']==$_smarty_tpl->tpl_vars['l']->value['id']){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['l']->value['name'];?>
</option>
				<?php }} ?>
				</select></td>
			<td><input value="1" class="span-1 available" type="checkbox" <?php if ($_smarty_tpl->tpl_vars['d']->value['available']==1){?>checked<?php }?>/></td>
			<td><span class="clickbtn saveDepartment" i="<?php echo $_smarty_tpl->tpl_vars['d']->value['id'];?>
"> [保存] </span> <span class="clickbtn deleteDepartment" i="<?php echo $_smarty_tpl->tpl_vars['d']->value['id'];?>
"> [删除] </span></td>
		</tr>
		<?php }} ?>
	</table>
</div>

<?php $_template = new Smarty_Internal_Template("declaration.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>

<script>
$(function(){
	$('#addDepartment').click(function(){
		var name = $('#name').val();
		var nameshort = $('#nameshort').val();
		var manageremployeeid = $('#manageremployeeid').val();
		var parentid = $('#parentid').val();
		var available = $('#available:checked').val();
		if(name!='' || nameshort!=''){
			$.post('index.php?m=system&s=setup&a=department.add', {'name':name,'nameshort':nameshort,'manageremployeeid':manageremployeeid,'parentid':parentid,'available':available}, function(data){
				var json = eval('(' + data + ')');
				if(json.httpstatus == 200){
					alert(json.msg);
					window.location.reload();
				}
				if(json.httpstatus == 500){
					alert(json.error);
				}
			})			
		}
	})
	$('.saveDepartment').click(function(){
		var o = $(this).parents('tr');
		var name = o.find('.name').val();
		var nameshort = o.find('.nameshort').val();
		var manageremployeeid = o.find('.manageremployeeid').val();
		var parentid = o.find('.parentid').val();
		var available = o.find('.available:checked').val();
		var id = $(this).attr('i');
		if(name!='' || nameshort!=''){
			$.post('index.php?m=system&s=setup&a=department.save', {'id':id,'name':name,'nameshort':nameshort,'manageremployeeid':manageremployeeid,'parentid':parentid,'available':available}, function(data){
				var json = eval('(' + data + ')');
				if(json.httpstatus == 200){
					alert(json.msg);
					window.location.reload();
				}
				if(json.httpstatus == 500){
					alert(json.error);
				}
			})			
		}
	})
})
</script>