<?php /* Smarty version Smarty-3.0.7, created on 2013-01-31 23:26:11
         compiled from "E:\UATME_OA/template/modules\document/list.html" */ ?>
<?php /*%%SmartyHeaderCode:14079510a8d1396a817-51163548%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ca469403c496b7ff97046bb9a3e1b9c60ee61cf7' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\document/list.html',
      1 => 1356277255,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14079510a8d1396a817-51163548',
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
	<?php if (($_smarty_tpl->getVariable('session')->value['if_document_admin']==1&&$_smarty_tpl->getVariable('request_action')->value!='doc.tech_handbook')||($_smarty_tpl->getVariable('request_action')->value=='doc.tech_handbook'&&$_smarty_tpl->getVariable('session')->value['if_tech_admin']==1)){?>
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
									
									<?php if (($_smarty_tpl->getVariable('session')->value['if_document_admin']==1&&$_smarty_tpl->tpl_vars['t']->value['name']!='技术文档')||($_smarty_tpl->tpl_vars['t']->value['name']=='技术文档'&&$_smarty_tpl->getVariable('session')->value['if_tech_admin']==1)){?>
									<option value="<?php echo $_smarty_tpl->tpl_vars['t']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['t']->value['name']==$_smarty_tpl->getVariable('list_type')->value){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['t']->value['name'];?>
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
	<?php }?>
	
	<table>
		<thead>
			<tr>
				<th>文档名称</th>
				<?php if (($_smarty_tpl->getVariable('session')->value['if_document_admin']==1&&$_smarty_tpl->getVariable('request_action')->value!='doc.tech_handbook')||($_smarty_tpl->getVariable('request_action')->value=='doc.tech_handbook'&&$_smarty_tpl->getVariable('session')->value['if_tech_admin']==1)){?>
				<th></th>
				<?php }?>
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
				<?php if (($_smarty_tpl->getVariable('session')->value['if_document_admin']==1&&$_smarty_tpl->getVariable('request_action')->value!='doc.tech_handbook')||($_smarty_tpl->getVariable('request_action')->value=='doc.tech_handbook'&&$_smarty_tpl->getVariable('session')->value['if_tech_admin']==1)){?>
				<td><span i="<?php echo $_smarty_tpl->tpl_vars['doc']->value['id'];?>
" class="clickbtn deleteDocumentBtn"> [删除] </span></td>
				<?php }?>
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


<?php if (($_smarty_tpl->getVariable('session')->value['if_document_admin']==1&&$_smarty_tpl->getVariable('request_action')->value!='doc.tech_handbook')||($_smarty_tpl->getVariable('request_action')->value=='doc.tech_handbook'&&$_smarty_tpl->getVariable('session')->value['if_tech_admin']==1)){?>
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
						alert(data.error);
					}else{
						alert(data.msg);
						window.location.reload();
					}
				}
			},
			error: function (data, status, e){
				alert(e);
			}
		})
	}
	return false;
}

function initAlertDiv(id){
	$('#alertDiv').dialog({
		autoOpen: false,
		modal: true,
		buttons:{
			'确定':function(){
				$('#alertDiv').dialog({
					buttons: {}
				}).html('数据操作中，请不要关闭此窗口！');
				$.post('index.php?m=document&s=file&a=delete',{'id':id},function(data){
					//$('#document_name').parents('div.ui-tabs-panel').load('<?php echo $_smarty_tpl->getVariable('self_location')->value;?>
');
					window.location.reload();
					$('#alertDiv').dialog("close");
				})
			},
			'取消':function(){
				$('#alertDiv').dialog("close");
			}
		}
	}).html('确认删除此文档？');
}

$(function(){
	$('.deleteDocumentBtn').click(function(){
		initAlertDiv($(this).attr('i'));
		$('#alertDiv').dialog("open");
	})
})
</script>
<?php }?>