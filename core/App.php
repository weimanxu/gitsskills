<?php
/** 
 * 初始类
 * 
 * 项目启动时调用App::run()加载配置，及实例化官方功能类，App作为全局使用
 * 
 */
class App {
    //是否开启DEBUG模式
    private static $isDebug;
    //是否开发环境
    private static $isDev;
    //是否已经执行
    private static $done = false;
    //文件加载列表
    private static $requireFiles = [];
    
    //必要组件
    public static $request;
    public static $response;
    public static $view;
    public static $html;
    public static $session;
    //public static $i18n;
    public static $debug;
    
    public static function RUN () {
        //防止重复执行
        if (self::$done) return ;
        self::$done = true;
        
        //TODO
        date_default_timezone_set("Asia/Shanghai");
        
        //初始化环境
        $deployEnv = self::loadConf('/deployEnv');
        if($deployEnv == 'dev') error_reporting(E_ALL);
        else error_reporting(E_ERROR);
        
        //加载核心组件
        self::requireFile('/core/lib/Request.php');
        self::requireFile('/core/lib/Response.php');
        self::requireFile('/core/lib/View.php');
        self::requireFile('/core/lib/Html.php');
        self::requireFile('/core/lib/Session.php');
        //self::requireFile('/core/lib/I18n.php');
        self::requireFile('/core/lib/Debug.php');
        
        self::$request  = Request::_getInstance();
        self::$response = Response::_getInstance();
        self::$view     = View::_getInstance();
        self::$html 	= Html::_getInstance();
        self::$session  = Session::_getInstance();
        //self::$i18n     = I18n::_getInstance();
        self::$debug    = Debug::_getInstance();
        
        //注册autoload函数，自动加载class、lib
        spl_autoload_register(function($className){
            //class
            if (preg_match('/Class$/', $className)){
            	$file = PATH .'/class/' . $className . '.php';
                if(!file_exists($file)){
                	throw new Exception('class not exist : ' . $className);
                }
                self::requireFile('/class/'. $className . '.php');
                
            }else {
            	$file = PATH .'/lib/' . $className . '.php';
	            //lib
	            if(!file_exists($file)){
	                throw new Exception('lib not exist : '. $className);
	            }
	            self::requireFile('/lib/'. $className . '.php');
            }
            
            
            //debug
            if (self::isDebug()) {
            	App::$debug->setDebug([
	            	'file' => $file,
	            	'class' => $className,
            	]);
            }
            
        });
        
        //拦截器、路由器
        self::requireFile('/core/InterceptorLoader.php');
        self::requireFile('/core/RouterLoader.php');
        
        try {
            InterceptorLoader::RUN();
            RouterLoader::RUN();
        }catch (Exception $e){
            self::errorAction($e->getMessage(), $e);
        }
    }
    
    /**
     * 读取配置
     * 
     * @param string $key
     * @param boolean $reload
     * @return mixed
     */
    public static function loadConf ($key = '/', $reload = false) {
        static $conf = [];
        //当$reload为true时重新读取配置文件
        if (empty($conf) || $reload){
            //加载公共conf
            $conf = require(PATH . '/config/config.php');
            
            //检查必要项目
            if (!in_array($conf['deployEnv'], ['dev', 'pro']))
                self::errorAction('deployEnv has not been set or not been right！');
            self::$isDev = ($conf['deployEnv'] === 'dev');
            
            if (empty($conf['secretKey']))
                self::errorAction('secretKey has not been set！');
            
            //加载环境conf
            $conf = array_merge($conf, require(PATH . '/config/config_' . $conf['deployEnv'] . '.php'));
            
            self::$isDebug = $conf['debug'];
        }
        
        $keyArr = explode('/', $key);
        $tmpConf = $conf;
        while (($tmpKey = array_shift($keyArr)) !== null){
            if ($tmpKey == '')
                continue;
            
            if (isset($tmpConf[$tmpKey]))
                $tmpConf = $tmpConf[$tmpKey];
            else
                return null;
        }
        return $tmpConf;
    }
    
    /**
     * 是否DEBUG
     * 
     * @return boolean
     */
    public static function isDebug () {
        return self::$isDebug === true;
    }
    
    /**
     * 是否开发环境
     *
     * @return boolean
     */
    public static function isDev () {
    	return self::$isDev;
    }
    
    /**
     * 错误处理
     * 
     * 处理在App、InterceptorLoader、RouterLoader时期产生的错误
     * 
     */
    public static function errorAction ($message, $e = null) {
        if (App::isDebug() && ($e instanceof Exception)){
        	$traces = array_reverse($e->getTrace());
//         	echo '<pre>';
//          print_r($traces);
            var_dump($traces);
        }
        die ($message);
    }
    
    /**
     * 加载文件， 相对PATH目录
     * 
     */
    public static function requireFile ($file) {
        array_push(self::$requireFiles, $file);
        require_once PATH . $file;
    }
    
    /**
     * 不允许实例化
     * 
     */
    private function __construct () {
       
    }
}
