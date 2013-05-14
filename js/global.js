/* #alertDiv data handling function set 
 * Need jQuery support
 * Start
 * 
 * Example
   $('#xxxxx').click(function(){
       var aaa = $('#aaa').val();
       var bbb = $('#bbb').val();
       var ccc = $('#ccc').val();
       alertDivPostData({
           	initMsg: '递交数据中，请稍候……',                                          //modal提示框中的初始文字
           	postConfirm: '确认要xxx么？',                                          //递交前的提示确认信息
			checkBeforePost: '"'+aaa+'"!="" && "'+bbb+'"!="" && "'+ccc+'"!=""',   //递交前数据检查通过的逻辑表达式, 注意, 不能接受带控制符的字符串如textarea, 只能单行文本或数字
			tipBeforePost: '请将数据填写完整后再次递交',                            //递交前数据检查不通过的警告提示
			postUrl: 'index.php?m=xxx&s=xxx&a=xxx',                               //递交数据的目标url
			postData: {'aaa':aaa,'bbb':bbb,'ccc':ccc},                            //递交的数据
			tipAfterPost: '正在刷新页面，请稍候……',                                //递交后modal提示框中的文字
			redirectUrl: 'index.php?m=xxxx&s=xxxx&a=xxxx'                         //递交后页面转向的url
       })
   }) 
   */
function alertDivInit(){
	$('<div id="alertDiv">数据交互中，请稍候……</div>').appendTo('body').hide().dialog({
		'modal':true,
		'autoOpen':false
	}).ajaxStart(function(){
		$(this).dialog('open');
	});
}
function alertDivPostData(postOptions){
	var defaultOptions = {
			initMsg: '数据交互中，请稍候……',          //modal提示框中的初始文字
			postConfirm: '',                             //递交前的提示确认信息
			checkBeforePost: '',                         //递交前数据检查通过的逻辑表达式, 注意, 不能接受带控制符的字符串如textarea, 只能单行文本或数字
			tipBeforePost: '请将数据填写完整后再次递交',   //递交前数据检查不通过的警告提示
			postUrl: '#',                                //递交数据的目标url
			postData: {},                                //递交的数据
			tipAfterPost: '正在刷新页面，请稍候……',       //递交后modal提示框中的文字
			redirectUrl: ''                              //递交后页面转向的url
	};
	var o = $.extend(defaultOptions, postOptions);
	//if need confirm before post
	if(o.postConfirm!=''){
		if(confirm(o.postConfirm)){
			//if need data precheck before post
			if(o.checkBeforePost!=''){
				//if passed the data precheck
				if(alertDivCheckBeforePost(o.checkBeforePost)){
					$('#alertDiv').html(o.initMsg);
					$.post(o.postUrl,o.postData,function(data){
						alertDivHandleResult(data, o.redirectUrl, o.tipAfterPost);
					});
				}else{
					alert(o.tipBeforePost);
				}
			}else{
				$('#alertDiv').html(o.initMsg);
				$.post(o.postUrl,o.postData,function(data){
					alertDivHandleResult(data, o.redirectUrl, o.tipAfterPost);
				});
			}
		}
	}else{
		if(o.checkBeforePost!=''){
			if(alertDivCheckBeforePost(o.checkBeforePost)){
				$('#alertDiv').html(o.initMsg);
				$.post(o.postUrl,o.postData,function(data){
					alertDivHandleResult(data, o.redirectUrl, o.tipAfterPost);
				});
			}else{
				alert(o.tipBeforePost);
			}
		}else{
			$('#alertDiv').html(o.initMsg);
			$.post(o.postUrl,o.postData,function(data){
				alertDivHandleResult(data, o.redirectUrl, o.tipAfterPost);
			});
		}
	}
}
function alertDivCheckBeforePost(data){
	var result = eval('(' + data + ')');
	if(result){
		return true;
	}else{
		return false;
	}
}
function alertDivHandleResult(data, redirectUrl, tipAfterPost){
	var json = eval('(' + data + ')');
	if(json.httpstatus = 200){
		if(json.msg!=''){
			alert(json.msg);
		}
	}else{
		if(json.error!=''){
			alert(json.error);
		}
	}
	$('#alertDiv').html(tipAfterPost);
	if(redirectUrl!=''){
		window.location = redirectUrl;
	}else{
		window.location.reload();
	}
}
/* #alertDiv data handling function set End */