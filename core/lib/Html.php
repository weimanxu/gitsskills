<?php
class Html {
    private static $Instance = null;
    
    public static function _getInstance () {
    	if (self::$Instance === null){
    		self::$Instance = new self();
    	}
    	return self::$Instance;
    }
    
    private function __construct () {
        
    }
    
    /**
     * 转跳到指定页
     * @param $url	//链接
     */
    static public function gopage($url = ''){
    	if (!empty($url)) header('Location: '. $url);
    	else header('Location: /');
    	exit;
    }
    
    /**
     * HTML转义
     *
     * @param  string
     * @return string
     */
    static public function htmlEncode ($str) {
        return htmlspecialchars($str);
    }
}