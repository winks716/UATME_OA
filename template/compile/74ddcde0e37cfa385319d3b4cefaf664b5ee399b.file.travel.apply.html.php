<?php /* Smarty version Smarty-3.0.7, created on 2013-02-02 02:10:56
         compiled from "E:\UATME_OA/template/modules\hr/travel.apply.html" */ ?>
<?php /*%%SmartyHeaderCode:23784510c053035bfa7-02172718%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '74ddcde0e37cfa385319d3b4cefaf664b5ee399b' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\hr/travel.apply.html',
      1 => 1359644238,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '23784510c053035bfa7-02172718',
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
		<tr><th>目的地</th><td><input id="travelTarget"/></td></tr>
		<tr><th>起始日期</th><td><input id="travelStartDate" readonly/>
								<select id="travelStartTime"><option value="09:00:00">上午</option><option value="13:00:00">下午</option></select></td></tr>
		<tr><th>结束日期</th><td><input id="travelEndDate" readonly/>
								<select id="travelEndTime"><option value="13:00:00">上午</option><option value="17:00:00">下午</option></select></td></tr>
		<tr><th>出差事由</th><td><textarea id="travelReason"></textarea></td></tr>
		<tr><th>费用预算</th><td><select id="travelExpenseCurrencyId"><option value="1">人民币</option></select>
								<input id="travelExpense"/></td></tr>
		<tr><th>指定代办</th><td><select id="leaveAlter" name="travelAlter">
									<option value="0">-请选择指定代办员工-</option>
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
		<tr><th></th><td><span class="clickbtn" id="applyConfirm">[确认申请]</span></td></tr>
	</table>
</form>
</div>

<div id="alertDiv">差旅申请处理中，请耐心等待，切勿关闭此窗口，谢谢！</div>

<?php $_template = new Smarty_Internal_Template("declaration.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>

<script type="text/javascript">
	$(function(){
		$('#alertDiv').dialog({
			'modal':true,
			'autoOpen':false
		});
		
		$('#travelStartDate, #travelEndDate').datepicker({
			'dateFormat':'yy-mm-dd'
		});
		
		$('#applyConfirm').click(function(){
			if(confirm('确认递交申请？')){
				$('#alertDiv').dialog('open');
				$.post('index.php?m=hr&s=travel&a=submit', $('#applyForm').serialize(), function(data){
					var json = eval('(' + data + ')');
					if(json.httpstatus == 200){
						alert(json.msg);
						window.location = 'index.php?m=hr&s=travel&a=list';
					}else{
						alert(json.error);
					}
				})				
			}
		})
	})
</script>