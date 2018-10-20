<?php
class InterceptorLoader {
    //是否已经执行
    private static $done = false;
    
    /**
     * 加载拦截器
     * 
     */
    public static function RUN () {
        //防止重复执行
        if (self::$done) return ;
        self::$done = true;
        
        App::requireFile('/core/Interceptor.php');
        
        $interceptorConf = require(PATH . '/config/interceptor_map.php');
        foreach ($interceptorConf as $item) {
            $conditionFn     = '';
            $conditionResult = null;
            $actionFn        = '';
            $actionResult    = null;
            
            if (is_string($item)){
                
                $interceptor = $item;
                
            }elseif (is_array($item)) {
                if (!isset($item['condition']) || !isset($item['action'])) {
                	//debug
                	self::setLastDebug(__METHOD__, __LINE__ + 2);
                	
                	throw new Exception('interceptor error: param not set!');
                }
                
                $condition = explode('|', $item['condition'] , 2);
                
                if (count($condition) != 2) {
                	//debug
                	self::setLastDebug(__METHOD__, __LINE__ + 2, ['condition' => $item['condition']]);
                	
                	throw new Exception('interceptor error : ' . $item['condition']);
                }
                
                //拦截条件
                switch ($condition[0]){
                    case 'host' :
                        $conditionResult = !!preg_match($condition[1], App::$request->getHost());
                        break;
                    case 'url' :
                        $conditionResult = !!preg_match($condition[1], App::$request->getOriginPath());
                        break;
                    case 'ip' :
                        $conditionResult = !!preg_match($condition[1], App::$request->getUserIp());
                        break;
                    case 'useragent' :
                        $conditionResult = !!preg_match($condition[1], App::$request->getUserAgent());
                        break;
                    default :
                    	
                    	//debug
                    	self::setLastDebug(__METHOD__, __LINE__ + 2, ['condition' => $item['condition']]);
                    	
                        throw new Exception('interceptor type error : ' . $item['condition']);
                }
                
                //条件不成立时退出
                if ($conditionResult === false)
                    continue;
                
                if (is_callable($item['action'])){
                    $actionResult = $item['action']();
                    
                    if ($actionResult === false)
                        //拦截器返回false，终止执行往后的拦截器
                        break;
                    else 
                        continue;
                }
                
                //action不为callback
                $interceptor = $item['action'];
            }
            
            //需要加载拦截器
            $interceptorArr = explode('/', $interceptor);
            
            if (count($interceptorArr) != 2) {
            	//debug
            	self::setLastDebug(__METHOD__, __LINE__ + 2, ['interceptor' => $interceptor]);
            	
                throw new Exception('interceptor error : ' . $interceptor);
            }
            
            $interceptor = $interceptorArr[0] . 'Interceptor';
            
            if (!file_exists(PATH . '/interceptor/' . $interceptor . '.php')){
            	//debug
            	self::setLastDebug(__METHOD__, __LINE__ + 2, ['interceptor' => $interceptor]);
            	
                throw new Exception('interceptor not exist : ' . $interceptor);
            }
            
            App::requireFile('/interceptor/' . $interceptor . '.php');
            
            $class = new ReflectionClass($interceptor);
            
            //根据$conditionResult是否等null判断是否需要调用conditionFn
            if ($conditionResult === null){
                $conditionFn     = $interceptorArr[1] . 'Condition';
                if (!$class->hasMethod($conditionFn)) {
                	//debug
                	self::setLastDebug(__METHOD__, __LINE__ + 2, ['conditionFn' => $conditionFn]);
                	
                    throw new Exception('interceptor condition not exist : ' . $conditionFn);
                }
                
                $conditionResult = $interceptor::$conditionFn();
            }
            
            //需要执行拦截器
            if ($conditionResult === true){
                $actionFn = $interceptorArr[1] . 'Action';
                if (!$class->hasMethod($actionFn)) {
                	
                	//debug
                	self::setLastDebug(__METHOD__, __LINE__ + 2, ['actionFn' => $actionFn]);
                	
                	throw new Exception('interceptor action not exist : ' . $actionFn);
                }
                
                $actionResult = $interceptor::$actionFn();
                
                if ($actionResult === false)
                    //拦截器返回false，终止执行往后的拦截器
                    break;
                else 
                    continue;
                
            }
        }
    }
    
    private static function setLastDebug($method, $line, $args = []) {
    	//debug
    	App::$debug->setTracesEnd([
	    	'file' 		=> __FILE__,
	    	'method' 	=> $method,
	    	'args' 		=> $args,
	    	'line' 		=> $line,
	    	'time' 		=> microtime(true),
    	]);
    }
}