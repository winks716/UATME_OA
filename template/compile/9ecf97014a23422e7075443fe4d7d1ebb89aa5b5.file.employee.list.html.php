<?php /* Smarty version Smarty-3.0.7, created on 2013-01-31 23:43:43
         compiled from "E:\UATME_OA/template/modules\system/employee.list.html" */ ?>
<?php /*%%SmartyHeaderCode:19660510a912fba38b1-16889660%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9ecf97014a23422e7075443fe4d7d1ebb89aa5b5' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\system/employee.list.html',
      1 => 1359039212,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19660510a912fba38b1-16889660',
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
			<th>中文名</th>
			<th>英文名</th>
			<th>缩写(必须唯一)</th>
			<th>电邮(必须唯一)</th>
			<th>工号(必须唯一)</th>
			<th>在职</th>
			<th>操作</th>
		</tr>
		<tr>
			<td><input id="namezh" class="span-2"/>+新增</td>
			<td><input id="name" class="span-3"/></td>
			<td><input id="short_name" class="span-2"/></td>
			<td><input id="email" class="span-4"/></td>
			<td><input id="employee_no" class="span-2"/></td>
			<td><input id="ifleave" type="hidden" value="0"/>是</td>
			<td><span class="clickbtn span-2" id="addEmployee"> [添加] </span></td>
		</tr>
		<?php  $_smarty_tpl->tpl_vars['e'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('employee')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['e']->key => $_smarty_tpl->tpl_vars['e']->value){
?>
		<tr>
			<td><input value="<?php echo $_smarty_tpl->tpl_vars['e']->value['namezh'];?>
" class="span-2 namezh"/></td>
			<td><input value="<?php echo $_smarty_tpl->tpl_vars['e']->value['name'];?>
" class="span-3 name"/></td>
			<td><input value="<?php echo $_smarty_tpl->tpl_vars['e']->value['short_name'];?>
" class="span-2 short_name"/></td>
			<td><input value="<?php echo $_smarty_tpl->tpl_vars['e']->value['email'];?>
" class="span-4 email"/></td>
			<td><input value="<?php echo $_smarty_tpl->tpl_vars['e']->value['employee_no'];?>
" class="span-2 employee_no"/></td>
			<td><input value="stay" class="ifleave" type="checkbox" <?php if ($_smarty_tpl->tpl_vars['e']->value['ifleave']==0){?>checked<?php }?>/></td>
			<td><span class="clickbtn saveEmployee" i="<?php echo $_smarty_tpl->tpl_vars['e']->value['id'];?>
"> [保存] </span>
				<span class="clickbtn editPrivilege" i="<?php echo $_smarty_tpl->tpl_vars['e']->value['id'];?>
"> [权限] </span>
				<span class="clickbtn resetPassword" i="<?php echo $_smarty_tpl->tpl_vars['e']->value['id'];?>
"> [密码重置] </span>
				<?php if ($_smarty_tpl->tpl_vars['e']->value['ifleave']==1){?> <span class="clickbtn deleteEmployee" i="<?php echo $_smarty_tpl->tpl_vars['e']->value['id'];?>
"> [删除] </span><?php }?></td>
		</tr>
		<?php }} ?>
	</table>
</div>

<div id="alertDiv"></div>

<?php $_template = new Smarty_Internal_Template("declaration.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>

<script>
$(function(){
	//add one employee
	$('#addEmployee').click(function(){
		var namezh = $.trim($('#namezh').val());
		var name = $.trim($('#name').val());
		var short_name = $.trim($('#short_name').val());
		var email = $.trim($('#email').val());
		var employee_no = $.trim($('#employee_no').val());
		var ifleave = $.trim($('#ifleave').val());
		if(name != '' && email != '' && employee_no != '' && short_name != ''){
			$.post('index.php?m=system&s=setup&a=employee.add',{'namezh':namezh, 'name':name, 'short_name':short_name, 'email':email, 'employee_no':employee_no, 'ifleave':ifleave}, function(data){
				var json = eval('(' + data + ')');
				if(json.httpstatus == 200){
					alert(json.msg);
					window.location.reload();
				}else{
					alert(json.error);
				}
			})
		}else{
			alert('除了中文名，其他内容必填');
		}
	})
	
	//delete one employee
	$('.deleteEmployee').click(function(){
		if(confirm('确认删除此员工？'))
		var id = $(this).attr('i');
		$.post('index.php?m=system&s=setup&a=employee.delete',{'id':id}, function(data){
			var json = eval('(' + data + ')');
			if(json.httpstatus == 200){
				alert(json.msg);
				window.location.reload();
			}else{
				alert(json.error);
			}			
		})
	})
	
	//save one employee
	$('.saveEmployee').click(function(){
		var id = $(this).attr('i');
		var namezh = $.trim($(this).parents('tr').find('.namezh').val());
		var name = $.trim($(this).parents('tr').find('.name').val());
		var short_name = $.trim($(this).parents('tr').find('.short_name').val());
		var email = $.trim($(this).parents('tr').find('.email').val());
		var employee_no = $.trim($(this).parents('tr').find('.employee_no').val());
		var ifleave = $.trim($(this).parents('tr').find('.ifleave:checked').val());
		ifleave = (ifleave=='stay') ? 0 : 1;
		if(name != '' && email != '' && employee_no != '' && short_name != ''){
			$.post('index.php?m=system&s=setup&a=employee.save',{'id':id, 'namezh':namezh, 'name':name, 'short_name':short_name, 'email':email, 'employee_no':employee_no, 'ifleave':ifleave}, function(data){
				var json = eval('(' + data + ')');
				if(json.httpstatus == 200){
					alert(json.msg);
					window.location.reload();
				}else{
					alert(json.error);
				}			
			})
		}else{
			alert('除了中文名，其他内容必填');
		}
	})
	
	//edit employee privilege
	$('.editPrivilege').click(function(){
		var id = $(this).attr('i');
		$('#alertDiv').html('').dialog({
			modal: true,
			width: 500,
			height: 450,
			buttons: {
				'保存': function(){
					$.post('index.php?m=system&s=setup&a=employee.privilege.save',$('#editPrivilegeForm').serialize(),function(data){
						var json = eval('(' + data + ')');
						if(json.httpstatus == 200){
							alert(json.msg);
							$('#alertDiv').dialog('close').html('');
						}else{
							alert(json.error);
						}
					})
				},
				'取消': function(){$('#alertDiv').dialog('close').html('')}
			}
		}).load('index.php?m=system&s=setup&a=employee.privilege.edit',{'id':id});
	})
	
	//reset employee's password
	$('.resetPassword').click(function(){
		if(confirm('确认要重置此用户的密码？')){
			var id = $(this).attr('i');
			$.post('index.php?m=system&s=setup&a=employee.password.reset',{'id':id},function(data){
				var json = eval('(' + data + ')');
				if(json.httpstatus == 200){
					alert(json.msg);
				}else{
					alert(json.error);
				}
			})			
		}
	})
})
</script>