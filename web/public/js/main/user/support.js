/**
 * 我支持的项目js
 * @param         
 * @return 
 * @author OU.jc Create At 2017年8月4日
 */
$(function() {
    $('body').on('click', '.invest-detail-button', function() { //投资明细
        var invest_list = '';
        var id = $(this).data('id');
        var invest = $('.detal' + id);

        if (!invest.has('.invest-list').length) {
            $.post('/user/investmentDetails', { id: id }, function(res) {
                if (res.success) {
                    if (!!res.data.length) {
                        for (var i = 0; i < res.data.length; i++) {
                            invest_list += '<tr><td>' +
                                res.data[i].createtime +
                                '</td><td> ' +
                                res.data[i].amount + ' ' + res.data[i].type +
                                '</td><td>' +
                                (res.data[i].token > 0 ? res.data[i].token : '--') +
                                '</td>';
                        }
                    } else {
                        invest_list = '<tr><td colspan=4>暫無投資記錄！</td></tr>'
                    }
                } else {
                    invest_list = '<tr><td colspan=4>獲取數據出錯，請刷新重試！</td></tr>'
                }
                var table_pan = '<table class="table invest-list">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>投資時間</th>' +
                    '<th>投資金額</th>' +
                    '<th>代幣數量</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>' +
                    invest_list +
                    '</tbody>' +
                    '</table>';

                invest.html(table_pan);
            }, 'json').error(function() {
                layer.msg('網絡出錯，請刷新重試！', {
                    icon: 2,
                    time: 2000
                }, function() {
                    window.location.reload();
                });
            });
            invest.slideDown(0);
            $(this).parents('.project-pan').addClass('shadow');
        } else {
            invest.slideToggle(0);
            $(this).parents('.project-pan').toggleClass('shadow');
        }
    }).on('click', '.withdraw-detail-button', function() { //提现明细
        var withdraw_list = '';
        var id = $(this).data('id');
        var withdraw = $('.detal' + id);

        if (!withdraw.has('.withdraw-list').length) {
            $.post('/user/tokenWithdrawDetails', { id: id }, function(res) {
                if (res.success) {
                    if (!!res.data.length) {
                        for (var i = 0; i < res.data.length; i++) {
                            withdraw_list += '<tr><td>' +
                                res.data[i].createtime +
                                '</td><td> ' +
                                res.data[i].amount +
                                '</td><td>' +
                                res.data[i].address +
                                '</td><td>' +
                                (['已取消', '申請中', '處理中', '提現失敗', '提現成功'][res.data[i].withdraw_state]) +
                                '</td><td>' +
                                (res.data[i].withdraw_state == '1' ? '<a href="javascript:;" class="revocation" data-id="' + res.data[i].id + '">撤銷</a>' : '') +
                                '</td>';
                        }
                    } else {
                        withdraw_list = '<tr><td colspan="5">暫無代幣提現記錄！</td></tr>'
                    }
                } else {
                    withdraw_list = '<tr><td colspan="5">獲取數據出錯，請刷新重試！</td></tr>'
                }
                var table_pan = '<table class="table withdraw-list">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>提現時間</th>' +
                    '<th>提現金額</th>' +
                    '<th>提現地址</th>' +
                    '<th>提現狀態</th>' +
                    '<th>操作</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>' +
                    withdraw_list +
                    '</tbody>' +
                    '</table>';

                withdraw.html(table_pan);
            }, 'json').error(function() {
                layer.msg('網絡出錯，請刷新重試！', {
                    icon: 2,
                    time: 2000
                }, function() {
                    window.location.reload();
                });
            });
            withdraw.slideDown(0);
            $(this).parents('.project-pan').addClass('shadow');
        } else {
            withdraw.slideToggle(0);
            $(this).parents('.project-pan').toggleClass('shadow');
        }
    }).on('click', '.withdraw-button', function() { //提现对话框
        var formContent = '';
        var id = $(this).data('id');
        var address = $(this).data('address');
        var balance = $(this).data('balance');

        if (address) {
            formContent = '<div>' +
                '<div class="form-group">' +
                '<input type="text" class="form-control address-input" name="withdraw_address" value="' + address + '" disabled>' +
                '<a href="javascript:;" class="address-edit" data-id="' + id + '">修改</a>' +
                '</div>' +
                '<div class="form-group">' +
                '<input type="text" class="form-control" name="withdraw_number" placeholder="請輸入提幣數量" id="withNumber" value="' + balance + '" disabled>' +
                '</div>' +
                '<div class="form-group">' +
                '<input type="password" name="fund_password" style="display:none" disabled>' +
                '<input type="password" class="form-control" name="fund_password" placeholder="資金密碼" id="fundPassword">' +
                '</div>' +
                '<button type="button" class="btn withdraw-submit" data-id="' + id + '">提交</button>' +
                '</div>';

            $('.modal-title').html('代幣提現');
        } else {
            formContent = '<div>' +
                '<div class="form-group">' +
                '<input type="text" class="form-control" name="withdraw_address" placeholder="提現地址" id="withAddress">' +
                '</div>' +
                '<div class="form-group">' +
                '<input type="password" name="fund_password" style="display:none" disabled>' +
                '<input type="password" class="form-control" name="fund_password" placeholder="資金密碼" id="fundPassword">' +
                '</div>' +
                '<button type="button" class="btn address-submit" data-id="' + id + '">提交</button>' +
                '</div>';

            $('.modal-title').html('新增提現地址');
        }

        $('#withdrawForm').html(formContent);
    }).on('click', '.address-edit', function() { //修改提現地址
        var id = $(this).data('id');
        var formContent = '<div>' +
            '<div class="form-group">' +
            '<input type="text" class="form-control" name="withdraw_address" placeholder="提現地址" id="withAddress">' +
            '</div>' +
            '<div class="form-group">' +
            '<input type="password" name="fund_password" style="display:none" disabled>' +
            '<input type="password" class="form-control" name="fund_password" placeholder="資金密碼" id="fundPassword">' +
            '</div>' +
            '<button type="button" class="btn address-submit" data-id="' + id + '">提交</button>' +
            '</div>';

        $('.modal-title').html('修改提現地址');
        $('#withdrawForm').html(formContent);
    }).on('click', '.withdraw-submit', function() { //提交提现信息
        var projectId = $(this).data('id');
        var withNumber = $('#withNumber').val();
        var fundPassword = $('#fundPassword').val();

        if (!withNumber) {
            layer.tips('請輸入提幣數量', $('#withNumber'), {
                tips: [1, '#d9534f']
            });
            return false;
        }
        if (!fundPassword) {
            layer.tips('请输入資金密碼', $('#fundPassword'), {
                tips: [1, '#d9534f']
            });
            return false;
        }
        $.post('/user/tokenTakeWithdraw', { id: projectId, amount: withNumber, fund_password: fundPassword }, function(res) {
            if (res.success) {
                $('#supportModal').modal('hide');
                layer.msg(res.data, {
                    icon: 1,
                    time: 2000
                }, function() {
                    window.location.reload();
                });
            } else {
                layer.tips(res.error, $('.withdraw-submit'), {
                    tips: [1, '#d9534f']
                });
                return false;
            }
        }, 'json').error(function() {
            layer.tips('網絡出錯，請刷新重試！', '.withdraw-submit', {
                tips: [1, '#d9534f']
            });
        });

    }).on('click', '.address-submit', function() { //新增、修改提現地址
        var projectId = $(this).data('id');
        var withAddress = $('#withAddress').val();
        var fundPassword = $('#fundPassword').val();

        if (!withAddress) {
            layer.tips('请输入提現地址', $('#withAddress'), {
                tips: [1, '#d9534f']
            });
            return false;
        }
        if (!fundPassword) {
            layer.tips('请输入資金密碼', $('#fundPassword'), {
                tips: [1, '#d9534f']
            });
            return false;
        }

        $.post('/user/tokenRefreshAddress', { id: projectId, withdraw_address: withAddress, fund_password: fundPassword }, function(res) {
            if (res.success) {
                $('#supportModal').modal('hide');
                layer.msg(res.data, {
                    icon: 1,
                    time: 2000
                }, function() {
                    window.location.reload();
                });
            } else {
                layer.tips(res.error, $('.address-submit'), {
                    tips: [1, '#d9534f']
                });
                return false;
            }
        }, 'json').error(function() {
            layer.tips('網絡出錯，請刷新重試！', '.address-submit', {
                tips: [1, '#d9534f']
            });
        });
    }).on('click', '.revocation', function() { //代幣提現撤销
        var _this = $(this);
        var id = _this.data('id');
        $.post('/user/tokenRevocation', { id: id }, function(res) {
            if (res.success) {
                layer.msg(res.data, {
                    icon: 1,
                    time: 2000
                }, function() {
                    window.location.reload();
                });
            } else {
                layer.tips(res.error, _this, {
                    tips: [1, '#d9534f']
                });
                return false;
            }
        }, 'json').error(function() {
            layer.msg('網絡出錯，請刷新重試！', {
                icon: 2,
                time: 2000
            }, function() {
                window.location.reload();
            });
        });
    });
});