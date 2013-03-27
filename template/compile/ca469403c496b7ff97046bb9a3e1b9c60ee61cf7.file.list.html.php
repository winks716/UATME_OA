<?php /* Smarty version Smarty-3.0.7, created on 2013-03-27 21:36:19
         compiled from "E:\UATME_OA/template/modules\document/list.html" */ ?>
<?php /*%%SmartyHeaderCode:178955152f5d3ddade7-76604785%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ca469403c496b7ff97046bb9a3e1b9c60ee61cf7' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\document/list.html',
      1 => 1364219348,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '178955152f5d3ddade7-76604785',
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
			<tr>
				<th>文档名称</th>
			</tr>
		</thead>
		<tbody>
			<?php  $_smarty_tpl->tpl_vars['doc'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('document')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['doc']->key => $_smarty_tpl->tpl_vars['doc']->value){
?>
			<tr class="documentItem">
				<td><a href="<?php echo $_smarty_tpl->tpl_vars['doc']->value['path'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['doc']->value['name'];?>
</a></td>
			</tr>
			<?php }} ?>
		</tbody>
	</table>
</div>

<?php $_template = new Smarty_Internal_Template("declaration.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>