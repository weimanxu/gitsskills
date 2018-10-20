<?php App::$view->beginContent()?>

<div class="user-main clearfix">
	<div class="user-left-content">
		<div class="user-nav">
			<div class="menu-head">個人賬戶</div>
			<ul class="meun-list">
				<li class="<?= App::$request->getOriginPath() == '/user/home' ? 'current' : null ?>">
					<a href="/user/home">賬戶總覽</a>
				</li>
				<li class="<?= App::$request->getOriginPath() == '/user/identity' ? 'current' : null ?>">
					<a href="/user/identity">實名認證</a>
				</li>
				<li class="<?= App::$request->getOriginPath() == '/user/security' ? 'current' : null ?>">
					<a href="/user/security">安全設置</a>
				</li>
			</ul>
		</div>
		<div class="user-nav">
			<div class="menu-head">項目管理</div>
			<ul class="meun-list">
				<li class="<?= App::$request->getOriginPath() == '/user/support' ? 'current' : null ?>">
					<a href="/user/support">我支持的項目</a>
				</li>
			</ul>
		</div>
		<div class="user-nav">
			<div class="menu-head">資金管理</div>
			<ul class="meun-list">
				<li class="<?= App::$request->getOriginPath() == '/fund/recharge' ? 'current' : null ?>">
					<a href="/fund/recharge">資金充值</a>
				</li>
				<li class="<?= App::$request->getOriginPath() == '/fund/withdrawal' ? 'current' : null ?>">
					<a href="/fund/withdrawal">資金提現</a>
				</li>
				<li class="<?= App::$request->getOriginPath() == '/fund/capital' ? 'current' : null ?>">
					<a href="/fund/capital">資金明細</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="user-right-content">
		<?= $content ?>
	</div>
</div>
<?php 
//注册js|css文件必须在加载父layout之前
App::$view->endContent('main', [
    'userInfo'  => $userInfo,
    'bodyClass' => 'body-user'
]);

?>