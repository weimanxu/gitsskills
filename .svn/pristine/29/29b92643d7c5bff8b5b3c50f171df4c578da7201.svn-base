<?php
class Response {
    private static $Instance = null;
    
    //返回类型
    private $contentType = null;
    
    public static function _getInstance () {
    	if (self::$Instance === null){
    		self::$Instance = new self();
    	}
    	return self::$Instance;
    }
    
    private function __construct () {
        
    }
    
    public function notFound () {
        header('HTTP/1.1 404 Not Found');
        exit;
    }
    
    /**
     * 设置JSON返回的头信息
     */
    public function headerJson(){
        if ($this->contentType == null){
            $this->contentType = 'application/json; charset=utf-8';
            header('Content-type: ' . $this->contentType);
        }
    }
    
    /**
     * 设置HTML返回的头信息
     */
    public function headerHtml(){
        if ($this->contentType == null){
            $this->contentType = 'text/html; charset=utf-8';
            header('Content-type: ' . $this->contentType);
        }
    }
    
    /**
     * 返回没登陆JSON提示
     * @param string $message
     */
    public function noLoginJson(){
        $this->headerJson();
    	echo self::toJson(["success" => false, "error" => "请先登录"]);
    	exit;
    }
    
    /**
     * 返回错误的JSON信息
     * @param string $error
     * @param int $errorCode
     */
    public function simpleJsonError($error = ''){
        $this->headerJson();
        $data = ["success" => false, "error" => $error];
    	echo self::toJson($data);
    	exit;
    }
    
    /**
     * 返回正确的JSON信息
     * @param string $error
     * @param int $errorCode
     */
    public function simpleJsonSuccess($error = ''){
        $this->headerJson();
        $data = ["success" => true, "error" => $error];
    	echo self::toJson($data);
    	exit;
    }
    
    /**
     * 返回正确的JSON信息并携带DATA
     * @param string $error
     * @param int $errorCode
     */
    public function simpleJsonSuccessWithData($data = '', $error = ''){
        $this->headerJson();
        $data = ["success" => true, "data" => $data, "error" => $error];
        echo self::toJson($data);
        exit;
    }
    
    /**
     * 根据state状态输出json
     *
     * @return
     * @author Ymj Created at 2017年3月1日
     */
    public function simpleJsonForState($state, $error = '操作失败'){
        if($state){
            $this->simpleJsonSuccess();
        }
        $this->simpleJsonError($error);
    }
    
    /**
     * 向客户端输出JSON
     * 
     */
    public function printJson ($data) {
        $this->headerJson();
        echo self::toJson($data);
        exit;
    }
    
    /**
     * 重定向
     * 
     */
    public function gotoPage ($url) {
        header("Location:" . $url);
        exit ;
    }
    
    /**
     * 强制不缓存
     */
    public function forbidCache () {
        header('Cache-Control:no-cache,must-revalidate');
        header('Pragma:no-cache');
    }
    
    /**
     * 向客户端输出JSON
     * 
     */
    public function toJson($array){
        return json_encode($array, JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 结束输出缓冲数据
     * @return [type] [description]
     */
    public function finishRequest(){
        ignore_user_abort(true);            // 客户端关闭程序继续执行
        if(function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();   // 响应完成, 关闭连接。只在FastCGI有效
        } else {
            header('X-Accel-Buffering: no');    // nginx 不缓存输出
            header('Content-Length: '. strlen(ob_get_contents()));
            header("Connection: close");
            header("HTTP/1.1 200 OK");
            ob_end_flush();
            flush();
        }
    }
}