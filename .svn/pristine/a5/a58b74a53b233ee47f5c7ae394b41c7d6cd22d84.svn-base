/**
 * 实名认证
 * @param         
 * @return 
 * @author OU.jc Create At 2017年7月28日
 */
$(function() {
    var clickFlag = true;

    $('body').on('change', '.file-upload', function(e) { //照片上传
        uploadFile('/user/UploadIdentityImg', $(this));
    }).on('click', '.btn-verify', function(e) { //提交认证信息
        e.preventDefault();
        var $license = $('.license').is(':checked');
        var $name = $.trim($('#compellation').val());
        var $cardno = $.trim($('#cardno').val());
        var $identity_hand_pic = $('input[name="identity_hand_pic"]').val();
        var $identity_front_pic = $('input[name="identity_front_pic"]').val();
        var $identity_back_pic = $('input[name="identity_back_pic"]').val();
        var cardnoReg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;

        if (!$license) {
            errorTip('請先勾選承諾條款！');
            return false;
        } else if ($name == '') {
            errorTip('名字不能為空' );
            return false;
        } else if (!$cardno.match(cardnoReg)) {
            errorTip('請填寫正確的身份證號碼');
            return false;
        } else if ($identity_hand_pic == '') {
            errorTip('手持身份證照片不能為空！');
            return false;
        } else if ($identity_front_pic == '') {
            errorTip('身份證正面照不能為空！');
            return false;
        } else if ($identity_back_pic == '') {
            errorTip('身份證背面照不能為空！');
            return false;
        }

        var data = { name: $name, cardno: $cardno, pic_hand: $identity_hand_pic, pic_front: $identity_front_pic, pic_back: $identity_back_pic }

        if (clickFlag) {
            clickFlag = false;
            $.post('/user/takeIdentityInfo', data, function(res) {
                if (res.success) {
                    layer.msg(res.data, {
                        icon: 1,
                        time: 2000
                    }, function() {
                        window.location.reload();
                    });
                } else {
                    clickFlag = true;
                    layer.tips(res.error, '.btn-verify', {
                        tips: [1, '#d9534f']
                    });
                }
            }, 'json').error(function() {
                clickFlag = true;
                layer.tips('網絡出錯，請刷新重試！', '.btn-verify', {
                    tips: [1, '#d9534f']
                });
            });
        }
    });

    //上传函数
    function uploadFile(url, $selector) {
        $selector.wrap('<form enctype="multipart/form-data"></form>');
        var $proForm = $selector.parent('form');
        $proForm.ajaxSubmit({
            type: 'POST',
            url: url,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,

            beforeSubmit: function() {
                if ($selector.val() == '') {
                    layer.tips('請先選擇圖片', $selector, {
                        tips: [1, '#d9534f']
                    });
                    return false;
                }
            },
            success: function(res) {
                previewFile(res, $selector);
                if (!res.success) {
                    layer.tips(res.data, $selector, {
                        tips: [1, '#d9534f']
                    });
                }
            },
            error: function(res) {
                $selector.unwrap();
                layer.tips('請選擇正確的圖片，不能超過限制大小！', $selector, {
                    tips: [1, '#d9534f']
                });
            }
        });
    }

    //上传后执行
    function previewFile(res, $selector) {
        $selector.unwrap();
        var imgs = '<img src="' + res.data + '">';
        var $parent = $selector.parent();
        if (!$parent.hasClass('uploaded') && !$parent.has('img').length) {
            $parent.addClass('uploaded');
            $parent.prepend(imgs);
        } else {
            $parent.find('img').replaceWith(imgs);
        }
        $parent.find('.hide-pic').val(res.data);
    }

    //错误提示
    function errorTip($content, $selector, type) {
        layer.tips($content, $selector|| $('.btn-verify'), {
            tips: [type || 1, '#d9534f']
        });
    }
})