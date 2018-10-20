<?php
class I18n {
    const LANGUAGE_CONF = [
        'zh-CN' => ['text' => '简体中文'],
        'zh-HK' => ['text' => '繁體中文'],
        'zh-TW' => 'zh-HK',
        'en-US' => ['text' => 'English']
    ];
    private static $Instance = null;
    private static $default_lang = null;
    public static $language;
		
	private $lang_cookie = 'lang';
    
    public static function _getInstance () {
    	if (self::$Instance === null){
    		self::$Instance = new self();
    	}
    	
    	//初始化语言
    	self::$Instance->setLang();
    	
    	return self::$Instance;
    }
    
    private function __construct () {
    	//默认语言
    	if (self::$default_lang === null) self::$default_lang = App::loadConf('language');
    }
    
    /**
     * 设置语言
     * @return 
     */
    public function setLang () {
    	$lang      = App::$request->getParam('lang');
    	$langConf  = I18n::LANGUAGE_CONF;
    	//存在lang参数
    	if (!empty($lang)) {
    		//不支持该语言
    		if (!isset($langConf[$lang])) {
    			$lang = self::$default_lang;
    			//cookie优先
	    		if (isset($_COOKIE[$this->lang_cookie]) && isset($langConf[$_COOKIE[$this->lang_cookie]])) {
	    			$lang = $_COOKIE[$this->lang_cookie];
	    		
	    		//header 信息
	    		}elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
	    			$head_lang = trim(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], ',')));
	    			
	    			if (isset($langConf[$head_lang]))
	    				$lang = $head_lang;
	    		}
    		}
    		
    	//get | post 不存在lang参数
    	}else {
    		//cookie优先
    		if (isset($_COOKIE[$this->lang_cookie]) && isset($langConf[$_COOKIE[$this->lang_cookie]])) {
    			$lang = $_COOKIE[$this->lang_cookie];
    		
    		//header 信息
    		}elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    			
    			$dotpos = strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], ',');
    			if ($dotpos === false) {
    				$head_lang = trim($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    			}else {
    				$head_lang = trim(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, $dotpos));
    			}
    			
    			if (isset($langConf[$head_lang])) {
    				$lang = $head_lang;
    			}else {
    				$lang = I18n::$default_lang;
    			}
    		}else {
    			$lang = I18n::$default_lang;
    		}
    	}
    	
    	//适配多对一
    	if (is_string($langConf[$lang]))
    	    $lang = $langConf[$lang];
    	 
    	//当cookie不存在lang || 当前语言跟cookie不一致时
    	if (!isset($_COOKIE[$this->lang_cookie]) || $_COOKIE[$this->lang_cookie] !== $lang)
    		setcookie($this->lang_cookie, $lang, time() + 3600 * 24 * 30, '/', null);
	    
	    //当前语言
    	self::$language = $lang;
    	return self::$Instance;
    }
    
    /**
     * 获取当前语言
     * 
     */
    public function getLang () {
        return self::$language;
    }
    
    /**
     * 获取当前语言text
     *
     */
    public function getLangText () {
        $languageConf = self::LANGUAGE_CONF;
        return $languageConf[self::$language]['text'];
    }
    
    /**
     * 获取语言配置
     * 
     */
    public function getLanguageConf () {
        return self::LANGUAGE_CONF;
    }
    
    /**
     * 语言转换
     *
     * t('app', '你好{username}，欢迎来到{where}', ['username' => 'joker', 'where' => '中国'], 'en-US')
     * => {username}， {where}会被 params对应的参数替换
     *
     * @param $category		//分类名，作为文件名
     * @param $message		//原语言
     * @param $params		//$message里面要替换的数据
     * @param $language		//指定目标语言
     * @return string
     */
    public static function t($category, $message, $params = [], $language = NULL){
    	
    	$language = $language ? $language : self::$language;
    	
    	//当前语言跟默认语言一致
    	if ($language === self::$default_lang) return $message;
    	
    	//指定语言不存在
    	$sourceMessages = static::_loadMessages($category, $language);
    	if (!$sourceMessages || !isset($sourceMessages[$message])) 
    	    return $message;
    	
    	//替换翻译语言里面的变量
    	$t_message = $sourceMessages[$message];
    	$t_message = static::_format($t_message, $params);
    	return $t_message;
    }
    
    
    /**
     * 加载语言文件，并返回语言数组键值对
     * @param $category		//文件名
     * @param $language		//要翻译的语言
     * @return array
     */
    private static function _loadMessages($category, $language){
    	static $langs = [];
    	
    	//已经加载过相同的语言文件，直接返回
    	if (isset($langs[$language][$category])) return $langs[$language][$category];
    	
    	
    	$langPath = PATH .'/languages/'. $language .'/'. $category .'.php';
    	if (!is_file($langPath)) return false;
    	
    	$langs[$language][$category] = require($langPath);
    	
    	return $langs[$language][$category];
    }
    
    
    /**
     * 替换翻译里面的变量
     * @param string $message		//含有变量的目标语言
     * @param array $params 		//变量键值对
     * @return string
     */
    private static function _format($message, $params){
    	$p = [];
    	foreach ($params as $name => $value) {
    		$p['{' . $name . '}'] = $value;
    	}
    
    	return strtr($message, $p);
    }
}