<?php /* Smarty version Smarty-3.0.7, created on 2013-04-01 22:09:54
         compiled from "E:\UATME_OA/template/modules\hr/leave.apply.html" */ ?>
<?php /*%%SmartyHeaderCode:25878515995324bde99-15423096%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '85cfaad3f7167dce5b00bd8b577a65831b46778a' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\hr/leave.apply.html',
      1 => 1364813157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '25878515995324bde99-15423096',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template("header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("topnav.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>

<style>
.applyTable th{
	text-align:right;
}
</style>

<div class="container applyTable">
<form id="applyForm">
	<table>
		<tr><th>假期类型</th>
			<td><select id="leaveType" name="leaveType">
				<option value="0">-请选择假期类型-</option>
				<?php  $_smarty_tpl->tpl_vars['l'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('leave')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['l']->key => $_smarty_tpl->tpl_vars['l']->value){
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['l']->value['name'];?>
</option>
				<?php }} ?>
			</select></td></tr>
		<tr><th>起始日期</th><td><input id="leaveStartDate" name="leaveStartDate" readonly/>
								<select id="leaveStartTime" name="leaveStartTime"><option value="09:00:00">上午</option><option value="13:00:00">下午</option></select></td></tr>
		<tr><th>结束日期</th><td><input id="leaveEndDate" name="leaveEndDate" readonly/>
								<select id="leaveEndTime" name="leaveEndTime"><option value="17:00:00">下午</option><option value="13:00:00">上午</option></select></td></tr>
		<tr><th>请假事由</th><td><input id="leaveReason" name="leaveReason" class="span-16"/></td></tr>
		<tr><th>不在公司期间<br/>的指定代办人</th><td><select id="leaveAlter" name="leaveAlter">
									<option value="0">-请选择指定代办人-</option>
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
								</select></td></tr>
		<tr><th>申请结果抄送</th><td><select id="leaveCopy" name="leaveCopy[]" multiple="multiple">
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
								</select></td></tr>
		<tr><td></td><td><span class="clickbtn" id="applyConfirm">[确认申请]</span></td></tr>
	</table>
</form>
</div>

<div id="alertDiv">假期申请发送中，请耐心等待12秒左右，切勿关闭此窗口，谢谢！</div>

<?php $_template = new Smarty_Internal_Template("declaration.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>

<script type="text/javascript">
	$(function(){
		$('#alertDiv').hide().dialog({
			'modal':true,
			'autoOpen':false
		});
		
		$('#leaveStartDate, #leaveEndDate').datepicker({
			'dateFormat':'yy-mm-dd'
		});
		
		$('#applyConfirm').click(function(){
			if(confirm('确认递交申请？')){
				if($('#leaveType').val()==0 || $('#leaveStartDate').val()=='' || $('#leaveEndDate').val()=='' || $('#leaveAlter').val()==0){
					alert('假期类型、起始-结束日期、指定代办员工为必填项，请检查！');
				}else{
					$('#alertDiv').dialog('open');
					$.post('index.php?m=hr&s=leave&a=submit', $('#applyForm').serialize(), function(data){
						var json = eval('(' + data + ')');
						if(json.httpstatus == 200){
							alert(json.msg);
							window.location = 'index.php?m=hr&s=leave&a=list';
						}else{
							alert(json.error);
							$('#alertDiv').dialog('close');
						}
					})					
				}				
			}
		})
	})
</script>