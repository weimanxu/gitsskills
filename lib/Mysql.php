<?php
	class Mysql {
		private static $Instance 	= null;
		
		private static $config		= null;				//存放数据库配置
		private $rw_separate 		= false;			//是否读写分离
		
		private $rw_db 				= null;				//当前主数据库连接
		private $ro_db 				= null;				//当前从数据库只读连接
		
		private $tableName			= null;				//当前操作表
		private $sql 				= null;				//SQL语句
		private $result 			= null;				//上一次查询结果
		private $state				= null; 			//上一次SQL执行状态
		private $lastId				= null;				//上一次插入记录返回的主键值
		
		private $readPrefix 		= ['SELE', 'SHOW', 'DESC'];
		
		
		/**
		 * 单实例
		 * 
		 * @return Mysql
		 */
		public static function _getInstance ($tableName = '') {
			if (self::$Instance === null){
				self::$Instance = new self();
			}
			self::$Instance->selectTable($tableName);
			return self::$Instance;
		}
		
	    /**
	     * 架构函数 读取数据库配置信息
	     * 
	     * @param boolean $rw_separate		//读写分离
	     */
	    public function __construct($rw_separate = true) {
	    	//加载配置
	    	if (self::$config === null){
	    		self::$config = App::loadConf('mysql');
	    	}
	    	$this->rw_separate = $rw_separate;
	    }
	    
	    /**
	     * 析构函数
	     * 
	     */
	    public function __destruct () {
	    	//断开数据库连接
	    	$this->ro_db = null;
	    	$this->rw_db = null;
	    }
	    
	    
	    /**
	     * 返回PDO对象
	     * @param $type		//masters[默认] -> 主库实例，slaves -> 从库实例
	     * @return $obj | null
	     */
	    public function getPdo($type = 'masters'){
	    	if (!in_array($type, ['masters', 'slaves'])) throw new Exception('Param value error!');
	    	
	    	if ($type === 'masters') {
	    		if (!($this->rw_db instanceof PDO)) $this->initConnect($type);
	    		
	    		return $this->rw_db;
	    	}else{
	    		if (!($this->ro_db instanceof PDO)) $this->initConnect($type);
	    		
	    		return $this->ro_db;
	    	}
	    }
	    
	    /**
	     * 连接Mysql
	     * 
	     * @param unknown $conf
	     * @return PDO|$obj
	     */
	    private function connect ($conf) {
	    	$dsn = 'mysql:host='. $conf['host'] .';port='. $conf['port'] .';dbname='. $conf['database'];
	    	try {
	    		$db = new PDO($dsn, $conf['user'], $conf['password'], [PDO::ATTR_PERSISTENT => false]);
	    		//返回字段名总是小写
	    		$db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
	    		//编码
	    		$db->query("set names utf8mb4");
	    		return $db;
	    	} catch (PDOException $e){
	    	    
	    		return null;
	    	}
	    }
	    
	    /**
	     * 获取连接配置并调用连接
	     * 
	     * @param  string $type		//masters[默认] -> 主库实例，slaves -> 从库实例
	     * @return void
	     */
		private function initConnect ($type = 'masters'){
			static $tmpSlavesConf = null;
			static $tmpMastersConf = null;
			
			if (!is_string($type) || !in_array($type, ['masters', 'slaves'])) 
				throw new Exception('Db param value error!');
			
			//连接从数据库
			if ($type == 'slaves'){
			    //判断从数据库是否已经链接
			    if ($this->ro_db != null)
			        return ;
			    
				$sflag = false;
				
				if ($tmpSlavesConf === null) $tmpSlavesConf = self::$config['slaves'];
				
				$slaveSize = count($tmpSlavesConf);
				if ($slaveSize >= 1) {
					for($slaveTimes = 0; $slaveTimes < $slaveSize; $slaveTimes++)
					{
						//从从数据库中随机弹出元素
						$index = array_rand($tmpSlavesConf);
						
						$pdo = $this->connect($tmpSlavesConf[$index]);
						if ($pdo === null){
							unset($tmpSlavesConf[$index]);
							continue ;
						}
						
						$sflag = true;
						$this->ro_db = $pdo;
						break ;
					}
				}
				
				//从库全部失败，直接连接主库
				if (!$sflag){
				    //先判断主库是否已经链接
			        $this->initConnect('masters');
					$this->ro_db = $this->rw_db;
				}
				
			}else{
			    //判断主数据库是否已经链接
			    if ($this->rw_db != null)
			        return ;
			    
				$mflag = false;
				if ($tmpMastersConf === null) $tmpMastersConf = self::$config['masters'];
				
				$masterSize = count($tmpMastersConf);
				if ($masterSize < 1)
					throw new Exception('No M_Db!');
				
				for($masterTimes = 0; $masterTimes < $masterSize; $masterTimes++ )
				{
					//从主数据库中随机弹出元素
					$index = array_rand($tmpMastersConf);
					
					$pdo = $this->connect($tmpMastersConf[$index]);
					if ($pdo === null){
						unset($tmpMastersConf[$index]);
						continue ;
					}
					
					$mflag = true;
					$this->rw_db = $pdo;
					break ;
				}
				
				//主库全部失败
				if (!$mflag){
					throw new Exception('DB connect error!');
				}
			}
		}
		
		
		/**
		 * 切换表
		 * 
		 * @param string $table
		 * @return Mysql
		 */
		public function selectTable($tableName){
			!empty($tableName) && $this->tableName = $tableName;
			return $this;
		}
		
		/**
		 * 开启事务（主库）
		 *
		 * @return
		 * @author Mysql
		 */
		public function beginTransaction(){
		    //建立连接
		    if (!($this->rw_db instanceof PDO)) $this->initConnect();
		    $this->rw_db->beginTransaction();
		    return $this;
		}
		
		/**
		 * 提交事务（主库）
		 *
		 * @return Mysql
		 */
		public function commit(){
		    if($this->rw_db == null){
		        return $this;
		    }
		    $this->rw_db->commit();
		    return $this;
		}
		
		/**
		 * 回滚事务（主库）
		 *
		 * @return Mysql
		 */
		public function rollBack(){
		    if($this->rw_db == null){
		        return $this;
		    }
		    $this->rw_db->rollBack();
		    return $this;
		}
		
		/**
		 * 组装sql
		 * @param string $sql
		 * @param bool	 $append	//是否采用追加模式
		 * @return Mysql
		 * @author Ymj Create at 2013-12-15
		 */
		public function sql($sql, $append = false){
			if ($append && $this->sql != null){
				$this->sql .= $sql;
			}
			else $this->sql = $sql;
			return $this;
		}
		
		/**
		 * 生成查询SQL头部
		 * @param array $field
		 * @return Mysql
		 * @author Ymj Create at 2013-12-15
		 */
		public function sqlHead($field = [], $tableName = ''){
			if ($tableName == '') $tableName = $this->tableName;
			$sql = "SELECT ";
			if (empty($field))
				$sql .= "*";
			else if(is_array($field)){
				foreach ($field as $f => $as){
					if(is_numeric($f)){
						$sql .= "`$as`, ";
					}else{
						$sql .= "`$f` AS `$as`, ";
					}
				}
				$sql = substr($sql, 0, -2);
			}else if(is_string($field)){
				$sql .= $field;
			}
			$sql .= " FROM `" . $tableName . "` ";
			$this->sql = $sql;
			return $this;
		}
		
		/**
		 * 设置查询语句的where子语句
		 * @param string | array $where		//字符串|数组均不用带WHERE
		 * @return string 空字符串 || where子语句
		 * @author Li.hq Create at 2013-07-17
		 * 		   Modified by Ymj at 2013-12-15
		 */
		public function where($where = array()){
			if ($this->sql != null){
				$sql = '';
				if(!empty($where)){
					$sql .= ' WHERE';
					
					if(is_array($where)){
						foreach ($where as $f => $v){
							if(is_numeric($f)) continue;
							if(1 == preg_match('/^!=/', $v)){
								$sql .= ' `' . $f . '` != "' . substr($v, 2) . '" and';
							}else{
								$sql .= ' `' . $f . '` = "' . $v . '" and';
							}
						}
						$sql = substr($sql, 0, -3);
					}else if(is_string($where)){
						$sql .= ' ' . $where;
					}
				}
				$this->sql .= $sql;
			}
			return $this;
		}
		
		/**
		 * 查询多条（从库查询）
		 * 
		 * @param $type
		 * @return Mysql
		 */
		public function query($type = PDO::FETCH_ASSOC){
			
			if ($this->rw_separate && $this->checkSqlPrefix()) {
				
				//建立连接
				if (!($this->ro_db instanceof PDO)) $this->initConnect('slaves');
				
				
				//debug
				if (App::isDebug()) {
					App::$debug->setDebug([
						'file' 		=> '开始执行SQL: 【'. $this->sql .'】',
						'class' 	=> __CLASS__,
						'method' 	=> __FUNCTION__,
					]);
				}
				
				
				$stmt = $this->ro_db->query($this->sql);
				$this->result = $stmt->fetchAll($type);
				
				
				//debug
				if (App::isDebug()) {
					App::$debug->setDebug([
						'file' 		=> 'SQL执行结束。',
						'line' 		=> __LINE__ - 6,
					]);
				}
			}
			return $this;
		}
		
		/**
		 * 强制主库做查询
		 * 
		 * @param 
		 * @return Mysql
		 */
		public function masterQuery($type = PDO::FETCH_ASSOC){
			//建立连接
			if (!($this->rw_db instanceof PDO)) $this->initConnect();
			
			
			//debug
			if (App::isDebug()) {
				App::$debug->setDebug([
					'file' 		=> '开始执行SQL: 【'. $this->sql .'】',
					'class' 	=> __CLASS__,
					'method' 	=> __FUNCTION__,
				]);
			}
			
			
			$stmt = $this->rw_db->query($this->sql);
			$this->result = $stmt->fetchAll($type);
			
			
			//debug
			if (App::isDebug()) {
				App::$debug->setDebug([
					'file' 		=> 'SQL执行结束。',
					'line' 		=> __LINE__ - 6,
				]);
			}
			
			return $this;
		}
		
		/**
		 * 查询单条（从库查询）
		 * 
		 * @param $type
		 * @return Mysql
		 */
		public function queryOne($type = PDO::FETCH_ASSOC){
			
			if ($this->rw_separate && $this->checkSqlPrefix()) {
				
				//建立连接
				if (!($this->ro_db instanceof PDO)) $this->initConnect('slaves');
				
				
				//debug
				if (App::isDebug()) {
					App::$debug->setDebug([
						'file' 		=> '开始执行SQL: 【'. $this->sql .'】',
						'class' 	=> __CLASS__,
						'method' 	=> __FUNCTION__,
					]);
				}
				
				$stmt = $this->ro_db->query($this->sql);
				$this->result = $stmt->fetch($type);
				
				//debug
				if (App::isDebug()) {
					App::$debug->setDebug([
						'file' 		=> 'SQL执行结束。',
						'line' 		=> __LINE__ - 6,
					]);
				}
			}
			return $this;
		}
		
		/**
		 * 强制主库查询单记录
		 * 
		 * @param $type
		 * @return Mysql
		 */
		public function masterQueryOne($type = PDO::FETCH_ASSOC){
			//建立连接
			if (!($this->rw_db instanceof PDO)) $this->initConnect();
			
			
			//debug
			if (App::isDebug()) {
				App::$debug->setDebug([
					'file' 		=> '开始执行SQL: 【'. $this->sql .'】',
					'class' 	=> __CLASS__,
					'method' 	=> __FUNCTION__,
				]);
			}
			
			
			$stmt = $this->rw_db->query($this->sql);
			$this->result = $stmt->fetch($type);
			
			//debug
			if (App::isDebug()) {
				App::$debug->setDebug([
					'file' 		=> 'SQL执行结束。',
					'line' 		=> __LINE__ - 6,
				]);
			}
			
			return $this;
		}
		
		/**
		 * 检查sql是否为查询语句
		 * 
		 * @return boolean
		 */
		private function checkSqlPrefix() {
			$prefix = strtoupper(substr(ltrim($this->sql), 0, 4));
			if (in_array($prefix, $this->readPrefix)) return true;
			return false;
		}
		
		/**
		 * 执行UPDATE,DELETE,INSERT操作（不允许做查询操作）
		 * 
		 * @param string $sql
		 * @return boolean || int
		 */
		public function exec($sql = ""){
			!empty($sql) && $this->sql = $sql;
			
			//查询操作直接返回
			if ($this->checkSqlPrefix()){
				return $this;
			}
			
			//建立连接
			if (!($this->rw_db instanceof PDO)) $this->initConnect();
			
			
			//debug
			if (App::isDebug()) {
				App::$debug->setDebug([
					'file' 		=> '开始执行SQL: 【'. $this->sql .'】',
					'class' 	=> __CLASS__,
					'method' 	=> __FUNCTION__,
				]);
			}
			
			
			$this->state = !!$this->rw_db->exec($this->sql);
			
			//debug
			if (App::isDebug()) {
				App::$debug->setDebug([
					'file' 		=> 'SQL执行结束。',
					'line' 		=> __LINE__ - 6,
				]);
			}
			
			return $this;
		}
		
		/**
		 * 更新记录
		 * @param array $record
		 * @return boolean
		 */
		public function update($record){
			if (empty($record['id']) || !is_numeric($record['id'])) return false;
			$sql = "UPDATE `" . $this->tableName . "` SET ";
			foreach ($record as $key => $val){
				if ($key != 'id'){
				    if ($val === null){
				        $sql .= "`" . $key . "` = NULL,";
				    }else{
				        $sql .= "`" . $key . "` = '" . addslashes($val) . "',";
				    }
				}
			}
			$sql = substr($sql, 0, -1);
			$sql .= " WHERE `id` = '" . $record['id'] . "'";
			$this->sql = $sql;
			$this->exec();
			return $this;
		}
		
		/**
		 * 插入记录
		 * @param array $record
		 * @return boolean || int
		 */
		public function insert($record){
			//组装SQL
			$this->insertSql($record);
			
			$this->exec();
			if ($this->state)
				$this->lastId = $this->rw_db->lastInsertId();
			return $this;
		}
		
		/**
		 * 组装INSERT SQL
		 * @param array $record
		 * @return 
		 */
		public function insertSql($record) {
			$sql = "INSERT INTO `" . $this->tableName . "` SET ";
			foreach ($record as $key => $val){
				if ($key != 'id'){
				    if ($val === null){
				        $sql .= "`" . $key . "` = NULL,";
				    }else{
				        $sql .= "`" . $key . "` = '" . addslashes($val) . "',";
				    }
				}
			}
			$sql = substr($sql, 0, -1);
			$this->sql = $sql;
			return $this;
		}
		
		/**
		 * 返回全部状态
		 * @param
		 * @return array
		 * @author Ymj Create at 2013-12-15
		 */
		public function getAll(){
			return array(
				'sql' 		=> $this->sql,
				'result' 	=> $this->result,
				'state' 	=> $this->state,
				'lastId' 	=> $this->lastId
			);
		}
		
		/**
		 * 返回上一次执行的SQL
		 * @param
		 * @return string
		 * @author Ymj Create at 2013-12-15
		 */
		public function getSql(){
			return $this->sql;
		}
		
		
		/**
		 * 返回查询结果
		 * @param 
		 * @return array 
		 * @author Ymj Create at 2013-12-15
		 */
		public function getResult(){
			return $this->result;
		}
		
		/**
		 * 返回查询得到的总数
		 * @param
		 * @return 
		 * @author Ymj Create at 2014-1-15
		 */
		public function getCount(){
			if ($this->result == null) return 0;
			if (!isset($this->result['count'])) return 0;
			return (int)$this->result['count'];
		}
		
		/**
		 * 返回执行状态
		 * @param
		 * @return boolean
		 * @author Ymj Create at 2013-12-15
		 */
		public function getState(){
			return $this->state;
		}
		
		/**
		 * 返回最后插入记录主键值
		 * @return int
		 * @author Ymj Create at 2013-12-15
		 */
		public function getLastId(){
			return $this->lastId;
		}
		
		/**
		 * 清空上一次的执行状态及数据
		 * @param
		 * @return Mysql
		 * @author Ymj Create at 2013-12-15
		 */
		public function clean(){
			$this->sql = null;
			$this->result = null;
			$this->state = null;
			$this->lastId = null;
			return $this;
		}
	}
