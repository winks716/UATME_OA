<?php /* Smarty version Smarty-3.0.7, created on 2013-04-01 22:16:46
         compiled from "E:\UATME_OA/template/modules\base/self.edit.html" */ ?>
<?php /*%%SmartyHeaderCode:18564515996ce806585-18517515%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '24b606855ad4f3a14db6e93e8e9edafd242d10ef' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\base/self.edit.html',
      1 => 1363015203,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18564515996ce806585-18517515',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template("header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("topnav.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>

<div style="margin: 20px auto; width:660px;">

<table>
<tr>
<td>
	<p><strong>密码修改</strong></p>
	<table style="width:260px;">
		<tr>
			<th>原密码</th><td><input id="oldPassword" type="password"/></td>
		</tr>
		<tr>
			<th>新密码</th><td><input id="newPassword" type="password"/></td>
		</tr>
		<tr>
			<th>重复新密码</th><td><input id="confirmNewPassword" type="password"/></td>
		</tr>
	</table>
	<span id="confirmBtn" class="clickbtn"> [确认修改] </span>
</td>

<td style="border-left:solid 2px #CCCCCC;">
	<table style="width:400px;">
	<tr><th colspan="2">个人信息</th></tr>
	<tr><th>中文名</th><td><?php echo $_smarty_tpl->getVariable('employee')->value['namezh'];?>
</td></tr>
	<tr><th>英文名</th><td><?php echo $_smarty_tpl->getVariable('employee')->value['name'];?>
</td></tr>
	<tr><th>电子邮箱</th><td><?php echo $_smarty_tpl->getVariable('employee')->value['email'];?>
</td></tr>
	<tr><th>所属部门</th><td><?php echo $_smarty_tpl->getVariable('employee')->value['department_name'];?>
</td></tr>
	<tr><th>年假信息</th><td>截止至今日，总共:<?php echo $_smarty_tpl->getVariable('employee')->value['total_annualleave'];?>
天； 已用:<?php echo $_smarty_tpl->getVariable('employee')->value['used_annualleave'];?>
天； 剩余:<?php echo $_smarty_tpl->getVariable('employee')->value['total_annualleave']-$_smarty_tpl->getVariable('employee')->value['used_annualleave'];?>
天；</td></tr>
	</table>
</td>
</tr>
</table>

</div>



<?php $_template = new Smarty_Internal_Template("declaration.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>

<script type="text/javascript">
$(function(){
	$('#confirmNewPassword').keyup(function(){
		if($(this).val()==$('#newPassword').val()){
			$(this).css('background-color','green');
		}else{
			$(this).css('background-color','red');
		}
	})
	
	$('#confirmBtn').click(function(){
		var oldPassword = $('#oldPassword').val();
		var newPassword = $('#newPassword').val();
		var confirmNewPassword = $('#confirmNewPassword').val();
		if(oldPassword==''){
			alert('原密码必须填写！');
			$('#oldPassword').get(0).focus();
		}else if(newPassword==''){
			alert('新密码必须填写！');
			$('#newPassword').get(0).focus();
		}else if(confirmNewPassword==''){
			alert('重复新密码必须填写！');
			$('#confirmNewPassword').get(0).focus();
		}else if(confirmNewPassword!=newPassword){
			alert('两次输入的新密码必须一致！');
			$('#confirmNewPassword').get(0).focus();
		}else{
			$.post('index.php?m=base&s=self&a=save',{'oldPassword':oldPassword,'newPassword':newPassword,'confirmNewPassword':confirmNewPassword},function(data){
				var json = eval("(" + data + ")");
				if(json.httpstatus == 200){
					alert('密码修改成功');
					$('#oldPassword, #newPassword, #confirmNewPassword').val('');
				}else if(json.httpstatus == 500){
					alert('您的原密码不正确!');
					$('#oldPassword').get(0).focus();
				}
			})
		}
	})
})
</script>