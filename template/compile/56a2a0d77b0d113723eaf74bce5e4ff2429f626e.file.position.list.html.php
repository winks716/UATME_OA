<?php /* Smarty version Smarty-3.0.7, created on 2013-02-02 01:52:28
         compiled from "E:\UATME_OA/template/modules\system/position.list.html" */ ?>
<?php /*%%SmartyHeaderCode:12578510c00dcbee2a6-66287416%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '56a2a0d77b0d113723eaf74bce5e4ff2429f626e' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\system/position.list.html',
      1 => 1359741028,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12578510c00dcbee2a6-66287416',
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
			<th>职位名称</th>
			<th>职位缩写</th>
			<th>所属部门</th>
			<th>可用</th>
			<th>操作</th>
		</tr>
		<tr>
			<td><input id="name" class="span-2"/>+新增</td>
			<td><input id="nameshort" class="span-3"/></td>
			<td><select id="parentid">
				<?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('department')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value){
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['d']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['d']->value['name'];?>
</option>
				<?php }} ?>
				</select></td>
			<td><input id="available" type="checkbox" value="1" checked/></td>
			<td><span class="clickbtn span-2" id="addPosition"> [添加] </span></td>
		</tr>
		<?php  $_smarty_tpl->tpl_vars['p'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('position')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['p']->key => $_smarty_tpl->tpl_vars['p']->value){
?>
		<tr>
			<td><input value="<?php echo $_smarty_tpl->tpl_vars['p']->value['name'];?>
" class="span-2 name"/></td>
			<td><input value="<?php echo $_smarty_tpl->tpl_vars['p']->value['nameshort'];?>
" class="span-3 nameshort"/></td>
			<td><select class="parentid">
				<?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('department')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value){
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['d']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['p']->value['department_id']==$_smarty_tpl->tpl_vars['d']->value['id']){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['d']->value['name'];?>
</option>
				<?php }} ?>
				</select></td>
			<td><input value="1" class="span-1 available" type="checkbox" <?php if ($_smarty_tpl->tpl_vars['p']->value['available']==1){?>checked<?php }?>/></td>
			<td><span class="clickbtn savePosition" i="<?php echo $_smarty_tpl->tpl_vars['p']->value['id'];?>
"> [保存] </span> <span class="clickbtn deletePosition" i="<?php echo $_smarty_tpl->tpl_vars['p']->value['id'];?>
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
	$('#addPosition').click(function(){
		var name = $('#name').val();
		var nameshort = $('#nameshort').val();
		var parentid = $('#parentid').val();
		var available = $('#available:checked').val();
		if(name!='' || nameshort!=''){
			$.post('index.php?m=system&s=setup&a=position.add', {'name':name,'nameshort':nameshort,'parentid':parentid,'available':available}, function(data){
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
	$('.savePosition').click(function(){
		var o = $(this).parents('tr');
		var name = o.find('.name').val();
		var nameshort = o.find('.nameshort').val();
		var parentid = o.find('.parentid').val();
		var available = o.find('.available:checked').val();
		var id = $(this).attr('i');
		if(name!='' || nameshort!=''){
			$.post('index.php?m=system&s=setup&a=position.save', {'id':id,'name':name,'nameshort':nameshort,'parentid':parentid,'available':available}, function(data){
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