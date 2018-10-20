<?php
	App::$view->title = '財酷ICO - 註冊';
	
	App::$view->registerJs('/public/js/main/register.js');
?>
<div class="reg-wrap">
    <h1>註冊</h1>
    <h4>請輸入您的郵箱地址來創建一個財酷ICO賬戶.</h4>
    <form>
    	<div class="item">
    		<div class="key">登錄郵箱</div>
    		<div class="value">
    			<input type="text" name="email" id="email" placeholder="Email" class="inp inp-block" maxlength="32" />
    		</div>
    	</div>
    	<div class="item">
    		<div class="key">登錄密碼</div>
    		<div class="value">
    			<input type="password" name="password" id="password" placeholder="6-32位字符" class="inp inp-block" maxlength="32" />
    		</div>
    	</div>
    	<div class="item">
    		<div class="key">確認密碼</div>
    		<div class="value">
    			<input type="password" name="repassword" id="repassword" placeholder="請重新輸入密碼" class="inp inp-block" maxlength="32" />
    		</div>
    	</div>
    	<div class="item-error" id="errorTip"></div>
    	<div class="item mt30">
    		<a href="javascript:;" id="registerBtn" class="btn btn-major btn-block">註冊</a>
    	</div>
    	<div class="item-p">
    		<p>已有賬戶？</p>
    	</div>
    	<a href="/login" class="btn btn-hollow btn-block">登錄</a>
    </form>
</div>
<div class="reg-success-wrap">
	<h1>註冊成功</h1>
    <h4>一封確認郵件已經發往您的郵箱，請點擊內含的鏈接完成註冊.</h4>
    <a href="/login" class="btn btn-hollow btn-block mt30">登錄</a>
</div>
<div class="cpy">©2017 CAIKUICO.com. All Rights Reserved</div>
