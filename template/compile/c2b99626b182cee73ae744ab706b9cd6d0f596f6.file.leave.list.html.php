<?php /* Smarty version Smarty-3.0.7, created on 2013-03-31 23:23:04
         compiled from "E:\UATME_OA/template/modules\hr/leave.list.html" */ ?>
<?php /*%%SmartyHeaderCode:2387515854d803cf53-43107259%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c2b99626b182cee73ae744ab706b9cd6d0f596f6' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\hr/leave.list.html',
      1 => 1359644238,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2387515854d803cf53-43107259',
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
		<thead>
			<tr><th>申请人</th><th>假期类型</th><th>起始-结束</th><th>代办</th><th>状态</th><th>操作</th></tr>
		</thead>
		<tbody>
		<?php  $_smarty_tpl->tpl_vars['a'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('apply')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['a']->key => $_smarty_tpl->tpl_vars['a']->value){
?>
			<tr><td><?php echo $_smarty_tpl->tpl_vars['a']->value['applyer'];?>
</td><td><?php echo $_smarty_tpl->tpl_vars['a']->value['type'];?>
</td><td><?php echo $_smarty_tpl->tpl_vars['a']->value['start'];?>
 ~ <?php echo $_smarty_tpl->tpl_vars['a']->value['end'];?>
</td><td><?php echo $_smarty_tpl->tpl_vars['a']->value['alter'];?>
</td><td><?php echo $_smarty_tpl->tpl_vars['a']->value['status'];?>
</td><td></td></tr>
		<?php }} ?>
		</tbody>
	</table>
</div>

<?php $_template = new Smarty_Internal_Template("declaration.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>