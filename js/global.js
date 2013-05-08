/* #alertDiv data handling function set 
 * Need jQuery support
 * Start */
function alertDivInit(){
	$('#alertDiv').hide().dialog({
		'modal':true,
		'autoOpen':false
	}).ajaxStart(function(){
		$(this).dialog('open');
	});
}
function alertDivPostData(postOptions){
	var defaultOptions = {
			postUrl: '#',
			postData: {},
			postConfirm: '',
			redirectUrl: '',
			tipAfterPost: '正在刷新页面，请稍候……',
			checkBeforePost: '',
			tipBeforePost: '请将数据填写完整后再次递交'
	};
	var o = $.extend(defaultOptions, postOptions);
	//if need confirm before post
	if(o.postConfirm!=''){
		if(confirm(o.postConfirm)){
			//if need data precheck before post
			if(o.checkBeforePost!=''){
				//if passed the data precheck
				if(alertDivCheckBeforePost(o.checkBeforePost)){
					$.post(o.postUrl,o.postData,function(data){
						alertDivHandleResult(data, o.redirectUrl, o.tipAfterPost);
					});
				}else{
					alert(o.tipBeforePost);
				}
			}else{
				$.post(o.postUrl,o.postData,function(data){
					alertDivHandleResult(data, o.redirectUrl, o.tipAfterPost);
				});
			}
		}
	}else{
		if(o.checkBeforePost!=''){
			if(alertDivCheckBeforePost(o.checkBeforePost)){
				$.post(o.postUrl,o.postData,function(data){
					alertDivHandleResult(data, o.redirectUrl, o.tipAfterPost);
				});
			}else{
				alert(o.tipBeforePost);
			}
		}else{
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
		alert(json.msg);
	}else{
		alert(json.error);
	}
	$('#alertDiv').html(tipAfterPost);
	if(redirectUrl!=''){
		window.location = redirectUrl;
	}else{
		window.location.reload();
	}
}
/* #alertDiv data handling function set End */