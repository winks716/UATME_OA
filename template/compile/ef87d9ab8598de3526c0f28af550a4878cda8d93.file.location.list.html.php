<?php /* Smarty version Smarty-3.0.7, created on 2013-02-02 01:26:20
         compiled from "E:\UATME_OA/template/modules\system/location.list.html" */ ?>
<?php /*%%SmartyHeaderCode:22670510bfabc1df4c5-69089482%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ef87d9ab8598de3526c0f28af550a4878cda8d93' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\system/location.list.html',
      1 => 1359739559,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '22670510bfabc1df4c5-69089482',
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
			<th>地区名称</th>
			<th>地区缩写</th>
			<th>所属国家</th>
			<th>可用</th>
			<th>操作</th>
		</tr>
		<tr>
			<td><input id="name" class="span-2"/>+新增</td>
			<td><input id="nameshort" class="span-3"/></td>
			<td><select id="parentid">
				<?php  $_smarty_tpl->tpl_vars['c'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('country')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['c']->key => $_smarty_tpl->tpl_vars['c']->value){
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['c']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['c']->value['name'];?>
</option>
				<?php }} ?>
				</select></td>
			<td><input id="available" type="checkbox" value="1" checked/></td>
			<td><span class="clickbtn span-2" id="addLocation"> [添加] </span></td>
		</tr>
		<?php  $_smarty_tpl->tpl_vars['l'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('location')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['l']->key => $_smarty_tpl->tpl_vars['l']->value){
?>
		<tr>
			<td><input value="<?php echo $_smarty_tpl->tpl_vars['l']->value['name'];?>
" class="span-2 name"/></td>
			<td><input value="<?php echo $_smarty_tpl->tpl_vars['l']->value['nameshort'];?>
" class="span-3 nameshort"/></td>
			<td><select class="parentid">
				<?php  $_smarty_tpl->tpl_vars['c'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('country')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['c']->key => $_smarty_tpl->tpl_vars['c']->value){
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['c']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['l']->value['country_id']==$_smarty_tpl->tpl_vars['c']->value['id']){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['c']->value['name'];?>
</option>
				<?php }} ?>
				</select></td>
			<td><input value="1" class="span-1 available" type="checkbox" <?php if ($_smarty_tpl->tpl_vars['l']->value['available']==1){?>checked<?php }?>/></td>
			<td><span class="clickbtn saveLocation" i="<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
"> [保存] </span> <span class="clickbtn deleteLocation" i="<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
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
	$('#addLocation').click(function(){
		var name = $('#name').val();
		var nameshort = $('#nameshort').val();
		var parentid = $('#parentid').val();
		var available = $('#available:checked').val();
		if(name!='' || nameshort!=''){
			$.post('index.php?m=system&s=setup&a=location.add', {'name':name,'nameshort':nameshort,'parentid':parentid,'available':available}, function(data){
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
	$('.saveLocation').click(function(){
		var o = $(this).parents('tr');
		var name = o.find('.name').val();
		var nameshort = o.find('.nameshort').val();
		var parentid = o.find('.parentid').val();
		var available = o.find('.available:checked').val();
		var id = $(this).attr('i');
		if(name!='' || nameshort!=''){
			$.post('index.php?m=system&s=setup&a=location.save', {'id':id,'name':name,'nameshort':nameshort,'parentid':parentid,'available':available}, function(data){
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