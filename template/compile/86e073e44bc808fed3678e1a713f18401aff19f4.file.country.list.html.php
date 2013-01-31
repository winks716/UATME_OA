<?php /* Smarty version Smarty-3.0.7, created on 2013-02-01 00:38:24
         compiled from "E:\UATME_OA/template/modules\system/country.list.html" */ ?>
<?php /*%%SmartyHeaderCode:8204510a9e003548a9-26302046%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '86e073e44bc808fed3678e1a713f18401aff19f4' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\system/country.list.html',
      1 => 1359650298,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8204510a9e003548a9-26302046',
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
			<td><input id="countryName" class="span-2"/>+新增</td>
			<td><input id="countryNameshort" class="span-3"/></td>
			<td><input id="countryAvailable" type="checkbox" value="1" checked/></td>
			<td><span class="clickbtn span-2" id="addCountry"> [添加] </span></td>
		</tr>
		<?php  $_smarty_tpl->tpl_vars['c'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('country')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['c']->key => $_smarty_tpl->tpl_vars['c']->value){
?>
		<tr>
			<td><input value="<?php echo $_smarty_tpl->tpl_vars['c']->value['name'];?>
" class="span-2 countryName"/></td>
			<td><input value="<?php echo $_smarty_tpl->tpl_vars['c']->value['nameshort'];?>
" class="span-3 countryNameshort"/></td>
			<td><input value="1" class="span-1 countryAvailable" type="checkbox" <?php if ($_smarty_tpl->tpl_vars['c']->value['available']==1){?>checked<?php }?>/></td>
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