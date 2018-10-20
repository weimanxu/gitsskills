/**
 * 延迟加载，需先加载jquery
 * 
 * TODO 1、增加依赖管理；2、允许并行加载
 */
(function($, global){
	if (global.lazyLoad)
		return ;
	
	var cache = {};
	
	function main (depend, callback) {
		var scripts = depend;
		
		if (typeof scripts == 'string') {
			scripts = [scripts];
		}
		
		loadScript(depend, callback);
		
	}
	
	function loadScript (scripts, callback) {
		var script = scripts.shift();
		if (!script){
			//加载完毕
			callback();
			return ;
		}
		if (cache[script]) {
			if (cache[script] == 'done') {
				//已加载过，next;
				loadScript(scripts, callback);
			}else {
				//正在加载中..
				//保存状态，等待恢复
				cache[script].push((function(scripts, callback){
					return function(){
						loadScript(scripts, callback);
					};
				})(scripts, callback));
			}
		}else{
			cache[script] = [];
			$.getScript(script, function(){
				loadScript(scripts, callback);
				
				if (cache[script].length > 0) {
					//恢复状态
					for (var i = 0; i < cache[script].length.length; i++) {
						cache[script][i]();
					}
					
					//done
					cache[script] = 'done';
				}
			});
		}
	}
	
	global.lazyLoad = function (depend, callback) {
		
		return main (depend, callback);
	};
})(window.jQuery, window);