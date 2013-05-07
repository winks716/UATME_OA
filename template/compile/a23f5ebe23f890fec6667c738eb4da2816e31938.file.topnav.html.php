<?php /* Smarty version Smarty-3.0.7, created on 2013-05-07 22:09:38
         compiled from "E:\UATME_OA/template/modules\topnav.html" */ ?>
<?php /*%%SmartyHeaderCode:346151890b22766cd7-14872598%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a23f5ebe23f890fec6667c738eb4da2816e31938' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\topnav.html',
      1 => 1365683194,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '346151890b22766cd7-14872598',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div id="topNavigation">
	  <table><tr>
	  <td><img src="images/global/logo.highsource.small.gif" border="0"/></td>
	  <td><span class="">色彩
		<?php  $_smarty_tpl->tpl_vars['t'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('themeList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['t']->key => $_smarty_tpl->tpl_vars['t']->value){
?>
		  <a class="switchTheme border" title="<?php echo $_smarty_tpl->tpl_vars['t']->value['name'];?>
" href="javascript:void(0)" style="height:15px; width:15px; display:inline-block; background-color:<?php echo $_smarty_tpl->tpl_vars['t']->value['bgcolor'];?>
;"></a>
		<?php }} ?>
	  </span></td>
	  <td><span class="">欢迎, <?php echo $_smarty_tpl->getVariable('session')->value['employee_name'];?>
(<?php echo $_smarty_tpl->getVariable('session')->value['employee_namezh'];?>
)</span></td>
	  <td>待完成: <a href="index.php?m=base&s=self&a=task.list"><span id="taskCount">...</span>个任务</a></td>
	  <td><span class=""><span class="clickbtn" id="logOff"> [退出] </span></span></td>
	  </tr></table>
</div>

<div id="mainNavigationDiv" class="ui-widget-header">
<table id="mainNavigation">
	<tr>
	<?php  $_smarty_tpl->tpl_vars['m'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('menu')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['m']->key => $_smarty_tpl->tpl_vars['m']->value){
?>
		<td class="menu ui-state-default ui-corner-top <?php if ($_smarty_tpl->tpl_vars['m']->value['module']==$_smarty_tpl->getVariable('request_module')->value){?>ui-state-active<?php }?>">
			<a href="<?php echo $_smarty_tpl->tpl_vars['m']->value['href'];?>
" i="<?php echo $_smarty_tpl->tpl_vars['m']->value['id'];?>
" p="0" class="ui-tabs-anchor"><?php echo $_smarty_tpl->tpl_vars['m']->value['label'];?>
</a>
		</td>
	<?php }} ?>
	<td></td>
	</tr>
</table>
</div>

<table id="subNavigationTable">
<?php  $_smarty_tpl->tpl_vars['m'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('menu')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['m']->key => $_smarty_tpl->tpl_vars['m']->value){
?>
<tr class="subNavigation <?php if ($_smarty_tpl->tpl_vars['m']->value['module']==$_smarty_tpl->getVariable('request_module')->value){?>active<?php }else{ ?>disactive<?php }?>">
	<?php  $_smarty_tpl->tpl_vars['sm'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('submenu')->value[$_smarty_tpl->tpl_vars['m']->value['id']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['sm']->key => $_smarty_tpl->tpl_vars['sm']->value){
?>
		<td class="menu">
			<a href="<?php echo $_smarty_tpl->tpl_vars['sm']->value['href'];?>
" i="<?php echo $_smarty_tpl->tpl_vars['sm']->value['id'];?>
" p="<?php echo $_smarty_tpl->tpl_vars['m']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['sm']->value['module']==$_smarty_tpl->getVariable('request_module')->value&&$_smarty_tpl->tpl_vars['sm']->value['submodule']==$_smarty_tpl->getVariable('request_submodule')->value&&$_smarty_tpl->tpl_vars['sm']->value['action']==$_smarty_tpl->getVariable('request_action')->value){?>class="ui-state-active"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sm']->value['label'];?>
</a>
		</td>
	<?php }} ?>
	<td class="menuBlank"></td>
</tr>
<?php }} ?>
</table>

<?php if ($_smarty_tpl->getVariable('session')->value['if_announced']==0&&$_smarty_tpl->getVariable('announce')->value[0]['id']>0){?>
<div id="announce" title="公司通告">
	<?php  $_smarty_tpl->tpl_vars['a'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('announce')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['a']->key => $_smarty_tpl->tpl_vars['a']->value){
?>
	<div class="item append-bottom notice">
		<div class="title"><label><?php echo $_smarty_tpl->tpl_vars['a']->value['title'];?>
</label>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $_smarty_tpl->tpl_vars['a']->value['update_date'];?>
</div>
		<div class="detail"><textarea readonly=readonly style="width:635px;height:235px"><?php echo $_smarty_tpl->tpl_vars['a']->value['detail'];?>
</textarea></div>
	</div>
	<?php }} ?>
</div>
<?php }?>

<script type="text/javascript">
//init logoff button action
$('#logOff').click(function(){
	$.post('index.php?m=base&s=logoff',{},function(){
		window.location = 'index.php';
	})
})
//init switch theme button action
$('.switchTheme').click(function(){
	var themeName = $(this).attr('title');
	$.post('index.php?m=base&s=theme&a=switch',{'themeName':themeName},function(){
		$('link[rel*=style][title]').each(function(){
            if (this.getAttribute('title') == themeName){
				this.disabled = false;
			}else{
				this.disabled = true;
			}
        });
	})
})
//INIT company announce
$('#announce').dialog({
	autoOpen: true,
	height: 450,
	width: 700,
	modal: true,
	buttons: {
		'确认': function(){
			$('#announce').dialog('close');
		}
	}
})
//INIT task count number
$.post('index.php?m=base&s=self&a=task.count',{},function(data){
	var json = eval('('+ data +')');
	if(json.httpstatus==200){
		$('#taskCount').html(json.msg);
	}else{
		$('#taskCount').html('0');
	}
})
</script>