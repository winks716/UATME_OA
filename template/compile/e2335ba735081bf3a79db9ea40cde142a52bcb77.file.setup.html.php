<?php /* Smarty version Smarty-3.0.7, created on 2013-03-28 00:01:33
         compiled from "E:\UATME_OA/template/modules\document/setup.html" */ ?>
<?php /*%%SmartyHeaderCode:3455515317dd05c9a9-26103563%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e2335ba735081bf3a79db9ea40cde142a52bcb77' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\document/setup.html',
      1 => 1364400088,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3455515317dd05c9a9-26103563',
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
	<div class="toolbar clear">
		<div class="right">
			<img id="loading" src="images/global/loading.gif" style="display:none;">
			<form name="form" action="" method="POST" enctype="multipart/form-data">
				<table cellpadding="0" cellspacing="0" class="tableForm">
					<tr>
						<td>
						文档类型<select id="document_type_id">
									<?php  $_smarty_tpl->tpl_vars['t'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('type')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['t']->key => $_smarty_tpl->tpl_vars['t']->value){
?>
									<?php if ($_smarty_tpl->tpl_vars['t']->value['name']==$_smarty_tpl->getVariable('list_type')->value){?>
									<option value="<?php echo $_smarty_tpl->tpl_vars['t']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['t']->value['name'];?>
</option>
									<?php }?>
									<?php }} ?>
								</select>
						文档名称<input id="document_name" size="30"/>
						&nbsp;&nbsp;<input id="fileToUpload" type="file" size="20" name="fileToUpload" class="input">
						<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();">上传</button></td>
					</tr>
				</table>
			</form>    	
		</div>
	</div>
	
	

	<table>
		<thead>
			<tr>
				<th>文档名称</th>

				<th>操作</th>
				<th>排序</th>

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

				<td><span i="<?php echo $_smarty_tpl->tpl_vars['doc']->value['id'];?>
" class="clickbtn deleteDocumentBtn"> [删除] </span></td>
				<td><input class="span-2 orderBy" value="<?php echo $_smarty_tpl->tpl_vars['doc']->value['orderby'];?>
"/><span i="<?php echo $_smarty_tpl->tpl_vars['doc']->value['id'];?>
" class="clickbtn sortDocumentBtn"> [排序] </span></td>

			</tr>
			<?php }} ?>
		</tbody>
	</table>

</div>

<div id="alertDiv"></div>

<?php $_template = new Smarty_Internal_Template("declaration.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>



<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<script type="text/javascript">
function ajaxFileUpload(){
	if($.trim($('#document_name').val())==''){
		alert('文档名称不能为空，请填写');
		$('#document_name').get(0).focus();
	}else{
		$("#loading")
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});

		$.ajaxFileUpload({
			url:'index.php?m=document&s=file&a=upload',
			secureuri:false,
			fileElementId:'fileToUpload',
			dataType: 'json',
			data:{'document_name':$('#document_name').val(), 'document_type_id':$('#document_type_id').val()},
			success: function (data, status){
				if(typeof(data.error) != 'undefined'){
					if(data.error != ''){
						alert('反馈D3： '+data.error);
					}else{
						alert(data.msg);
						window.location.reload();
					}
				}
			},
			error: function (data, status, e){
				alert('反馈D4： '+e);
			}
		})
	}
	return false;
}

$(function(){
	$('#alertDiv').html('数据操作中，请不要关闭此窗口！').dialog({
		autoOpen: false,
		modal: true
	});

	$('.deleteDocumentBtn').click(function(){
		if(confirm('确认删除此文档？')){
			$('#alertDiv').dialog('open');
			$.post('index.php?m=document&s=file&a=delete',{'id':$(this).attr('i')},function(data){
				/*var json = eval("(" + data + ")");
				if(json.httpstatus==200){
					window.location.reload();
				}else{
					alert('反馈D1: 服务器忙，请稍后再试');
				}*/
				window.location.reload();
			})
		}
	})
	$('.sortDocumentBtn').click(function(){
		if(confirm('确认保存排序？')){
			$('#alertDiv').dialog('open');
			$.post('index.php?m=document&s=file&a=sort',{'id':$(this).attr('i'), 'orderby':($(this).parent().find('input.orderBy').val()-0)},function(data){			
				/*var json = eval("(" + data + ")");
				if(json.httpstatus==200){
					window.location.reload();
				}else{
					alert('反馈D2： 服务器忙，请稍后再试');
				}*/
				window.location.reload();
			})		
		}
	})
})
</script>