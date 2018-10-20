/**
 * mui 自定义扩展
 * 
 */
(function (root, factory) {
    var muiExtend = factory(root);
    if (typeof define === 'function' && define.amd) {
        // AMD
        // define([], factory);
        define('muiExtend', function() { return muiExtend; });
    } else if (typeof exports === 'object') {
        // Node.js
        module.exports = muiExtend;
    } else {
        // Browser globals
        root.muiExtend = muiExtend;
    }
})(this, function () {
/**
 * 提示框
 */
(function($, window) {
	var CLASS_ACTIVE = 'mui-active';
	/**
	 * 自定义提示框
	 */
	var globToastIns = null, globIdentity;
	$.toastOpen = function(message, time, callback) {
		var toast, 
			toastIns,
			identity = new Date().getTime() + '' + Math.floor(Math.random() * 10000);
		
		if (globToastIns != null) {
			globIdentity = identity;
			globToastIns.text(message);
			//自动消失
			if (!!time){
				setTimeout(function(){
					if (globToastIns != null) {
						globToastIns.close();
					}
					if (callback)
						callback();
				}, time);
			}
			return globToastIns;
		}
		
		toast = document.createElement('div')
		toast.classList.add('mui-toast-container');
		toast.classList.add('mui-toast-custom');
		toast.innerHTML = '<div class="' + 'mui-toast-message' + '">' + message + '</div>';
		toast.addEventListener('webkitTransitionEnd', function() {
			if (!toast.classList.contains(CLASS_ACTIVE)) {
				toast.parentNode.removeChild(toast);
				globToastIns = null;
			}
		});
		document.body.appendChild(toast);
		toast.classList.add(CLASS_ACTIVE);
		
		toastIns = {
			'close' : function() {
				if (toast) {
					toast.classList.remove(CLASS_ACTIVE);
				}
			},
			'text' : function(message) {
				if (toast) {
					toast.firstChild.innerText = message;
				}
			}
		};
		
		//自动消失
		if (!!time){
			setTimeout(function(){
				if (toastIns != null) {
					toastIns.close();
				}
				if (callback)
					callback();
			}, time);
		}
		
		globToastIns = toastIns;
		
		return toastIns;
	};
	
	$.toastClose = function() {
		if (globToastIns != null) {
			globToastIns.close();
		}
	};
	
	$.imgPreview = function(element, afterOpen) {
		var wrap = $('#__mui-imageview__');
		
		if (wrap.length == 0) {
			//创建wrap
			wrap = document.createElement("div");
			wrap.setAttribute("id", "__mui-imageview__");
			wrap.classList.add("mui-fullscreen");
			wrap.classList.add("mui-preview-custom");
			wrap.innerHTML = '<div class="inner"><a id="__mui-imageview-a__"></a></div>';
			wrap.style.display = "none";
			document.body.appendChild(wrap);
			
			wrap.addEventListener("tap", function(event) {
				wrap.style.display = "none";
			});
			
			wrap.addEventListener("touchmove", function(event) {
				event.preventDefault();
			})
		}
		
		element = $(element);
		if (element.length == 0)
			return ;
		
		$.each(element, function(index, ele){
			ele.addEventListener("tap", function(event) {
				var a = mui('#__mui-imageview-a__')[0],
				 	img = document.createElement("img"),
				 	original, originalSrc;
				
				img.setAttribute("src", this.src);
				a.innerHTML = '';
				a.appendChild(img);
				wrap.style.display = "block";
				
				originalSrc = this.getAttribute('data-original');
				if(originalSrc){
					original = new Image(); 
					original.onload = function () {
						img.setAttribute('src', originalSrc);
//						img.classList.add("w100");
					};
					original.setAttribute('src', originalSrc);
				}
				
				if (typeof afterOpen == 'function') {
					afterOpen(event);
				}
			});
		});
	};
	
})(mui, window);
	
});