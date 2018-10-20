<?php
class Debug {
    private static $Instance = null;
    
    //debug信息
    private $profiles   = [];
    private $tracesEnd  = [];
    //脚本开始时间
    private $startTime;
    //脚本结束时间
    private $endTime    = null;
    //Time栈，用于计时
    private $timeStack  = [];
    //内存使用信息
    private $memoryInfo = [];
    
    public static function _getInstance () {
        if (self::$Instance === null){
            self::$Instance = new self();
        }
        return self::$Instance;
    }
    
    private function __construct () {
        //设置开始时间
        if (defined(START_TIME)){
            $this->startTime = START_TIME;
        }else{
            $this->startTime = microtime(true);
        }
    }
    
    /**
     * 收集数据，为了统计数据更精准，应在开始处理DEBUG前调用此函数
     * 注意：重复调用此函数时，会重新统计数据
     *
     * @return void
     */
    public function collect () {
        //统计脚本运行时间
        $this->endTime = microtime(true);
        //统计内存使用信息
        $memory = memory_get_usage();
        $memoryMax = memory_get_peak_usage();
        $this->memoryInfo = [
                        'memory'     => $this->convertSize($memory),
                        'memoryMax'  => $this->convertSize($memoryMax)
                    ];
    }
    
    /**
     * throw exception 抛出的异常，最后一个加载信息
     * @param array		$info = [
     * 								'file' => '',
     * 								'line' => '',
     * 								'class' => '',
     * 								'method' => '',
     * 								'args' => '',
     * 								'time' => microtime(true),
     * 								'duration' => '',
     * 							]
     * @return
     */
    public function setTracesEnd($info) {
        $debuginfo = [
            'file' 		=> '',
            'line' 		=> '',
            'class' 	=> '',
            'method' 	=> '',
            'args' 		=> '',
            'time' 		=> microtime(true),
            'duration' 	=> '',
        ];
        
        $this->tracesEnd = array_merge($debuginfo, $info);
    }
    
    
    /**
     * 增加debug信息
     *
     * @param array		$info = [
     * 								'file' => '',
     * 								'line' => '',
     * 								'class' => '',
     * 								'method' => '',
     * 								'args' => '',
     * 								'time' => microtime(true),
     * 								'duration' => '',
     * 							]
     */
    public function setDebug($info = []){
        $debuginfo = [
            'file' 		=> '',
            'line' 		=> '',
            'class' 	=> '',
            'method' 	=> '',
            'args' 		=> '',
            'time' 		=> microtime(true),
            'duration' 	=> '',
        ];
         
        $debuginfo = array_merge($debuginfo, $info);
        
        array_push($this->profiles, $debuginfo);
    }
    
    
    /**
     * 获取debug信息
     * @return array
     */
    public function getDebug() {
        $i = 0;
        foreach ($this->profiles as $one) {
            static $prevTime = null;
            if ($prevTime === null)
                $prevTime = $one['time'];
            else {
                $duration = $this->timeGap($prevTime, $one['time']);
                $this->profiles[$i - 1]['duration'] = $duration;
                 
                $prevTime = $one['time'];
            }
    
            $i++;
        }
         
        return $this->profiles;
    }
    
    /**
     * 获取内存使用情况
     * 
     */
    public function getMemoryInfo () {
        if (empty($this->memoryInfo))
            $this->collect();
        return $this->memoryInfo;
    }
    
    /**
     * 获取include文件
     * 
     */
    public function getIncludeFiles () {
        return get_included_files();
    }
    
    /**
     * 获取脚本开始运行时间
     *
     * @param  
     * @return double
     */
    public function getScriptStartTime () {
        return $this->startTime;
    }
    
    /**
     * 获取脚本结束运行时间
     *
     * @param
     * @return double
     */
    public function getScriptEndTime () {
        if ($this->endTime == null)
            $this->collect();
        return $this->endTime;
    }
    
    /**
     * 获取脚本运行总时间，只统计一次
     *
     * @return double
     */
    public function getScriptRunTime () {
        return bcsub($this->getScriptEndTime(), $this->startTime, 4);
    }
    
    /**
     * 开始计时
     * 
     * @return void
     */
    public function startTime () {
        array_push($this->timeStack, microtime(true));
    }
    
    /**
     * 返回计时时间
     * 支持多个嵌套
     * 
     * @return double
     */
    public function endTime () {
        $startTime = array_pop($this->timeStack);
        if ($startTime == null)
            return 0;
        return bcsub(microtime(true), $startTime, 4);
    }
    
    /**
     * 返回时间间隔
     * @param $prevTime		//前一个时间
     * @param $nexTime		//后一个时间
     * @return float
     */
    private function timeGap($prevTime, $nexTime) {
        if (empty($nexTime) || empty($prevTime)) return '';
        return bcsub($nexTime, $prevTime, 4);
    }
    
    private function convertSize ($size){
        if (empty($size)) return '0 Kb';
        $unit = array('b','Kb','Mb','Gb','Tb','Pb');
    
        return round(
            $size / pow(
                1024,
                (
                    $i = floor(
                        log($size, 1024)
                    )
                )
            ),
            2
        ).' '.$unit[$i];
    }
}