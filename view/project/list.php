<?php
	App::$view->title = '財酷ICO - 項目';
	
	App::$view->registerCss('/public/css/project.css');
	
	App::$view->registerJs('/public/js/main/project/list.js');
?>
<!--<div class="location-wrap clearfix">-->
<!--	<div class="right">-->
<!--    	<ul class="clearfix">-->
<!--    		<li><a href="/">主页</a></li>-->
<!--    		<li class="active"><strong>項目</strong></li>-->
<!--    	</ul>-->
<!--	</div>-->
<!--</div>-->
<div class="main-wrap">
	<div class="box">
		<div class="title">項目列表</div>
		<div class="content">
			<ul class="project-tab-head clearfix">
				<li class="active"><a href="javascript:;">進行中</a></li>
				<li><a href="javascript:;">即將到來</a></li>
				<li><a href="javascript:;">已完成</a></li>
			</ul>
			<div class="project-tab-content">
				<!-- 進行中 -->
				<ul class="tab-item active">
					<?php if(empty($goingList)) {?>
					<li class="nodata">暫無項目</li>
					<?php }else {?>
						<?php foreach ($goingList as $item):?>
						<li class="clearfix">
							<div class="pic-w">
								<a href="/project/detail?id=<?= $item['id'] ?>"><img src="<?= $item['logo'] ?>" /></a>
							</div>
							<div class="info">
								<h3><a href="/project/detail?id=<?= $item['id'] ?>"><?= $item['name'] ?></a></h3>
								<p class="intro"><?= $item['intro'] ?></p>
								<p class="begindate"><?= date('d/m/Y', $item['begintime']) ?></p>
							</div>
							<a href="/project/invest?id=<?= $item['id'] ?>" class="invest-btn">
								<i class="fa fa-money"></i>
								投資
							</a>
						</li>
						<?php endforeach;?>
					<?php }?>
				</ul>
				<!-- 即將到來-->
				<ul class="tab-item">
					<?php if(empty($waitList)) {?>
					<li class="nodata">暫無項目</li>
					<?php }else {?>
						<?php foreach ($waitList as $item):?>
						<li class="clearfix">
							<div class="pic-w">
								<a href="/project/detail?id=<?= $item['id'] ?>"><img src="<?= $item['logo'] ?>" /></a>
							</div>
							<div class="info">
								<h3><a href="/project/detail?id=<?= $item['id'] ?>"><?= $item['name'] ?></a></h3>
								<p class="intro"><?= $item['intro'] ?></p>
								<p class="begindate"><?= date('d/m/Y', $item['begintime']) ?></p>
							</div>
						</li>
						<?php endforeach;?>
					<?php }?>
				</ul>
				<!-- 已完成 -->
				<ul class="tab-item">
					<?php if(empty($doneList)) {?>
					<li class="nodata">暫無項目</li>
					<?php }else {?>
						<?php foreach ($doneList as $item):?>
						<li class="clearfix">
							<div class="pic-w">
								<a href="/project/detail?id=<?= $item['id'] ?>"><img src="<?= $item['logo'] ?>" /></a>
							</div>
							<div class="info">
								<h3><a href="/project/detail?id=<?= $item['id'] ?>"><?= $item['name'] ?></a></h3>
								<p class="intro"><?= $item['intro'] ?></p>
								<p class="begindate"><?= date('d/m/Y', $item['begintime']) ?></p>
							</div>
						</li>
						<?php endforeach;?>
					<?php }?>
				</ul>
			</div>
		</div>
	</div>
</div>