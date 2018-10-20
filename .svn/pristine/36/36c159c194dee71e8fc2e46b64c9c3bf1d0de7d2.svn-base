<?php
	App::$view->title = '財酷ICO - 實名認證';
    App::$view->registerCss('/public/css/identity.css');
    App::$view->registerJs('/public/js/lib/jquery.form.min.js');
	App::$view->registerJs('/public/js/main/user/identity.js');
?>
<div class="user-identity-wrap">
    <div class="user-center-head clearfix">
        <div class="left">
            <img src="/public/images/identity-ico.png">
        </div>
        <div class="left">
            <h2>實名認證</h2>
            <ul class="clearfix">
                <li class="active"><a href="/">主頁</a></li>
                <li><a>個人賬戶</a></li>
            </ul>
        </div>
    </div>
    <?php if($verifyRecord['verify_state'] == 0 || $verifyRecord==''){?>
        <div class="identification">
            <form class="form-horizontal">
                <?php if($verifyRecord['reason'] !='' ){?>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <span class="error"><?= $verifyRecord['reason']?></span>
                        </div>
                    </div>
                <?php }?>
                <div class="form-group">
                    <label for="compellation" class="col-sm-3 control-label">姓名：</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control" id="compellation" placeholder="請輸入您的姓名" value="<?= $verifyRecord['name']?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="cardno" class="col-sm-3 control-label">身份證號：</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control" id="cardno" placeholder="請輸入身份證號碼" value="<?= $verifyRecord['cardno']?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="identity_hand_pic" class="col-sm-3 control-label">手持身份證正面：</label>
                    <div class="col-sm-9">
                        <div class="upload-identity">
                            <a class="upload-button" href="javascript:;">
                                 <?php if($verifyRecord['pic_hand'] !='' ){?>
                                    <img src="<?= $verifyRecord['pic_hand']?>">
                                 <?php }?>
                                 <input type="hidden" name="identity_hand_pic" class="hide-pic" value="<?= $verifyRecord['pic_hand']?>">
                                 <input type="file" name="picture" class="file-upload">
                                 <svg height="34" width="34" viewBox="0 0 34 34">
                                    <circle class="add-circle" fill="#BEBEBE" cx="17" cy="17" r="17"></circle>
                                    <path class="add-rect" fill-rule="evenodd" clip-rule="evenodd" fill="#FFF" d="M7 15.345h20v3.31H7z"></path>
                                    <path class="add-rect" fill-rule="evenodd" clip-rule="evenodd" fill="#FFF" d="M15.345 7h3.31v20h-3.31z"></path>
                                </svg>
                            </a>
                            <p>手持身份證正面</p>
                        </div>
                        <div class="upload-example">
                            <div class="picture-example">
                                <img src="/public/images/identity_hand_pic.png">
                            </div>
                            <div class="example-intro">
                                <h4>示例</h4>
                                <p>保證人物頭像和身份證內容清晰可識別</p>
                                <p>圖片大小不要超過1MB</p>
                                <p>建議使用QQ截圖</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="identity_front_pic" class="col-sm-3 control-label">身份證正面：</label>
                    <div class="col-sm-9">
                        <div class="upload-identity">
                            <a class="upload-button" href="javascript:;">
                                <?php if($verifyRecord['pic_front'] !='' ){?>
                                    <img src="<?= $verifyRecord['pic_front']?>">
                                 <?php }?>
                                 <input type="hidden" name="identity_front_pic" class="hide-pic" value="<?= $verifyRecord['pic_front']?>">
                                 <input type="file" name="picture" class="file-upload">
                                 <svg height="34" width="34" viewBox="0 0 34 34">
                                    <circle class="add-circle" fill="#BEBEBE" cx="17" cy="17" r="17"></circle>
                                    <path class="add-rect" fill-rule="evenodd" clip-rule="evenodd" fill="#FFF" d="M7 15.345h20v3.31H7z"></path>
                                    <path class="add-rect" fill-rule="evenodd" clip-rule="evenodd" fill="#FFF" d="M15.345 7h3.31v20h-3.31z"></path>
                                </svg>
                            </a>
                            <p>身份證正面</p>
                        </div>
                        <div class="upload-example">
                            <div class="picture-example">
                                <img src="/public/images/identity_front_pic.png">
                            </div>
                            <div class="example-intro">
                                <h4>示例</h4>
                                <p>保證人物頭像和身份證內容清晰可識別</p>
                                <p>圖片大小不要超過1MB</p>
                                <p>建議使用QQ截圖</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="identity_back_pic" class="col-sm-3 control-label">身份證背面：</label>
                    <div class="col-sm-9">
                        <div class="upload-identity">
                            <a class="upload-button" href="javascript:;">
                                <?php if($verifyRecord['pic_back'] !='' ){?>
                                    <img src="<?= $verifyRecord['pic_back']?>">
                                 <?php }?>
                                 <input type="hidden" name="identity_back_pic" class="hide-pic" value="<?= $verifyRecord['pic_back']?>">
                                 <input type="file" name="picture" class="file-upload">
                                 <svg height="34" width="34" viewBox="0 0 34 34">
                                    <circle class="add-circle" fill="#BEBEBE" cx="17" cy="17" r="17"></circle>
                                    <path class="add-rect" fill-rule="evenodd" clip-rule="evenodd" fill="#FFF" d="M7 15.345h20v3.31H7z"></path>
                                    <path class="add-rect" fill-rule="evenodd" clip-rule="evenodd" fill="#FFF" d="M15.345 7h3.31v20h-3.31z"></path>
                                </svg>
                            </a>
                            <p>身份證背面</p>
                        </div>
                        <div class="upload-example">
                            <div class="picture-example">
                                <img src="/public/images/identity_back_pic.png">
                            </div>
                            <div class="example-intro">
                                <h4>示例</h4>
                                <p>保證人物頭像和身份證內容清晰可識別</p>
                                <p>圖片大小不要超過1MB</p>
                                <p>建議使用QQ截圖</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" checked class="license"> 我承諾所提供的資料為我本人所有，不存在盜用別人資料的情況。
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-verify">提交</button>
                    </div>
                </div>
            </form>
        </div>
    <?php }else {?>
        <div class="verified">
            <div class="verified-title">基本認證信息</div>
            <div class="form-group">
                <label>姓名：</label>
                <span><?= $verifyRecord['name']?></span>
            </div>
            <div class="form-group">
                <label>賬戶：</label>
                <span><?= $userInfo['email'] ?></span>
            </div>
            <div class="form-group">
                <label>身份證：</label>
                <span><?= Format::convertIdentitycard($verifyRecord['cardno']) ?></span>
            </div>
            <div class="form-group">
                <label>實名認證：</label>
                <?php if($verifyRecord['verify_state'] == 1){?>
                    <span>待審核</span>
                <?php }else {?>
                    <span class="already-auth">已認證</span>
                <?php }?>
            </div>
        </div>
    <?php }?>
</div>
 
