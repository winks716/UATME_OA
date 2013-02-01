<?php /* Smarty version Smarty-3.0.7, created on 2013-02-02 01:05:33
         compiled from "E:\UATME_OA/template/modules\system/country.list.html" */ ?>
<?php /*%%SmartyHeaderCode:23106510bf5dd750a98-65210328%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '86e073e44bc808fed3678e1a713f18401aff19f4' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\system/country.list.html',
      1 => 1359738318,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '23106510bf5dd750a98-65210328',
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
			<th>国家名称</th>
			<th>国家缩写</th>
			<th>可用</th>
			<th>操作</th>
		</tr>
		<tr>
			<td><input id="name" class="span-2"/>+新增</td>
			<td><input id="nameshort" class="span-3"/></td>
			<td><input id="available" type="checkbox" value="1" checked/></td>
			<td><span class="clickbtn span-2" id="addCountry"> [添加] </span></td>
		</tr>
		<?php  $_smarty_tpl->tpl_vars['c'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('country')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['c']->key => $_smarty_tpl->tpl_vars['c']->value){
?>
		<tr>
			<td><input value="<?php echo $_smarty_tpl->tpl_vars['c']->value['name'];?>
" class="span-2 name"/></td>
			<td><input value="<?php echo $_smarty_tpl->tpl_vars['c']->value['nameshort'];?>
" class="span-3 nameshort"/></td>
			<td><input value="1" class="span-1 available" type="checkbox" <?php if ($_smarty_tpl->tpl_vars['c']->value['available']==1){?>checked<?php }?>/></td>
			<td><span class="clickbtn saveCountry" i="<?php echo $_smarty_tpl->tpl_vars['c']->value['id'];?>
"> [保存] </span> <span class="clickbtn deleteCountry" i="<?php echo $_smarty_tpl->tpl_vars['c']->value['id'];?>
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
	$('#addCountry').click(function(){
		var name = $('#name').val();
		var nameshort = $('#nameshort').val();
		var available = $('#available:checked').val();
		if(name!='' || nameshort!=''){
			$.post('index.php?m=system&s=setup&a=country.add', {'name':name,'nameshort':nameshort,'available':available}, function(data){
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
	$('.saveCountry').click(function(){
		var o = $(this).parents('tr');
		var name = o.find('.name').val();
		var nameshort = o.find('.nameshort').val();
		var available = o.find('.available:checked').val();
		var id = $(this).attr('i');
		if(name!='' || nameshort!=''){
			$.post('index.php?m=system&s=setup&a=country.save', {'id':id,'name':name,'nameshort':nameshort,'available':available}, function(data){
				alert(data);
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