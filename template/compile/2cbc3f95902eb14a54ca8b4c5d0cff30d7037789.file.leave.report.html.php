<?php /* Smarty version Smarty-3.0.7, created on 2013-04-02 22:27:42
         compiled from "E:\UATME_OA/template/modules\hr/leave.report.html" */ ?>
<?php /*%%SmartyHeaderCode:25853515aeade1d6d53-96043131%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2cbc3f95902eb14a54ca8b4c5d0cff30d7037789' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\hr/leave.report.html',
      1 => 1364912856,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '25853515aeade1d6d53-96043131',
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
#report tr.company td{background-color:#336699;color:#ffffff;font-weight:bold;cursor:normal;}
#report tr.location td{background-color:#6699cc;color:#ffffff;cursor:normal;}
#report tr.department td{background-color:#99ccff;cursor:normal;}
#report tr.employee td{background-color:#eeeeee;display:;}
#report tr.employee td.nonestyle{background:none;cursor:normal;}
#report tr.employee td.employee:hover{background-color:pink;display:;cursor:pointer;}
#report tr.employee td.nonestyle:hover{background:none;cursor:normal;}
#report tr.apply td.nonestyle{background:none;cursor:normal;}
#report tr.apply td.detail{background:none;cursor:normal;}
#report tr.apply td.detail table td{background:none;}
</style>

<div class="container">
<strong>年份</strong>
<select id="yearSelect">
<option <?php if ($_smarty_tpl->getVariable('yearSelect')->value=="2012"){?>selected<?php }?>>2012</option>
<option <?php if ($_smarty_tpl->getVariable('yearSelect')->value=="2013"){?>selected<?php }?>>2013</option>
<option <?php if ($_smarty_tpl->getVariable('yearSelect')->value=="2014"){?>selected<?php }?>>2014</option>
<option <?php if ($_smarty_tpl->getVariable('yearSelect')->value=="2015"){?>selected<?php }?>>2015</option>
<option <?php if ($_smarty_tpl->getVariable('yearSelect')->value=="2016"){?>selected<?php }?>>2016</option>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;
<strong>时间</strong>
<select id="timeSelect">
<option value="0">-请选择-</option>
<option value="1" <?php if ($_smarty_tpl->getVariable('timeSelect')->value=="1"){?>selected<?php }?>>第一季度</option>
<option value="2" <?php if ($_smarty_tpl->getVariable('timeSelect')->value=="2"){?>selected<?php }?>>第二季度</option>
<option value="3" <?php if ($_smarty_tpl->getVariable('timeSelect')->value=="3"){?>selected<?php }?>>第三季度</option>
<option value="4" <?php if ($_smarty_tpl->getVariable('timeSelect')->value=="4"){?>selected<?php }?>>第四季度</option>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;
<strong>假期类型</strong>
<select id="typeSelect">
<option value="0">-请选择-</option>
<?php  $_smarty_tpl->tpl_vars['t'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('type')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['t']->key => $_smarty_tpl->tpl_vars['t']->value){
?>
<option value="<?php echo $_smarty_tpl->tpl_vars['t']->value['id'];?>
" <?php if ($_smarty_tpl->getVariable('typeSelect')->value==$_smarty_tpl->tpl_vars['t']->value['id']){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['t']->value['name'];?>
</option>
<?php }} ?>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;
<strong>员工</strong>
<select id="employeeSelect">
<option value="0">-请选择-</option>
<?php  $_smarty_tpl->tpl_vars['e'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('employee')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['e']->key => $_smarty_tpl->tpl_vars['e']->value){
?>
<option value="<?php echo $_smarty_tpl->tpl_vars['e']->value['id'];?>
" <?php if ($_smarty_tpl->getVariable('employeeSelect')->value==$_smarty_tpl->tpl_vars['e']->value['id']){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['e']->value['namezh'];?>
(<?php echo $_smarty_tpl->tpl_vars['e']->value['name'];?>
)</option>
<?php }} ?>
</select>
<span class="clickbtn reportSearch">[查看]</span>
<span class="clickbtn reportExport">[导出Excel]</span>
</div>

<div class="container">
<table id="report">
<tr class="company"><td class="span-3"><?php echo $_smarty_tpl->getVariable('count')->value['whole_company']['name'];?>
</td><td><?php if ($_smarty_tpl->getVariable('count')->value['whole_company']['count']!=0){?><?php echo $_smarty_tpl->getVariable('count')->value['whole_company']['count'];?>
<?php }else{ ?>0<?php }?>天</td></tr>
<?php  $_smarty_tpl->tpl_vars['l'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('location')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['l']->key => $_smarty_tpl->tpl_vars['l']->value){
?>
<?php if ($_smarty_tpl->getVariable('count')->value["location_".($_smarty_tpl->tpl_vars['l']->value['id'])]['count']!=0){?>
<tr class="location"><td class="span-3"><?php echo $_smarty_tpl->tpl_vars['l']->value['name'];?>
</td><td><?php echo $_smarty_tpl->getVariable('count')->value["location_".($_smarty_tpl->tpl_vars['l']->value['id'])]['count'];?>
天</td></tr>
	<?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('department')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value){
?>
	<?php if ($_smarty_tpl->tpl_vars['d']->value['location_id']==$_smarty_tpl->tpl_vars['l']->value['id']&&$_smarty_tpl->getVariable('count')->value["department_".($_smarty_tpl->tpl_vars['d']->value['id'])]['count']!=0){?>
	<tr class="department"><td><?php echo $_smarty_tpl->tpl_vars['d']->value['name'];?>
</td><td><?php echo $_smarty_tpl->getVariable('count')->value["department_".($_smarty_tpl->tpl_vars['d']->value['id'])]['count'];?>
天</td></tr>
		<?php  $_smarty_tpl->tpl_vars['e'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('employee')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['e']->key => $_smarty_tpl->tpl_vars['e']->value){
?>
		<?php if ($_smarty_tpl->tpl_vars['e']->value['department_id']==$_smarty_tpl->tpl_vars['d']->value['id']&&$_smarty_tpl->getVariable('count')->value["employee_".($_smarty_tpl->tpl_vars['e']->value['id'])]['count']!=0){?>
		<tr class="employee"><td class="nonestyle"></td><td class="employee" i="<?php echo $_smarty_tpl->tpl_vars['e']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['e']->value['namezh'];?>
 (<?php echo $_smarty_tpl->tpl_vars['e']->value['name'];?>
)  <?php echo $_smarty_tpl->getVariable('count')->value["employee_".($_smarty_tpl->tpl_vars['e']->value['id'])]['count'];?>
天</td></tr>
		<?php }?>
		<?php }} ?>
	<?php }?>
	<?php }} ?>
<?php }?>
<?php }} ?>
</table>
</div>

<?php $_template = new Smarty_Internal_Template("declaration.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>

<script type="text/javascript">
$(function(){
	$('tr.employee td.employee').click(function(){
		$('tr.apply').remove();
		var o = $(this);
		var id = $(o).attr('i');
		$(o).parent().after('<tr class="apply"><td class="nonestyle"></td><td class="detail">等待数据载入……</td></tr>');
		$.post('index.php?m=hr&s=report&a=get.employee.leave',{'id':id},function(data){
			var json = eval('(' + data + ')');
			if(json.httpstatus==200){
				$('tr.apply').find('td.detail').html(json.msg);
			}else{
				$('tr.apply').remove();
				alert(json.error);
			}
		})
	})
	
	$('.reportSearch').click(function(){
		window.location = 'index.php?m=hr&s=report&a=leave.apply.report&employeeSelect='+$('#employeeSelect').val()+'&yearSelect='+$('#yearSelect').val()+'&timeSelect='+$('#timeSelect').val()+'&typeSelect='+$('#typeSelect').val();
	})
	$('.reportExport').click(function(){
		window.open('index.php?m=hr&s=report&a=leave.apply.report.export&employeeSelect='+$('#employeeSelect').val()+'&yearSelect='+$('#yearSelect').val()+'&timeSelect='+$('#timeSelect').val()+'&typeSelect='+$('#typeSelect').val());
	})
})
</script>