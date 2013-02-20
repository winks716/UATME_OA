<?php /* Smarty version Smarty-3.0.7, created on 2013-02-20 08:37:25
         compiled from "E:\UATME_OA/template/modules\base/login.html" */ ?>
<?php /*%%SmartyHeaderCode:1362251241ac5054d80-90422304%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e612dae6c23b66186b1181d4df6934c6074c227d' => 
    array (
      0 => 'E:\\UATME_OA/template/modules\\base/login.html',
      1 => 1361320639,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1362251241ac5054d80-90422304',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('#submit').click(function(){
			var email = $('#email').val();
			var password = $('#password').val();
			$.post('index.php?m=base&s=verify', {'email':email, 'password':password}, function(data){
				var json = eval("(" + data + ")");
				if(json.httpstatus == 200){
					self.location = 'index.php';
				}else if(json.httpstatus == 503){
					self.location = '503.html';
				}
			})
		})
	})
</script>
<title>汉盛科技OA</title>
<style>
.header{
	background: url('images/base/login/header.bg.gif');
	display: block;
	height: 72px;
	width: 100%;
	margin: 0 auto;
	padding-left: 0px;
	}
.header img{
	margin-left:50px;
}
.body{
	height: 450px;
	}
.main{
	width: 100%;
	height: 400px;
	margin: 25px auto;
	background: url('images/base/login/theme/taohua.jpg') no-repeat;
	border-radius: 0 50px;
	}
.login{
	float: right;
	height: 200px;
	width: 370px;
	margin: 0px 30px;
	border: solid 1px #CCCCCC;
	background-color: rgba(180,222,241,0.4);/*#b4def1*/;
	-webkit-box-shadow:5px 2px 10px #000;
	-moz-box-shadow:5px 2px 10px #000;
	box-shadow:5px 2px 10px #000;
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
	}
.email{
	margin: 30px 20px 0 20px;
	font: bold 16px 黑体;
	}
.emailAppendix{
	font: italic bold 16px arial;
	color: #666666;
	}
.password{
	margin: 20px 20px 0 20px;
	font: bold 16px 黑体;
	}
.submit{
	margin: 20px 20px 0 64px;
	}
.footer{
	width: 100%;
	margin: 0 auto;
	height: 50px;
	border-top: solid 1px #CCCCCC;
	font: 12px arial;
	color: #CCCCCC;
	text-align: center;
	}
#email{
	width: 100px;
	font: 18px arial;
	border: solid 1px #AAAAAA;
	padding: 5px;
	}
#password{
	width: 150px;
	font: 18px arial;
	border: solid 1px #AAAAAA;
	padding: 5px;
	}
#submit{
	background: url('images/base/login/login_btn.png') -200px -70px;
	width: 190px;
	height: 20px;
	display: inline-block;
	cursor: pointer;
	padding: 8px 0px;
	text-align: center;
	color: #FFFFFF;
	}
#submit:hover{
	background-position: -200px -112px;
	}
#submit:active{
	background-position: -200px -154px;
	}
.clickbtn{
	cursor:pointer;
	font:12px arial;
	color:blue;
	margin-left:20px;
}
</style>
</head>

<body>
<div class="header">
	<div>
		<div style="float:left;"><img border="0" src="images/global/logo.highsource.gif"/></div>
		<div style="font:bold 24px times; padding-top:18px; color:#999999;"></div>
		<div style="float:right; font:12px times; padding-right: 20px; color:#999999; padding-bottom: 20px;">版本 1.0.1</div>
	</div>
</div>
<div class="body">
	<div class="main">
		<div class="login">
			<div class="email">电邮 <input id="email"/> <span class="emailAppendix">@highsource.com</span></div>
			<div class="password">密码 <input id="password" type="password"/></div>
			<div class="submit">
				<a id="submit" title="登录">登&nbsp;&nbsp;录</a><!--<span class="clickbtn"> [忘记密码] </span>-->
			</div>
		</div>
</div>
<div class="footer">
	Powered by U@ME Studio
</div>
</body>
</html>
