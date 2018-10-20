/**
 * requirejs 配置
 * 
 */
var require = {
		baseUrl : '/public/js',
		urlArgs : 'v=' + ((typeof version != 'undefined') ? version : (new Date().getFullYear() + '' + (new Date().getMonth() + 1) + '' + new Date().getDay())),
		paths	: {
			'jquery' 				: 'lib/jquery-3.0.0.min',
			'mui'					: 'lib/mui.min',
			'mui-extend'			: 'lib/mui.extend',
			'cookie' 				: 'lib/jquery.cookie',
			'weixin'				: '//res.wx.qq.com/open/js/jweixin-1.0.0',
			'io'					: 'lib/socket.io-1.4.5',
			'xss'					: 'lib/xss.min',
			'tool'   				: 'lib/tool',
			'mustache'   			: 'lib/mustache.min',
			'validator'				: 'lib/validator',
			'jquery.bootstrap'   	: 'lib/bootstrap.min'
		},
		shim	: {
			'jquery.bootstrap'		: {
				deps: ['jquery']
			}
		}
};