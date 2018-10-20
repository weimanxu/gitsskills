$(function(){
	$('input, textarea').bind('focus', function(){
		$('#errorTip').hide();
		$(this).removeClass('show-error');
	});
	
	$('#resetBtn').bind('click', function(){
		if($(this).hasClass('disable')){
			return ;
		}
		
		var email = tool.getParam('email'),
			tk = tool.getParam('tk'),
			exp = tool.getParam('exp'),
			rand = tool.getParam('rand'),
			password = $.trim($('#password').val()),
			repassword = $.trim($('#repassword').val());
		
		if(password == ''){
			showError('password', '登錄密码不能为空');
			return ;
		}
		if(password != repassword){
			showError('repassword', '兩次輸入的密碼不一致');
			return ;
		}
		var index = layer.load(1, {
			shade: [0.1,'#fff']
		});
		
		$.post('/index/takeReset', {
			email: email,
			tk: tk,
			exp: exp,
			rand: rand,
			password: password
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
			//修改成功
			layer.msg('重設密碼成功，頁面將跳轉至登錄頁', {
				time: 2500
			}, function(){
				window.location.href = '/login';
			});
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