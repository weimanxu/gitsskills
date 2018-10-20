<?php
class RouterLoader {
    //是否已经执行
    private static $done        = false;
    
    //路由器配置
    private static $routerConf  = [];
    
    //路由根根目录
    private static $root        = '';
    
    //路由器子目录
	private static $dir 		= '';
	private static $Controller	= '';
	private static $function 	= '';
	
	//基类
	private static $baseController;
	
	//controller实例
	private static $ControllerObject	= null;
	
	/**
	 * 启动路由
	 * 
	 */
	static public function RUN () {
	    //防止重复执行
	    if (self::$done) return ;
	    self::$done = true;
	    
		//加载配置
		$routerMap = require(PATH . '/config/router_map.php');
		$host      = App::$request->getHost();
		
		if (isset($routerMap[$host])) {
		      $map = $routerMap[$host];
		      if (!isset($map['root']) || !isset($map['conf']) || !file_exists(PATH . '/config/router/' . $map['conf'])){
		          new Exception('Router config error : ' . $host);
		      }
		      self::$root       = $map['root'];
		      self::$routerConf = require(PATH . '/config/router/' . $map['conf']);
		}else{
		    self::$routerConf = require(PATH . '/config/router/default.php');
		}
		
		//匹配路由表，查找不到匹配项时候执行普通模式
		if (!self::_execRouterMap()){
			self::_execRouterNomal();
		}
		
        App::requireFile('/core/BaseController.php');
        self::$baseController = 'BaseController';
        
        //加载用户自定义基类
        $baseController = App::loadConf('/baseController');
        if (!empty($baseController)){
            App::requireFile('/controller/' . $baseController . '.php');
            self::$baseController = $baseController;
            
            //不允许直接访问基类
            if (strtolower(self::$Controller) == strtolower($baseController)){
                self::notFoundAction();
                exit ;
            }
        }
        
        App::requireFile('/core/BaseClass.php');
        if (!empty(App::loadConf('/baseClass'))){
            App::requireFile('/class/' . App::loadConf('/baseClass') . '.php');
        }
        
		//组装control目录
		$controlPath = '/controller/'
		             . (self::$root === "" ? "" : self::$root . '/')
		             . (self::$dir === "" ? "" : self::$dir . '/')
		             . self::$Controller . '.php';
		
		if(!file_exists(PATH . $controlPath)){
			self::notFoundAction();
			exit ;
		}
		App::requireFile($controlPath);
		
		//私有函数不允许执行
		if (self::$function[0] === '_') {
		    self::notFoundAction();
		    exit ;
		}
		
		self::$ControllerObject = new self::$Controller();
		$function = self::$function;
		
		//判断函数是否存在
		if (!method_exists(self::$ControllerObject, $function)){
			self::notFoundAction();
			exit ;
		}
		
		//debug
		App::$debug->setDebug([
				'file' 		=> PATH . $controlPath,
				'class' 	=> self::$Controller,
				'method' 	=> $function,
		]);
		
		self::$ControllerObject->$function();
	}
	
	/**
	 * 匹配路由表
	 * 
	 * @return boolean
	 */
	private static function _execRouterMap(){
		foreach (self::$routerConf as $map){
			if (preg_match($map['pattern'], App::$request->getRouterPath(), $matchs)){
				self::$Controller = ucfirst($map['Controller']) . 'Controller';
				self::$function  = ucfirst($map['function']) . 'Action';
				
				//路由器子目录
				if (isset($map['dir']))
				    self::$dir = $map['dir'];
				
				//设置参数，需要通过App::$request->getParam获取
				if (isset($map['params'])){
				    array_shift($matchs);
				    for($i = 0, $len = count($map['params']); $i < $len; $i++){
				        App::$request->setParam($map['params'][$i], !isset($matchs[$i]) ? '' : $matchs[$i]);
				    }
				}
				//已找到匹配项，告知不用执行普通的目录模式匹配
				return true;
			}
		}
		return false; 
	}
	
	/**
	 * 执行普通的路由模式
	 * 
	 * @return void
	 */
	private static function _execRouterNomal(){
		$pattern = '/^(?:\/(\w[\w\/\-]*?))?(?:\/([\w\-]+))?(?:\/([\w\-]+))?\/?$/i';
		$flag = preg_match($pattern, App::$request->getRouterPath(), $matchs);
		
		if (!$flag) {
		    self::redirectToIndex();
		}
		
		$count = count($matchs);
		if ($count <= 1){
			self::$Controller = 'IndexController';
			self::$function  = 'IndexAction';
		}else if ($count == 2){
			self::$Controller = ucfirst($matchs[1]) . 'Controller';
			self::$function  = 'IndexAction';
		}else if ($count == 3){
			self::$Controller = ucfirst($matchs[1]) . 'Controller';
			self::$function  = ucfirst($matchs[2]) . 'Action';
		}else if($count == 4) {
		    self::$dir       = $matchs[1];
		    self::$Controller = ucfirst($matchs[2]) . 'Controller';
		    self::$function  = ucfirst($matchs[3]) . 'Action';
		}else{
		    throw new Exception('Nomal Router Error : ' . App::$request->getRouterPath());
		}
	}
	
	/**
	 * not found
	 * 
	 */
	private static function notFoundAction () {
	    if (self::$ControllerObject != null){
	        self::$ControllerObject->notFound();
	    }else {
	        $baseControl = new self::$baseController();
	        $baseControl->notFound();
	    }
	    exit;
	}
	
	/**
	 * redirect to index
	 * 
	 */
	private static function redirectToIndex () {
	    header('Location: /');
	    exit;
	}
}
