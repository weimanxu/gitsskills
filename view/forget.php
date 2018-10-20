<?php
	App::$view->title = '财酷ICO - 重设密码';
	
	App::$view->registerJs('/public/js/main/forget.js');
?>
<div class="forget-wrap">
    <h1>重設密碼</h1>
    <h4>輸入你的郵箱來重設密碼.</h4>
    <form>
    	<div class="item">
    		<div class="value">
    			<input type="text" name="email" id="email" placeholder="Email" class="inp inp-block" />
    		</div>
    	</div>
    	<div class="item-error" id="errorTip"></div>
    	<div class="item mt30">
    		<a href="javascript:;" id="forgetBtn" class="btn btn-major btn-block">發送郵件</a>
    	</div>
    	<a href="/login" class="btn btn-hollow btn-block">登錄</a>
    </form>
</div>
<div class="forget-send-wrap">
	<h1>發送成功</h1>
    <h4>一封確認郵件已經發往您的郵箱，請點擊內含的鏈接完成重設密碼.</h4>
</div>
<div class="cpy">©2017 CAIKUICO.com. All Rights Reserved</div>
