<?php
	App::$view->title = '财酷ICO - 我的钱包';
	
	App::$view->registerCss('/public/css/my.css');
	
	App::$view->registerJs('/public/js/main/my/wallet.js');
?>
<div class="location-wrap clearfix">
	<div class="left">
    	<h2>我的钱包</h2>
    	<ul class="clearfix">
    		<li><a href="/">主页</a></li>
    		<li class="active"><strong>钱包</strong></li>
    	</ul>
	</div>
</div>
<div class="main-wrap">
	<div class="box">
		<div class="title">钱包</div>
		<div class="content">
			<ul class="wallet-tab-head clearfix">
				<li class="active"><a href="javascript:;">比特币</a></li>
				<li><a href="javascript:;">以太币</a></li>
			</ul>
			<div class="wallet-tab-content">
				<table id="btcWallet" class="wallet-table active" >
					<thead>
						<tr>
							<th style="width: 130px;"></th>
							<th style="width:350px;">项目名</th>
							<th>地址</th>
							<th style="width: 150px;">投资数额</th>
							<th>代币数额</th>
						</tr>
					</thead>
					<tbody>
					   <?php foreach ($listBtcWalletProject as $k=>$v):?>
						<tr>
							<td>
								<a href="/project/invest?id=<?=$v['project_id'] ?>">
									<img src="<?= $v['logo'] ?>" />
								</a>
							</td>
							<td>
								<a href="/project/invest?id=<?=$v['project_id'] ?>" class="project-name"><?= $v['name'] ?></a>
							</td>
							<td>
								<a target="_blank" href="https://blockchain.info/address/<?= $v['address'] ?>"><?= $v['address'] ?></a>
							</td>
							<td><?= $v['amount'] ?> BTC</td>
							<td><?= $v['token'] ?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
				<table id="ethWallet" class="wallet-table">
					<thead>
						<tr>
							<th style="width: 130px;"></th>
							<th style="width:350px;">项目名</th>
							<th>地址</th>
							<th style="width: 150px;">投资金额</th>
							<th>代币数额</th>
						</tr>
					</thead>
					<tbody>
					   <?php foreach ($listEthWalletProject as $k=>$v):?>
						<tr>
							<td>
								<a href="/project/invest?id=<?=$v['project_id'] ?>">
									<img src="<?= $v['logo'] ?>" />
								</a>
							</td>
							<td>
								<a href="/project/invest?id=<?=$v['project_id'] ?>" class="project-name"><?= $v['name'] ?></a>
							</td>
							<td>
								<a target="_blank" href="https://etherchain.org/account/<?= $v['address'] ?>"><?= $v['address'] ?></a>
							</td>
							<td><?= $v['amount'] ?> ETH</td>
							<td><?= $v['token'] ?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>