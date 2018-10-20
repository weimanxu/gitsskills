<?php
App::$view->title = '財酷ICO - 公告詳情';
App::$view->registerCss('/public/css/annoucement.css');
App::$view->registerJs('/public/js/main/user/annoucement.js');
?>
<div class="annoucement">
    <h2>【<?= $annou_info['type'] ?>】<?= $annou_info['title']?></h2>
    <div class="detail-box">
        <p class="small-title"><span>发布时间：<?= date('Y/m/d H:i:s', $annou_info['createtime']) ?></span><span>发布人：<?= $annou_info['author'] ?></span></p>
        <div class="content-box">
            <?= $annou_info['detail'] ?>
        </div>
    </div>

</div>
