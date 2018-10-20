$(function(){
	$('input, textarea').bind('focus', function(){
		$('#errorTip').hide();
		$(this).removeClass('show-error');
	});
	
	$('#forgetBtn').bind('click', function(){
		if($(this).hasClass('disable')){
			return ;
		}
		
		var email = $.trim($('#email').val());
		
		if(email == ''){
			showError('email', '郵箱不能為空');
			return ;
		}
		
		var index = layer.load(1, {
			shade: [0.1,'#fff']
		});
		
		$.post('/index/takeFindPwd', {
			email : email
		}, function(data){
			layer.close(index);
			if (!data.success){
				var errorArr = data.error.split('|');
				if(errorArr.length == 2){
					showError(errorArr[0], errorArr[1]);
				}else{
					showError(undefined, data.error);
				}
				return ;
			}
			$('.forget-wrap').hide();
			$('.forget-send-wrap').show();
			
		}, 'json');
		
	});
	
	//显示错误
	function showError(id, error){
		if(id){
			$('#' + id).addClass('show-error');
		}
		$('#errorTip').html(error).show();
	}
});