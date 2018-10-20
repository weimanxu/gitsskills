<?php
class Request {
    private static $Instance = null;
    
    public static function _getInstance () {
    	if (self::$Instance === null){
    		self::$Instance = new self();
    	}
    	return self::$Instance;
    }
    
    //用户IP
    private $userIp = null;
    
    //原URL目录部分，只读
    private $originPath;
    
    //路由URL目录部分，路由器根据此URL匹配，此目录可通过拦截器修改
    private $routerPath;
    
    //HOST
    private $host;
    
    //协议类型
    private $protocol;		//string  协议  http | https
    private $isProtocol;	//boolean 是否https请求
    
    //地理区域
    private $locale;
    
    //参数
    private $get    = []; 
    private $post   = [];
    private $params = [];
    
    private function __construct () {
        $pattern = '/^(\/[\w\/\-]*)/i';
        preg_match($pattern, $_SERVER['REQUEST_URI'], $matchs);
        
        //URL目录不区分大小写
        $this->originPath = $this->routerPath = strtolower($matchs[1]);
        
        //参数
        $this->get      = $_GET;
        $this->post     = $_POST;
        $this->params   = array_merge($this->get, $this->post);
        
        //HOST
        $colonPos   = strpos($_SERVER['HTTP_HOST'], ':');
        if ($colonPos){
            $this->host = substr($_SERVER['HTTP_HOST'], 0, $colonPos);
        }else{
            $this->host = $_SERVER['HTTP_HOST'];
        }
        
        //获取浏览器语言
        //$dotPos = strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], ',');
        //$this->locale = $dotPos ? trim(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, $dotPos)) : $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $this->locale = '';
        
        //协议类型
        $this->protocol   = isset($_SERVER['HTTPS']) ? "https" : "http";
        $this->isProtocol = isset($_SERVER['HTTPS']) ? true : false;
    }
    
    /**
     * 获取请求参数，不存在时返回null
     * 
     * @param string $key				//获取指定参数的值
     * @param boolean $htmlEscape		//是否转换html （默认不转换）
     * @param boolean $addSlashes		//字符' " \ 前加反斜杠 （默认不处理）
     * 
     * @return string | array | null
     */
    public function getParam ($key = null, $htmlEscape = false, $addSlashes = false) {
    	$value = null;
    	
    	//返回GET & POST所有参数（未过滤）
        if ($key === null) return $this->params;
        
        
        if (!isset($this->params[$key])) return $value;
        
        
        $value = $this->params[$key];
        
        //html 转换
        $htmlEscape && $value = htmlspecialchars($this->params[$key]);
        
        //字符' " \ 前加反斜杠
        $addSlashes && $value = addslashes($this->params[$key]);
        
        return $value;
    }
    
    /**
     * 获取请求中的JSON参数
     *
     * @return array
     */
    public function getJsonParam($key = null){
        static $params = null;
        if($params === null){
            $json = file_get_contents('php://input');
            $params = json_decode($json, true);
            if(empty($params)){
                $params = [];
            }
        }
        if ($key === null) return $params;
        
        if (!isset($params[$key])) {
            return null;
        }
        return $params[$key];
    }
    
    
    /**
     * 设置请求参数
     * 
     * @param string $key
     * @param string $value
     * @return void
     */
    public function setParam ($key, $value) {
        $this->params[$key] = $value;
    }
    
    /**
     * 获取用户IP地址
     * 
     * @return string
     */
    public function getUserIp () {
        if ($this->userIp == null){
            if(getenv("HTTP_CLIENT_IP")){
                $this->userIp = getenv("HTTP_CLIENT_IP");
            } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
                $this->userIp = getenv("HTTP_X_FORWARDED_FOR");
            } else {
                $this->userIp = $_SERVER["REMOTE_ADDR"];
            }
        }
        return $this->userIp;
    }
    
    /**
     * 获取accept-language
     * 
     * @return string
     */
    public function getAcceptLanguage () {
        return $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    }
    
    /**
     * 获取user-agent
     * 
     * @return string
     */
    public function getUserAgent () {
        return $_SERVER['HTTP_USER_AGENT'];
    }
    
    /**
     * 获取URL目录部分
     * 
     * @return string
     */
    public function getOriginPath () {
        return $this->originPath;
    }
    
    /**
     * 获取路由URL目录部分
     *
     * @return string
     */
    public function getRouterPath () {
        return $this->routerPath;
    }
    
    /**
     * 设置路由URL目录部分
     * 
     * @return void
     */
    public function setRouterPath ($routerPath) {
        $this->routerPath = $routerPath;
    }
    
    /**
     * 获取HOST
     * 
     */
    public function getHost () {
        return $this->host;
    }
    
    /**
     * 当前请求协议		http | https
     * @return string
     */
    public function getProtocol(){
    	return $this->protocol;
    }
    
    /**
     * 是否https请求
     * @return boolean true : https,  false : http
     */
    public function isHttps(){
    	return $this->isProtocol;
    }
    
    /**
     * 获取浏览器设置的语言
     * 
     * @return string
     */
    public function getHeaderLang() {
    	return $this->locale;
    }
    
    /**
     * 获取跟url地址
     * eg: 	http(s)://www.domain.com(:8080)
     * 
     * @return string
     */
    public function getBaseUrl() {
    	return $this->getProtocol() .'://'. $_SERVER['HTTP_HOST'];
    }
    
    /**
     * 判断是否微信客户端
     *
     * @return 
     * @author Ymj Created at 2016年8月25日
     */
    public function isWeixin() {
        return boolval(preg_match('/micromessenger/i', App::$request->getUserAgent()));
    }
}
