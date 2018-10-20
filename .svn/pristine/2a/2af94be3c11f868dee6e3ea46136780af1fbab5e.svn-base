<?php
	App::$view->title = '財酷ICO - 重設密碼';
	
	App::$view->registerJs('/public/js/main/resetpwd.js');
?>
<div class="resetpwd-wrap">
    <h1>重設密碼</h1>
    <h4>最後一步，請輸入你的新登錄密碼.</h4>
    <form>
    	<div class="item item-email"><?php echo $userInfo['email'];?></div>
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
    		<a href="javascript:;" id="resetBtn" class="btn btn-major btn-block">確定</a>
    	</div>
    </form>
</div>
<div class="cpy">©2017 CAIKUICO.com. All Rights Reserved</div>
