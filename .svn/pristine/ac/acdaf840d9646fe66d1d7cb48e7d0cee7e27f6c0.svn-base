<?php
	App::$view->title = '財酷ICO - 我支持的項目';
    App::$view->registerCss('/public/css/support.css');
	App::$view->registerJs('/public/js/main/user/support.js');
?>
<div class="user-support-wrap">
    <div class="user-center-head clearfix">
        <div class="left">
            <img src="/public/images/user-support.png">
        </div>
        <div class="left">
            <h2>我支持的項目</h2>
            <ul class="clearfix">
                <li class="active"><a href="/">主頁</a></li>
                <li><a>項目管理</a></li>
            </ul>
        </div>
    </div>
    <div class="support-list">
        <?php if(empty($user_project)) {?>
            <div class="no-data"><img src="/public/images/null.png"><p>您還沒投資過項目呢，趕緊去投一筆啦~~</p></div>
        <?php }else {?>
            <ul class="project-list">
                <?php foreach ($user_project as $row):?>
                    <li>
                        <div class="project-pan">
                            <div class="project-img">
                                <a href="/project/detail?id=<?= $row['project_id']?>">
                                    <img src="<?= $row['project_logo']?>">
                                </a>
                            </div>
                            <div class="project-intro">
                                <a href="/project/detail?id=<?= $row['project_id']?>">
                                    <?= $row['project_name']?>
                                </a>
                                <div class="phone-fund">
                                    <p class="font-type">投資金額：<?= $row['btc_amount'] == 0 ? '' : Format::formatNumber($row['btc_amount'], 8, false, true, ',').' BTC' ?><?php if( $row['eth_amount']!=0&&$row['btc_amount']!=0){echo '/';}?><?= $row['eth_amount'] == 0 ? '' : Format::formatNumber($row['eth_amount'], 8, false, true, ',').' ETH' ?></p>
                                </div>
                                <div class="phone-fund">
                                    <p class="font-type">代幣總額：<?= Format::formatNumber($row['token'], 8, false, true, ',') ?></p>
                                    <p class="font-type">待提現：<?=  Format::formatNumber($row['remainToken'], 8, false, true, ',') ?></p>
                                </div>
                            </div>
                            <div class="project-invest">
                                <span class="font-c999999">投資金額：</span>
                                <div>
                                    <p class="font-c666666"><?= $row['btc_amount'] == 0 ? '' : Format::formatNumber($row['btc_amount'], 8, false, true, ',').' BTC' ?></p>
                                    <p class="font-c666666"><?= $row['eth_amount'] == 0 ? '' : Format::formatNumber($row['eth_amount'], 8, false, true, ',').' ETH' ?></p>
                                </div>
                            </div>
                            <div class="project-token">
                                <span class="font-c999999">我的代幣：</span>
                                <div>
                                    <p class="font-c666666 balance">總&nbsp;&nbsp;&nbsp;額：<?= Format::formatNumber($row['token'], 8, false, true, ',') ?></p>
                                    <p class="font-c666666">待提現：<?=  Format::formatNumber($row['remainToken'], 8, false, true, ',') ?></p>
                                </div>
                            </div>
                            <div class="btn-group project-detail" role="group">
                                <button class="btn btn-default withdraw-button" data-toggle="modal" data-target="#supportModal" data-address="<?= $row['withdraw_address'] ?>" data-id="<?= $row['id'] ?>" data-balance="<?= $row['remainToken'] ?>">代幣提現</button>
                                <button type="button" class="btn btn-default withdraw-detail-button" data-id="<?= $row['id'] ?>">提現明細</button>
                                <button type="button" class="btn btn-default invest-detail-button" data-id="<?= $row['id'] ?>">投資明細</button>
                            </div>
                        </div>
                        <div class="invest-detail detal<?= $row['id']?>">
                        </div>
                    </li>
                <?php endforeach;?>
            </ul>
        <?php }?>
    </div>
</div>
<div class="modal fade" id="supportModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">╳</span></button>
        <h4 class="modal-title">代幣提現</h4>
      </div>
      <div class="modal-body">
        <form id="withdrawForm"></form>
      </div>
    </div>
  </div>
</div>
