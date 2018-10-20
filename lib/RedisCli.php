<?php
	class RedisCli{
		
		public static $Instance = null;
		
		private $tableName;
		private $redis = null;
		
		public static function _getInstance($tableName = 0){
			if (self::$Instance === null){
				self::$Instance = new self($tableName);
			}else{
			    self::$Instance->selectTable($tableName);
			}
			return self::$Instance;
		}
		
		public static function getRedisIns($tableName = 0){
			$conf =  App::loadConf('redis');
			$redis = new Redis();
			$redis->connect($conf['host'], $conf['port']);
			$redis->select($tableName);
			return $redis;
		}
		
		private function __construct($tableName){
			$this->redis = new Redis();
			$this->connect();
			!empty($tableName) && $this->select($tableName);
		}
		
		/**
		 * 切换表
		 * @param string $table
		 * @return RedisCli
		 * @author Ymj Create at 2013-12-14
		 */
		public function selectTable($tableName){
			$this->tableName = $tableName;
			$this->select($tableName);
			return $this;
		}
		
		/**
		 * 获取Redis对象
		 * @param
		 * @return 
		 * @author Ymj Create at 2013-12-15
		 */
		public function getRedis(){
			return $this->redis;
		}
		
		/**
		 * 连接Redis服务器
		 * @param
		 * @return RedisCli
		 * @author Ymj Create at 2013-12-15
		 */
		public function connect($conf = array()){
			empty($conf) && $conf =  $conf =  App::loadConf('redis');
			$this->redis->connect($conf['host'], $conf['port']);
			return $this;
		}
		
		/**
		 * 关闭连接
		 * @param
		 * @return RedisCli
		 * @author Ymj Create at 2013-12-15
		 */
		public function close(){
			$this->redis->close();
			return $this;
		}
		
		/**
		 * 切换数据库
		 * @param int $index
		 * @return RedisCli
		 * @author Ymj Create at 2013-12-16
		 */
		public function select($index){
			$this->redis->select($index);
			return $this;
		}
		
		/**
		 * 批量查找数据
		 * @param array $keyList
		 * @return array
		 * @author Ymj Create at 2013-12-16
		 */
		public function getValues($keyList){
			
		}
	}