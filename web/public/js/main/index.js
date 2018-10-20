$(function() {
    $('body').on('click', '.notice-close', function() { //關閉公告
        $('.site-notice').addClass('hide');
    });
    //行情
    //判断是否支持websocket
    if (typeof(WebSocket) != 'undefined') {
        //loadOkcoinMarket();
        loadHuobiMarket();

    } else {
        //不支持
    }

    //huobi
    function loadHuobiMarket() {
        loadMarketBtcAndLtc();
        loadMarketEth();
    }

    function loadMarketBtcAndLtc() {
        var webSocket = new WebSocket("wss://api.huobi.com/ws");
        webSocket.binaryType = "arraybuffer";
        webSocket.onopen = function(event) {
            console.log(("webSocket connect at time: " + new Date()));
            webSocket.send(JSON.stringify({ 'sub': 'market.btccny.detail', 'id': 'btcmarket' + new Date() }));
            webSocket.send(JSON.stringify({ 'sub': 'market.ltccny.detail', 'id': 'ltcmarket' + new Date() }));
        }
        webSocket.onmessage = function(event) {
            var raw_data = event.data;
            var ua = new Uint8Array(raw_data);
            var json = pako.inflate(ua, { to: "string" });
            var data = JSON.parse(json);
            if (data["ping"]) {
                webSocket.send(JSON.stringify({ "pong": data["ping"] }));
            } else {
                if (data.ch == 'market.btccny.detail') {
                    $('#btcPrice').text(tool.numberFormat(data.tick.close, { digit: 2, trim: false, split: ',' }));
                } else if (data.ch == 'market.ltccny.detail') {
                    $('#ltcPrice').text(tool.numberFormat(data.tick.close, { digit: 2, trim: false, split: ',' }));
                } else {

                }
            }
        }

        webSocket.onclose = function() {
            console.log("webSocket connect is closed");
            console.log(arguments);
        }

        webSocket.onerror = function() {
            console.log("error");
            console.log(arguments);
        }
    }

    function loadMarketEth() {
        var webSocket = new WebSocket("wss://be.huobi.com/ws");
        webSocket.binaryType = "arraybuffer";
        webSocket.onopen = function(event) {
            console.log(("webSocket connect at time: " + new Date()));
            webSocket.send(JSON.stringify({ 'sub': 'market.ethcny.detail', 'id': 'ethmarket' + new Date() }));
        }
        webSocket.onmessage = function(event) {
            var raw_data = event.data;
            var ua = new Uint8Array(raw_data);
            var json = pako.inflate(ua, { to: "string" });
            var data = JSON.parse(json);
            if (data["ping"]) {
                webSocket.send(JSON.stringify({ "pong": data["ping"] }));
            } else {
                //console.log(data);
                if (data.ch == 'market.ethcny.detail') {
                    $('#ethPrice').text(tool.numberFormat(data.tick.close, { digit: 2, trim: false, split: ',' }));
                } else {

                }
            }
        }

        webSocket.onclose = function() {
            console.log("webSocket connect is closed");
            console.log(arguments);
        }

        webSocket.onerror = function() {
            console.log("error");
            console.log(arguments);
        }
    }

    //获取OKCoin行情数据
    function loadOkcoinMarket() {
        var webSocket = new WebSocket('wss://real.okcoin.cn:10440/websocket/okcoinapi');
        var lastHeartBeat = new Date().getTime();
        webSocket.onopen = function(event) {
            console.log(("webSocket connect at time: " + new Date()));
            webSocket.send("{'event':'addChannel','channel':'ok_sub_spotcny_btc_ticker'}");
            webSocket.send("{'event':'addChannel','channel':'ok_sub_spotcny_ltc_ticker'}");
            webSocket.send("{'event':'addChannel','channel':'ok_sub_spotcny_eth_ticker'}");
        }

        webSocket.onmessage = function(event) {
            var array = JSON.parse(event.data);

            for (var i = 0; i < array.length; i++) {
                if (array[i].channel == 'ok_sub_spotcny_btc_ticker') {
                    $('#btcPrice').text(tool.numberFormat(array[i].data.close, { digit: 2, trim: false, split: ',' }));
                } else if (array[i].channel == 'ok_sub_spotcny_ltc_ticker') {
                    $('#ltcPrice').text(tool.numberFormat(array[i].data.close, { digit: 2, trim: false, split: ',' }));
                } else if (array[i].channel == 'ok_sub_spotcny_eth_ticker') {
                    $('#ethPrice').text(tool.numberFormat(array[i].data.close, { digit: 2, trim: false, split: ',' }));
                } else {

                }

            }

            if (array.event == 'pong') {
                lastHeartBeat = new Date().getTime();
            } else {

            }

        }

        webSocket.onclose = function() {
            console.log("webSocket connect is closed");
            console.log(arguments);
        }

        webSocket.onerror = function() {
            console.log("error");
            console.log(arguments);
        }
    }
    //日历
    // $('#calendar').fullCalendar({
    // 	header: {
    // 		left: 'title',
    // 		right: 'today prev,next'
    // 	},
    // 	locale: 'zh-cn',
    // 	eventLimit: true, // allow "more" link when too many events
    // 	height: 570,
    // 	firstDay: 0,
    // 	businessHours: true,
    // 	aspectRatio: 1.5,
    // 	events: function(start,end,timezone, callback) {
    //         $.ajax({
    //         	type:"get",
    //             url: '/index/GetProjectNews',
    //             dataType: 'json',
    //             success: function(data){ // 返回json数据
    //                 var events = [];
    //                 if (data.success == true) {
    //                     $.each(data.data,function(k,v) {
    //                             events.push({
    //                                 title: v.title,
    //                                 start: v.publish_time, 
    //                                 url: '/project/detail?id='+v.project_id,
    //                             });
    //                     });
    //                 }
    //                 callback(events);
    //                 },
    //             });
    //         }
    // });
});