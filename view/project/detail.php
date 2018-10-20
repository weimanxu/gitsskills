<?php
	App::$view->title = '財酷ICO - ' . $projectInfo['name'];
	App::$view->registerCss('/public/css/project.css');
	
?>
<div class="location-wrap clearfix">
	<div class="right">
    	<ul class="clearfix">
    		<li><a href="/">主頁</a></li>
    		<li><a href="/project/list">項目</a></li>
    		<li class="active"><strong><?= $projectInfo['name'] ?></strong></li>
    	</ul>
	</div>
	<div class="left">
		<a href="javascript:;" class="active">項目詳情</a>
		<a href="/project/invest?id=<?= $projectInfo['id'] ?>">項目投資</a>
	</div>
</div>
<div class="main-wrap">
	<div class="project-detail-wrap <?= $projectInfo['id']<=11?'old-project-detail':'' ?>">
		<?= $projectInfo['detail'] ?>
	</div>
	<div class="project-btn-wrap">
		<a href="/project/invest?id=<?= $projectInfo['id'] ?>" id="investBtn" type="button" class="active">我要投資</a>
	</div>
</div>