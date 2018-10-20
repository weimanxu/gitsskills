<?php
	class Gopage {
		
		/**
		 * 生成翻页Html
		 * 
		 * @param int 		$pagecount	//总页数 			| 必须
		 * @param int 		$curpage 	//当前页 			| 必须
		 * @param string 	$href 		//跳转连接 			| 必须        例  ："/news/type?curpage="
		 * 
		 * @param array 	$extend 	//额外参数 			| 可选，键值对数组
		 * @param string 	$style		//样式			| 可选，默认 "s1"， s1|s2
		 * @param int 		$tagnum 	//显示数字按钮数 		| 可选，默认4，但必须大于3
		 * @return string
		 */
		public static function makeHtml($pagecount, $curpage, $href, $extend = [], $style = 's1', $tagnum = 5){
			if ($pagecount == 0) return '';
			
			$tagnum = (!isset($tagnum) || !is_numeric($tagnum) || $tagnum < 3) ? 5 : $tagnum;
			$style = empty($style) || !in_array($style, ['s1', 's2']) ? 's1' : $style;
			$extend = !is_array($extend) ? [] : $extend;
			
			//生成页码数组
			$numArr = [];
			if ($pagecount <= $tagnum){
				for ($i = 1; $i <= $pagecount; $i++){
					$numArr[] = $i;
				}
			}else{
				$numArr[0] = 1;
				//前段
				if ($curpage < ceil($tagnum / 2)){
					for ($i = 2; $i < $tagnum; $i++) {
						$numArr[] = $i;
					}
					$numArr[] = $pagecount;
				//后段
				}else if(($pagecount - $curpage) < ceil($tagnum / 2)){
					for ($i = $pagecount - ($tagnum - 1) + 1 ; $i <= $pagecount; $i++) {
						$numArr[] = $i;
					}
				//中间段
				}else{
					$beforeNum = (int)(($tagnum - 3) / 2);
					$afterNum = $tagnum - $beforeNum - 3;
					//组装当前页码前页码数组
					for ($i = 0; $i < $beforeNum; $i++){
						$numArr[] =  $curpage - $beforeNum + $i;
					}
					$numArr[] = $curpage;
					for ($i = 0; $i < $afterNum; $i++){
						$numArr[] =  $curpage + $i + 1;
					}
					$numArr[] = $pagecount;
				}
			}
			
			$htmlStr = '<div class="page-btn-'. $style .' gopage">'
					 . '<a href="' . self::_createHref($href, $curpage - 1, $curpage, $pagecount, $extend) 
					 . '" class="page-btn page-prev' . ($curpage == 1 ? " page-dis" : "") . '">&lt;</a>'
					 . self::_loopToPage($numArr, $href, $curpage, $pagecount, $extend)
					 . '<a href="' . self::_createHref($href, $curpage + 1, $curpage, $pagecount, $extend) 
					 . '" class="page-btn page-next' . ($curpage == $pagecount ? " page-dis" : "") . '">&gt;</a>' 
					 . '</div>';
			return $htmlStr;
		}
		
		/**
		 * 生成翻页Href
		 * 
		 * @param
		 * @return 
		 */
		private static function _createHref($href, $gopage, $curpage, $pagecount, $extend){
			//越界处理
			if ($gopage < 1 || $gopage > $pagecount || $gopage == $curpage){
				return "javascript:";
			}
			//添加页码
			$href .= $gopage;
			//添加其他参数
			foreach ($extend as $key => $value){
				$href .= '&'. $key .'='. $value;
			}
			return $href;
		}
		
		/**
		 * 循环生成页码
		 * 
		 * @param
		 * @return 
		 */
		private static function _loopToPage($numArr, $href, $curpage, $pagecount, $extend){
			$htmlStr = '';
			$tmp = $numArr[0];
			foreach ($numArr as $gopage){
				if ($gopage - $tmp > 1){
					$htmlStr .= '<span class="page-omit">...</span>';
				}
				$tmp = $gopage;
				$htmlStr .= '<a href="'. self::_createHref($href, $gopage, $curpage, $pagecount, $extend) 
					 	  . '" class="page-btn' . ($curpage == $gopage ? " page-cur page-dis" : "") . '">' . $gopage . '</a>';
			}
			
			return $htmlStr;
		}
	}
