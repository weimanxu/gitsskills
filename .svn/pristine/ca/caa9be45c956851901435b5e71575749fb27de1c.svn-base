<?php
App::$view->title = '財酷ICO - 安全設置';
App::$view->registerCss('/public/css/security.css');
App::$view->registerJs('/public/js/main/user/security.js');
?>
<div class="user-safe-wrap">
    <div class="clearfix home-nav">
        <img src="/public/images/safe_icon.png">
        <div>
            <h2>安全設置</h2>
            <ul>
                <li><a href="/">主頁</a></li>
                <li><a>個人賬戶</a></li>
            </ul>
        </div>
    </div>
    <div class="alert" role="alert">
        <p>
            <img src="/public/images/<?= $userInfo['phone'] != '' ? 'suc' : 'wro'; ?>_phone_icon.png"><span>手機號碼</span>
        </p>
        <span>賬戶啟動時通知該號碼：<?= Format::convertPhone($userInfo['phone']) ?>（修改後24小時不能提現）</span>
        <a <?= $userInfo['phone'] != '' ? 'class="change_suc change"' : 'class="change_wro change"'; ?> data-toggle="modal" data-target="#myModal" data-id="1" data-info="<?= Format::convertPhone($userInfo['phone'])?>">修改</a>
    </div>
    <div class="alert" role="alert">
        <p>
            <img src="/public/images/<?= $userInfo['fund_password'] != '' ? 'suc' : 'wro'; ?>_code_icon.png"><span>登錄密碼</span>
        </p>
        <span>您上次登錄的時間為：<?= date('Y/m/d H:i:s', $login_infos[0]['createtime']) ?>
            &nbsp;&nbsp;IP為：<?= $login_infos[0]['ip'] ?></span>
        <a <?= $userInfo['fund_password'] != '' ? 'class="change_suc change"' : 'class="change_wro change"'; ?> data-toggle="modal" data-target="#myModal" data-id="2">修改</a>
    </div>
    <div class="alert" role="alert">
        <p>
            <img src="/public/images/<?= $userInfo['email'] != '' ? 'suc' : 'wro'; ?>_email_icon.png"><span>郵箱綁定</span>
        </p>
        <span>用戶登錄進行時確認（修改後24小時不能提現）</span>
        <a <?= $userInfo['email'] != '' ? 'class="change_suc change"' : 'class="change_wro change"'; ?> data-info="<?= $userInfo['email']?>" data-toggle="modal" data-target="#myModal" data-id="3">修改</a>
    </div>
    <div class="alert" role="alert">
        <p>
            <img src="/public/images/<?= $userInfo['fund_password'] != '' ? 'suc' : 'wro'; ?>_crash_icon.png"><span>資金密碼</span>
        </p>
        <span>用戶提現時進行確認（修改後24小時不能提現）</span>
        <a <?= $userInfo['fund_password'] != '' ? 'class="change_suc change"' : 'class="change_wro change"'; ?> id="crash" data-toggle="modal" data-target="#myModal" data-phone="<?= Format::convertPhone($userInfo['phone']) ?>" data-email="<?= $userInfo['email'] ?>" data-id="4">設置</a>
    </div>
    <div class="alert" role="alert">
        <p>
            <img src="/public/images/<?= $verify_state == 2 ? 'suc' : 'wro'; ?>_identy_icon.png"><span>身份認證</span>
        </p>
        <span>確認用戶真實身份</span>
        <a <?= $verify_state == 2 ? 'class="change_suc change"' : 'class="change_wro change"'; ?> href="/user/identity">查看</a>
    </div>
    <div class="myRecord">
        <h2>最近十次登錄記錄</h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="text-center">登錄時間</th>
                    <th class="text-center">登錄IP</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($login_infos as $row): ?>
                <tr>
                    <td class="text-center "><?= date('Y/m/d H:i:s', $row['createtime']) ?></td>
                    <td class="text-center"><?= $row['ip'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">╳</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
</div>