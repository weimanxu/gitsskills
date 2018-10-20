<?php
	App::$view->title = '財酷ICO - ' . $projectInfo['name'];
	App::$view->registerCss('/public/css/project.css');
	App::$view->registerJs('/public/js/lib/bignumber.min.js');
	App::$view->registerJs('/public/js/main/project/invest.js');
?>
<div class="location-wrap clearfix">
	<div class="right">
    	<ul class="clearfix">
    		<li><a href="/">主頁</a></li>
    		<li><a href="/project/list">項目</a></li>
    		<li><strong><?= $projectInfo['name'] ?></strong></li>
    		<li class="active"><strong>投資</strong></li>
    	</ul>
	</div>
	<div class="left">
		<a href="/project/detail?id=<?= $projectInfo['id'] ?>">項目詳情</a>
		<a href="javascript:;" class="active">項目投資</a>
	</div>
</div>
<div class="main-wrap invest-page">
	<div class="project-head box">
    	<h2><?= $projectInfo['name'] ?></h2>
    	<p><?= $projectInfo['intro'] ?></p>
    </div>
    <div class="project-main-wrap box mt30">
		<div class="project-target-wrap">
			<?php if($projectInfo['btc_target'] > 0){?>
			<div class="project-target-item">
				<div class="project-total">
					<div>
						<img src="/public/images/total_icon.png">
					</div>
					<div>
						<h2 class="cff8040"><?= Format::formatNumber($projectInfo['btc_done'], 8, false, true, ',')?> BTC</h2>
						<p>總共籌集</p>
					</div>
				</div>
				<div class="project-target">
					<div>
						<img src="/public/images/target_icon.png">
					</div>
					<div>
						<h2><?= Format::formatNumber($projectInfo['btc_target'], 8, false, true, ',')?> BTC</h2>
						<p>目標金額</p>
					</div>
				</div>
				<div class="project-investor">
					<div>
						<img src="/public/images/investor_icon.png">
					</div>
					<div>
						<h2><?= $projectInfo['btc_total'] ?></h2>
						<p>總投資者</p>
					</div>
				</div>
			</div>
			<?php }?>
			<?php if($projectInfo['eth_target'] > 0){?>
			<div class="project-target-item">
				<div class="project-total">
					<div>
						<img src="/public/images/total_icon.png">
					</div>
					<div>
						<h2 class="c535878"><?= Format::formatNumber($projectInfo['eth_done'], 8, false, true, ',')?> ETH</h2>
						<p>總共籌集</p>
					</div>
				</div>
				<div class="project-target">
					<div>
						<img src="/public/images/target_icon.png">
					</div>
					<div>
						<h2><?= Format::formatNumber($projectInfo['eth_target'], 8, false, true, ',')?> ETH</h2>
						<p>目標金額</p>
					</div>
				</div>
				<div class="project-investor">
					<div>
						<img src="/public/images/investor_icon.png">
					</div>
					<div>
						<h2><?= $projectInfo['eth_total'] ?></h2>
						<p>總投資者</p>
					</div>
				</div>
			</div>
			<?php }?>
		</div>
        <div class="project-progress-wrap">
        	<?php if($projectInfo['btc_target'] > 0){?>
        	<dl>
        		<dt>BTC</dt>
        		<dd>
        			<div class="progress">
        				<div style="width: <?= $projectInfo['btc_done']/$projectInfo['btc_target'] * 100?>%;" class="progress-bar btc-bar"></div>
        			</div>
        			<div><small>BTC籌集進度 <strong><?= Format::formatNumber($projectInfo['btc_done'] / $projectInfo['btc_target']* 100, 1) ?>%</strong></small></div>
        		</dd>
        	</dl>
    		<?php }?>
    		<?php if($projectInfo['eth_target'] > 0){?>
        	<dl>
        		<dt>ETH</dt>
        		<dd>
        			<div class="progress">
        				<div style="width: <?= $projectInfo['eth_done']/$projectInfo['eth_target'] * 100?>%;" class="progress-bar eth-bar"></div>
        			</div>
        			<div><small>ETH籌集進度 <strong><?= Format::formatNumber($projectInfo['eth_done']/$projectInfo['eth_target'] * 100, 1) ?>%</strong></small></div>
        		</dd>
        	</dl>
    		<?php }?>
    		<div class="date">
    			<span class="key">開始日期</span>
    			<span class="val"><?= date('Y-m-d H:i:s', $projectInfo['begintime'])?></span>
    			<span class="key">結束日期</span>
    			<span class="val"><?= date('Y-m-d H:i:s', $projectInfo['endtime'])?></span>
    		</div>
        </div>
        <div class="project-btn-wrap">
        	<button id="investBtn" type="button" data-login="<?= $userInfo==''?'0':'1' ?>" class="<?= $projectInfo['projectInvestState'] == 1 ? 'active' : 'disable'?>"><?= ['', '我要投資', '未開始', '已過期', '已完成', '已暫停'][$projectInfo['projectInvestState']]?></button>
        </div>
    </div>
    <!-- 是否登录 -->
    <?php if(empty($userInfo)){?>
    <div class="project-login-wrap">
        請登錄來投資這個項目。 <a href="/login">登錄</a>
    </div>
    <?php }else {?>
    <div class="project-invest-wrap box">
    	<h3>我的投資</h3>
		<table class="trans">
			<thead>
				<tr>
					<th>時間</th>
					<th>投資金額</th>
					<th>代幣數量</th>
				</tr>
			</thead>
			<tbody>
				<?php if(empty($investRecord)){?>
					<tr><td colspan="3" class="nodata">暫無投資記錄</td></tr>
				<?php }else{?>
    				<?php foreach ($investRecord as $row):?>
    				<tr>
    					<td><?= date('Y-m-d H:i:s', $row['createtime'])?></td>
    					<td><?= Format::formatNumber($row['amount'], 8, false, true, ',') . ' ' . strtoupper($row['type']);?></td>
    					<td><?= Format::formatNumber($row['token'], 8, false, true, ',');?></td>
    				</tr>
    				<?php endforeach;?>
				<?php }?>
			</tbody>
		</table>
    </div>
	<?php }?>
</div>
<div class="modal fade" id="investModal" tabindex="-1" role="dialog" aria-labelledby="investModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">╳</span></button>
			<h4 class="modal-title" id="investModalLabel">項目投資</h4>
		</div>
		<div class="modal-body">
			<form id="investForm">
				<div class="form-group">
					<select class="form-control invest-type">
						<?php if($projectInfo['btc_target'] > 0){?>
						<option data-target="<?= $projectInfo['btc_target']?>" data-done="<?=$projectInfo['btc_done'] ?>" data-balance="<?=$userInfo['btc_usable'] ?>">BTC</option>
						<?php }?>
    					<?php if($projectInfo['eth_target'] > 0){?>
						<option data-target="<?= $projectInfo['eth_target']?>" data-done="<?=$projectInfo['eth_done'] ?>"data-balance="<?=$userInfo['eth_usable'] ?>">ETH</option>
						<?php }?>
					</select>
				</div>
				<div class="form-group">
					<input type="text" class="form-control" name="invest_num" placeholder="請輸入投資金額，目前最多可投">
				</div>
				<div class="form-group">
					<input type="password" name="fund_password" style="display:none" disabled>
					<input type="password" class="form-control" name="fund_password" id="fundPassword" placeholder="請輸入資金密碼">
				</div>
				<button type="button" class="btn btn-invest">確認投資</button>
			</form>
		</div>
		</div>
	</div>
</div>