(function(){
	
	var pagination = function(page, pageCount, pageSize){
		var pageArr = [], interval, start, end, htmlStr = '';
		if(pageSize == undefined){
			pageSize = 5;
		}
		
		interval = Math.floor((pageSize - 1) / 2);
		start = page - interval;
		if(start < 1){
			start = 1;
        }
			
        
        end = start + pageSize - 1;
        if(end > pageCount){
            end = pageCount;
            if (end - start < pageSize - 1 && end - pageSize + 1 > 0){
                start = end - pageSize + 1;
            }
        }
        
        for (var index = start; index <= end; index++){
            pageArr.push(index);
        }
        
        htmlStr = '<ul class="pagination">';
        //上一页
        if(page == 1){
        	htmlStr += '<li class="disabled"><a href="javascript:;">«</a></li>';
        }else{
        	htmlStr += '<li><a data-page="' + (page - 1) + '" href="javascript:;">«</a></li>';
        }
        //每一页
        for (var i = 0; i < pageArr.length; i++) {
        	if(page == pageArr[i]){
        		htmlStr += '<li class="active"><a href="javascript:;">' + pageArr[i] + '</a></li>';
        	}else{
        		htmlStr += '<li><a data-page="' + pageArr[i] + '" href="javascript:;">' + pageArr[i] + '</a></li>';
        	}
		}
        
        //下一页
        if(page == pageCount){
            htmlStr += '<li class="disabled"><a href="javascript:;">»</a></li>';
        }else{
            htmlStr += '<li><a data-page="' + (page + 1) + '" href="javascript:;">»</a></li>';
        }
        
        htmlStr += '</ul>';
        
        return htmlStr;
	};
	
	window.pagination = pagination;
})();