<?php
	App::$view->title = '财酷ICO - 資金明細';
    App::$view->registerCss('/public/css/funds.css');
    App::$view->registerJs('/public/js/main/fund/capital.js');
?>
<div class="user-founds-wrap">
    <div class="user-center-head clearfix">
        <div class="left">
            <img src="/public/images/user-capital.png">
        </div>
        <div class="left">
            <h2>資金明細</h2>
            <ul class="clearfix">
                <li class="active"><a href="/">主頁</a></li>
                <li><a>資金管理</a></li>
            </ul>
        </div>
    </div>
    <div class="founds-body">
    	<ul class="founds-body-nav clearfix">
            <li><a class="<?= $type == 'btc' ? 'active' : ''?>" href="/fund/capital?type=btc">BTC明細</a></li>
            <li><a class="<?= $type == 'eth' ? 'active' : ''?>" href="/fund/capital?type=eth">ETH明細</a></li>
        </ul>
    	<div class="record">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="w25">時間</th>
                        <th>類型</th>
                        <th>變動金額</th>
                        <th>帳戶餘額</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($recordInfos)) {?>
                       <tr><td colspan="4">暫無資金記錄</td></tr>
                    <?php }else {?>
                        <?php foreach ($recordInfos as $row):?>
                        <tr>
                            <td class="w25"><?= date('Y/m/d H:i:s', $row['createtime'])?></td>
                            <td><?= $row['typeText']?></td>
                            <td><?= $row['amount'] . ' ' . strtoupper($row['type'])?></td>
                            <td><?= $row['balance'] . ' ' . strtoupper($row['type'])?></td>
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
 
