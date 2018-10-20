<?php
	App::$view->title = '财酷ICO - 资金提現';
    App::$view->registerCss('/public/css/funds.css');
    App::$view->registerJs('/public/js/lib/bignumber.min.js');
	App::$view->registerJs('/public/js/main/fund/funds.js');
?>
<div class="user-founds-wrap" data-type="<?= $type?>">
    <div class="user-center-head clearfix">
        <div class="left">
            <img src="/public/images/recharge-<?= $type ?>.png">
        </div>
        <div class="left">
            <h2><?= strtoupper($type) ?>提現</h2>
            <ul class="clearfix">
                <li class="active"><a href="/">主頁</a></li>
                <li><a>資金管理</a></li>
            </ul>
        </div>
    </div>
    <div class="founds-body">
        <ul class="founds-body-nav clearfix">
            <li><a class="<?= $type == 'btc' ? 'active' : ''?>" href="/fund/withdrawal?type=btc">BTC提現</a></li>
            <li><a class="<?= $type == 'eth' ? 'active' : ''?>" href="/fund/withdrawal?type=eth">ETH提現</a></li>
        </ul>
        <div class="notice">
            <?php if($userInfo['fund_password'] == ''){?>
            <p class="set-password">你當前還沒設定資金密碼，暫不能提幣，請設定<a href="/user/security">資金密碼。</a></p>
            <?php }else {?>
            <p>當前每日最高可提現<?= $maxAmount?>個幣。</p>
            <p class="font-red">特别提示：因<?=strtoupper($type)?>交易量大，網絡確認時間長，為了您的交易更快被確認，建議您增加網絡手續費。</p>
            <p class="font-red">為了您的資金安全，在綁定、修改手機、更換驗證管道以及更改資金密碼後24小時內不允許提幣，如有此操作需求請聯系客服。</p>
            <?php }?>
        </div>
        <div class="founds-withdrawal">
            <form id="withdrawalForm">
                <?php if($userInfo['fund_password'] != ''){?>
                <label for="withdraw_address">提現地址：</label>
                    <?php if($addressInfo['address'] != ''){?>
                        <input class="form-control" type="text" value="<?= $addressInfo['address']?>" disabled/>
                        <a data-toggle="modal" data-target="#addressModal" href="javascript:;">修改</a>
                    <?php }else {?>
                        <a class="new-add" data-toggle="modal" data-target="#addressModal" href="javascript:;">添加地址</a>
                        <span class="new-tip">添加地址後才能提現</span>
                    <?php }?>
                <br>    
                <label for="minAmount">提現金額：</label>
                <input class="form-control" type="text" name="amount" id="minAmount" placeholder="最低提現 <?= $minAmount?>" data-minimum="<?= $minAmount?>"/>
                <p class="footnote">
                    現時最多可提：
                    <span class="qty" data-qty="<?= $userInfo[$type.'_usable'] >= $dayAmount ? $dayAmount : $userInfo[$type.'_usable'] ?>">
                        <?php if($userInfo[$type.'_usable'] >= $dayAmount){?>
                            <?= $dayAmount.' '.strtoupper($type) ?>
                        <?php }else {?>
                            <?= $userInfo[$type.'_usable'].' '.strtoupper($type) ?>
                        <?php }?>
                    </span>
                </p> 
                <label for="withdrawFee">網絡手續費：</label>
                <input class="form-control" type="text" name="fee" id="withdrawFee" placeholder="当前网络手续费平均为 <?= $minFee?>"/>   
                <br> 
                <label for="fund_password">資金密碼：</label>
                <input type="password" name="fund_password" style="display:none" disabled>
                <input class="form-control" type="password" name="fund_password" id="fundPassword" placeholder="请输入您的資金密碼"/>
                <p class="footnote"><a href="/user/security">忘記資金密碼</a></p>
                <button type="button" class="btn btn-withdrawal">確認提現</button>
                <?php }?>
            </form>
        </div>
        <div class="record">
            <h2>提現記錄</h2>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="w25">提現時間</th>
                        <th>提現金額</th>
                        <th class="phone-hidden">網絡手續費</th>
                        <th class="phone-hidden">提現地址</th>
                        <th>提現狀態</th>
                        <th class="phone-hidden">描述</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($result)) {?>
                        <tr><td colspan="7">暫無提現记录</td></tr>
                    <?php }else {?>
                        <?php foreach ($result as $row):?>
                        <tr>
                            <td class="w25"><?= date('Y/m/d H:i:s',$row['createtime'])?></td>
                            <td><?= $row['amount']?> <?=strtoupper($type)?></td>
                            <td class="phone-hidden"><?= $row['fee']?></td>
                            <td class="phone-hidden"><?= $row['withdraw_address']?></td>
                            <td><?= ['<span>已取消</span>', '<span>申請中</span>', '<span>處理中</span>', '<span class="font-red">提現失敗</span>', '<span>提現成功</span>'][$row['withdraw_state']]?></td>
                            <td class="phone-hidden"><?= $row['reason']?></td>
                            <td>
                                <?php if($row['withdraw_state'] == 1){?>
                                <a href="javascript:;" class="recall" data-id="<?= $row['id']?>">撤回</a>
                                <?php }elseif ($row['withdraw_state'] == 4 && !empty($row['txid'])){?>
                                <a target="_blank" href="<?= $row['type'] == 'btc' ? 'https://blockchain.info/tx/' : 'https://etherscan.io/tx/' ?><?= $row['txid']?>">查看</a>
                                <?php }?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    <?php }?>
                </tbody>
            </table>
            <!--分页-->
            <div class="pages">
            	<?php echo Pagination::create($pageInfo['page'], $pageInfo['pageCount']);?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">╳</span></button>
        <h4 class="modal-title" id="addressModalLabel">新增 / 修改提現地址</h4>
      </div>
      <div class="modal-body">
        <form id="addressForm">
          <div class="form-group">
            <input type="text" class="form-control" name="withdraw_address" placeholder="提現地址">
          </div>
          <div class="form-group">
            <input type="password" name="fund_password" style="display:none" disabled>
            <input type="password" class="form-control" name="fund_password" placeholder="資金密碼">
          </div>
          <button type="button" class="btn btn-address">提交</button>
        </form>
      </div>
    </div>
  </div>
</div>

