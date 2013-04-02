<?php /* Smarty version Smarty-3.0.7, created on 2013-04-02 23:15:16
         compiled from "E:\UATME_OA/template/modules\hr/annual.list.html" */ ?>
<?php /*%%SmartyHeaderCode:23232515af6041da278-06877219%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f0b46d4777c9ff60fcb6f51228942d713ee492dc' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\hr/annual.list.html',
      1 => 1364915710,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '23232515af6041da278-06877219',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template("header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("topnav.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>


<div class="container ">
	<table>
		<tr>
			<th>姓名<span class="clickbtn annualExport">[导出Excel]</span></th>
			<th>总年假</th>
			<th>已使用年假</th>
			<th>剩余年假</th>
			<th>操作</th>
		</tr>
		<?php  $_smarty_tpl->tpl_vars['e'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('employee')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['e']->key => $_smarty_tpl->tpl_vars['e']->value){
?>
		<tr>
			<td><?php echo $_smarty_tpl->tpl_vars['e']->value['namezh'];?>
 ( <?php echo $_smarty_tpl->tpl_vars['e']->value['name'];?>
 )</td>
			<td><input value="<?php echo $_smarty_tpl->getVariable('total')->value[$_smarty_tpl->tpl_vars['e']->value['id']];?>
" class="span-2 annualLeaveTotal"/></td>
			<td><?php echo $_smarty_tpl->getVariable('used')->value[$_smarty_tpl->tpl_vars['e']->value['id']];?>
</td>
			<td><?php echo $_smarty_tpl->getVariable('total')->value[$_smarty_tpl->tpl_vars['e']->value['id']]-$_smarty_tpl->getVariable('used')->value[$_smarty_tpl->tpl_vars['e']->value['id']];?>
</td>
			<td><span class="clickbtn saveAnnualLeave" i="<?php echo $_smarty_tpl->tpl_vars['e']->value['id'];?>
"> [保存] </span></td>
		</tr>
		<?php }} ?>
	</table>
</div>

<div id="alertDiv">数据处理中，请稍候……</div>

<?php $_template = new Smarty_Internal_Template("declaration.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>

<script type="text/javascript">
$(document).ready(function(){
	$('#alertDiv').dialog({
		'modal':true,
		'autoOpen':false
	});
	
	$('.saveAnnualLeave').click(function(){
		$('#alertDiv').dialog('open');
		$.post('index.php?m=hr&s=setup&a=annual.save',{'id':$(this).attr('i'),'count':($(this).parents('tr').find('.annualLeaveTotal').val()-0)},function(data){
			var json = eval('(' + data + ')');
			if(json.httpstatus == 200){
				alert(json.msg);
				window.location.reload();
			}else{
				alert(json.error);
				$('#alertDiv').dialog('close');
			}
		})
	})
	
	$('.annualExport').click(function(){	
		window.open('index.php?m=hr&s=setup&a=annual.setup.export');
	})
})
</script>