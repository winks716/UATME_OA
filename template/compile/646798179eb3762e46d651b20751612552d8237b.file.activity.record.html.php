<?php /* Smarty version Smarty-3.0.7, created on 2013-05-08 01:29:17
         compiled from "E:\UATME_OA/template/modules\sales/activity.record.html" */ ?>
<?php /*%%SmartyHeaderCode:16145518939ed424447-67881519%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '646798179eb3762e46d651b20751612552d8237b' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\sales/activity.record.html',
      1 => 1367947751,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16145518939ed424447-67881519',
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
	<table class="span-23">
		<tr><td class="span-6">
			<strong>添加活动</strong>
			<hr/>
			<form id="activityTable">
				<table class="span-7">
					<tr><th>日期</th><td><input readonly id="date" name="date" class="span-3"/></td></tr>
					<tr><th>时间</th><td><input readonly id="start" name="start" class="span-2"/> ~ <input readonly id="end" name="end" class="span-2"/></td></tr>
					<tr><th>客户</th><td><select id="customerId" name="customerId">
											<?php  $_smarty_tpl->tpl_vars['c'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('customer')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['c']->key => $_smarty_tpl->tpl_vars['c']->value){
?>
											<option value="<?php echo $_smarty_tpl->tpl_vars['c']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['c']->value['name'];?>
</option>
											<?php }} ?>
										</select></td></tr>
					<tr><th>活动</th><td><textarea class="span-5" id="detail" name="detail" style="height:50px;"></textarea></td></tr>
					<tr><td colspan="2"><span class="clickbtn activitySave">[递交]</span></td></tr>
				</table>
			</form>
		</td>
		<td class="span-16">
			<table class="span-16">
				<thead>
					<tr><th>日期</th><th>开始</th><th>结束</th><th>客户</th><th>活动</th></tr>
				</thead>
				<tbody>
					<?php  $_smarty_tpl->tpl_vars['a'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('activity')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['a']->key => $_smarty_tpl->tpl_vars['a']->value){
?>
					<tr><td><?php echo $_smarty_tpl->tpl_vars['a']->value['date'];?>
</td><td><?php echo $_smarty_tpl->tpl_vars['a']->value['start'];?>
</td><td><?php echo $_smarty_tpl->tpl_vars['a']->value['end'];?>
</td><td><?php echo $_smarty_tpl->tpl_vars['a']->value['customer'];?>
</td><td><?php echo $_smarty_tpl->tpl_vars['a']->value['detail'];?>
</td></tr>
					<?php }} ?>
				</tbody>
			</table>
		</td></tr>
	</table>
</div>

<?php $_template = new Smarty_Internal_Template("declaration.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>

<script>
$(function(){
	var date = new Date();
	var day = date.getDay();
	$('#date').datepicker({
		dateFormat: 'yy-mm-dd',
		minDate: '-'+day+'D',
		maxDate: '+'+(6-day)+'D'
	});
	$('#start, #end').timePicker({step:30, startTime:"08:00", endTime:"20:00"});
	$('.activitySave').click(function(){
		alert($('#activityTable').serialize());
		$.post('index.php?m=sales&s=activity&a=save',$('#activityTable').serialize(),function(data){
			alert(data);
		})
	})
})
</script>