<?php
/*
 * 分页
 * 
 */
class Pagination {
    
    /**
     * 生成分页HTML
     * 
     * @param int $curPage
     * @param int $pageCount
     * @param string $url
     * @return string
     * @author Ymj Created at 2016年12月26日
     */
    static public function create ($curPage, $pageCount, $url = null) {
        //size >= 3 且为奇数
        
        $size = 5;
        //生成需要显示的页码数组
        $pageArr = [];
        $interval = (int)(($size - 1) / 2);
        $start = $curPage - $interval;
        if($start < 1){
            $start = 1;
        }
        
        $end = $start + $size - 1;
        if($end > $pageCount){
            $end = $pageCount;
            if ($end - $start < $size - 1 && $end - $size + 1> 0){
                $start = $end - $size + 1;
            }
        }
        
        for ($index = $start; $index <= $end; $index++){
            $pageArr[] = $index;
        }
        
        $htmlStr = '<nav><ul class="pagination">';
        //上一页
        if($curPage == 1){
            $htmlStr .= '<li class="disabled"><a href="javascript:;">上一页</a></li>';
        }else{
            $htmlStr .= '<li><a href="' . self::_creatUrl($curPage - 1, $url) . '">上一页</a></li>';
        }
        //每一页
        foreach ($pageArr as $page){
            if($page == $curPage){
                $htmlStr .= '<li class="active"><a href="javascript:;">' . $page . '</a></li>';
            }else{
                $htmlStr .= '<li><a href="' . self::_creatUrl($page, $url) . '">' . $page . '</a></li>';
            }
        }
        //下一页
        if($curPage == $pageCount){
            $htmlStr .= '<li class="disabled"><a href="javascript:;">下一页</a></li>';
        }else{
            $htmlStr .= '<li><a href="' . self::_creatUrl($curPage + 1, $url) . '">下一页</a></li>';
        }
        
        $htmlStr .= '</ul></nav>';
        
        return $htmlStr;
    }
    
    
    /**
     * 根据页码生成url
     * 
     * @param int $page
     * @param string $url
     * @return 
     * @author Ymj Created at 2016年12月26日
     */
    static private function _creatUrl ($page, $url) {
        static $urlTemplate = null;
        if($urlTemplate !== null){
            return str_replace('{{page}}', $page, $urlTemplate);
        }
        //构造$urlTemplate
        if(empty($url)){
            $url = App::$request->getBaseUrl() . $_SERVER['REQUEST_URI'];
        }
        $urlArr = explode('?', $url);
        $path = $urlArr[0];
        if (count($urlArr) == 1){
            $urlTemplate = $path . '?page={{page}}';
            return $path . '?page=' . $page;
        }else{
            $queryArr = explode('&', $urlArr[1]);
            $queryArrTmp = [];
            $flag = false;
            foreach ($queryArr as $query) {
                $param = explode('=', $query);
                if(count($param) != 2){
                    continue;
                }
                if($param[0] == 'page'){
                    $flag = true;
                    $param[1] = '{{page}}';
                }
                $queryArrTmp[] = $param[0] . '=' . $param[1];
            }
            //找不到page参数
            if(!$flag){
                $queryArrTmp[] = 'page={{page}}';
            }
            $urlTemplate = $path . '?' . implode('&', $queryArrTmp);
            return str_replace('{{page}}', $page, $urlTemplate);
        }
    }
}