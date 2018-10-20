<?php
/**
 * Session类
 * 采用Cookie存放用户信息，不应该放置大量数据
 * 
 * Example
 * 
 * $value = Session::_getInstance()->getSession('key')
 * Session::_getInstance()->setSession('key', 'value')->sendSession()
 */
class Session {
	private static $Instance = null;
	
	private static $conf = [
			'sessionName' => 'uSession',               //默认SESSION位置，用于存放用户登录数据
			'timeOut'	  => 0                         //默认过期时间，单位秒，为0时关闭浏览器即过期
	];
	
	private $sessionName;
	private $timeOut;
	private $secretKey;
	private $currentDomain;
	
	private $data = [];
	
	public static function _getInstance($sessionName = ''){
		if (self::$Instance === null){
			self::$Instance = new self();
		}
		if ($sessionName == '') $sessionName = self::$conf['sessionName'];
		self::$Instance->select($sessionName);
		return self::$Instance;
	}
	
	/**
	 * 切换session
	 * 
	 * @param String $sessionName
	 * @return Session
	 */
	public function select($sessionName){
		$this->sessionName = $sessionName;
		$this->_decodeData();
		return $this;
	}
	
	/**
	 * 构造函数
	 * 
	 * @param
	 * @return 
	 */
	private function __construct(){
	    $this->timeOut         = self::$conf['timeOut'];
	    $this->secretKey       = App::loadConf('/secretKey');
	    $this->currentDomain   = App::$request->getHost(); 
	}
	
	/**
	 * 设置过期时间
	 * 
	 * @param
	 * @return Session
	 */
	public function setTimeOut($time = ''){
		if ($time === '')
			$this->timeOut = self::$conf['timeOut'];
		else $this->timeOut = $time;
		return $this;
	}
	
	/**
	 * 清空Cookie
	 * 
	 * @param
	 * @return void
	 */
	private function _cleanCookie(){
		setcookie($this->sessionName, '', time() - 3600, '/', $this->currentDomain);
	}
	
	/**
	 * 解析数据
	 * 
	 * @param
	 * @return void
	 */
	private function _decodeData(){
		if (empty($this->data[$this->sessionName])){
			if (!isset($_COOKIE[$this->sessionName])){
				$this->data[$this->sessionName] = [];
				return ;
			}
			$arr = is_string($_COOKIE[$this->sessionName]) ? explode('|', $_COOKIE[$this->sessionName]) : [];
			//签名验证
			if (count($arr) != 2 || hash_hmac('md5', $arr[0], $this->secretKey) != $arr[1]){
				//清空cookie
				$this->_cleanCookie();
				return ;
			}
			try {
				//AES ECB模式 解密
				$tmp = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->secretKey, base64_decode($arr[0]), MCRYPT_MODE_ECB));
				$this->data[$this->sessionName] = json_decode($tmp, true);
			}catch (Exception $e){
				$this->_cleanCookie();
			}
		}
	}
	
	/**
	 * 设置session
	 * 
	 * @param string $key
	 * @param string $value
	 * @return Session
	 */
	public function setSession($key, $value){
		$this->data[$this->sessionName][$key] = $value;
		return $this;
	}
	
	/**
	 * 设置session，整块设置
	 * 
	 * @param string $value
	 * @return Session
	 */
	public function setSessionBlock($value){
	    $this->data[$this->sessionName] = $value;
	    return $this;
	}
	
	/**
	 * 获取session
	 * 
	 * @param string $key
	 * @return mixed
	 */
	 public function getSession($key = ''){
	 	if ($key == '') return $this->data[$this->sessionName];
	 	if (!isset($this->data[$this->sessionName][$key]))
	 		return null;
	 	return $this->data[$this->sessionName][$key];
	 }
	 
	 /**
	  * 清空session
	  * 
	  * @param $key
	  * @return Session
	  */
	 public function cleanSession($key = ''){
	 	if ($key == ''){
	 		$this->data[$this->sessionName] = [];
	 	}else unset($this->data[$this->sessionName][$key]);
	 	return $this;
	 }
	 
	 /**
	  * 使session生效
	  * 
	  * @return void
	  */
	 public function sendSession(){
	 	$expire = ($this->timeOut ? time() + $this->timeOut : 0);
	 	if (empty($this->data[$this->sessionName])){
	 		$this->_cleanCookie();
	 		return $this;
	 	}
	 	//AES ECB模式 加密
	 	$tmp = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->secretKey, json_encode($this->data[$this->sessionName]), MCRYPT_MODE_ECB);
	 	$val = base64_encode($tmp);
	 	$val = $val . '|' . hash_hmac('md5', $val, $this->secretKey);
	 	
	 	//开启httponly
	 	setcookie($this->sessionName, $val, $expire, '/', $this->currentDomain, false, true);
	 	return $this;
	 }
}