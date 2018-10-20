<?php
class UserClass extends CustomBaseClass {
    
    /**
     * 根据id,type获取信息（state = 1）
     *
     * @param string $table
     * @param string $type
     * @param int $id
     * @param array $field
     * @return
     * @author XJW Created at 2017年7月25日
     */
    public function getInfoByIdAndType($table, $type,$id,$field = []){
        if (!is_numeric($id)){
            return null;
        }
        if (!in_array($type, App::loadConf('app/currency'))){
            return null;
        }
        $info = Mysql::_getInstance($table)
        ->sqlHead($field)
        ->sql('WHERE `user_id` = "' . $id . '" AND `type` ="'.$type.'" AND `state` = 1 LIMIT 1', true)
        ->queryOne()
        ->getResult();
        return $info;
    }
    
    /**
     * 根据id,user_id获取信息（state = 1）
     *
     * @param string $table
     * @param int $id
     * @param int $user_id
     * @param array $field
     * @return
     * @author XJW Created at 2017年7月27日
     */
    public function getWithdrawRecordByIdAndUserId($table, $id,$user_id,$field = []){
        if (!Validator::isInt($id)){
            return null;
        }
        if (!Validator::isInt($user_id)){
            return null;
        }
        $info = Mysql::_getInstance($table)
        ->sqlHead($field)
        ->sql('WHERE `id`='.$id.' AND`user_id` = "' . $user_id . '" AND `withdraw_state`=1 AND `state` = 1 ', true)
        ->query()
        ->getResult();
        return $info;
    }
    
    /**
     * 根据uid获取信息
     *
     * @param string $table
     * @param int $uid
     * @param array $field
     * @return
     * @author XJW Created at 2017年7月31日
     */
    public function getInfosByUid($table, $uid,$field = []){
        if (!is_numeric($uid)){
            return null;
        }
        $info = Mysql::_getInstance($table)
        ->sqlHead($field)
        ->sql('WHERE `user_id` = ' . $uid , true)
        ->query()
        ->getResult();
        return $info;
    }
    
    
    /**
     * 根据id获取信息（state = 1）
     *
     * @param string $table
     * @param int $id
     * @param array $field
     * @return
     * @author XJW Created at 2017年7月25日
     */
    public function getInfosById($table, $id,$field = []){
        if (!is_numeric($id)){
            return null;
        }
        $info = Mysql::_getInstance($table)
        ->sqlHead($field)
        ->sql('WHERE `user_id` = "' . $id . '" AND `state` = 1 ORDER BY `createtime` DESC', true)
        ->query()
        ->getResult();
        return $info;
    }
    
    
    /**
     * 根据id,$type,$n,$m获取分页信息（state = 1）
     *
     * @param string $table
     * @param int $id
     * @param string $type
     * @param array $field
     * @param array $n
     * @param array $m
     * @return
     * @author XJW Created at 2017年7月25日
     */
    public function getPageListData($table, $id,$type,$n,$m,$field = []){
        if (!is_numeric($id)){
            return null;
        }
        if (!in_array($type, App::loadConf('app/currency'))){
            return null;
        }
        if (!Validator::isInt($n) || !Validator::isInt($m)){
            return null;
        }
        $info = Mysql::_getInstance($table)
        ->sqlHead($field)
        ->sql('WHERE `user_id` = "' . $id . '" AND `type` ="'.$type.'"  AND `state` = 1  ORDER BY `createtime` DESC LIMIT '.$n.','.$m, true)
        ->query()
        ->getResult();
        return $info;
    }
    
    /**
     * 根据用户ID获取实名验证信息
     *
     * @param  int      $id
     * @param  array    $field
     * @param  boolean  $useCache
     * @return array || null
     * @author Xjw Created at 2017年7月25日
     */
    public function getVerifyInfoByUserId ($id, $field = [], $useCache = true) {
        static $cache = [];
        if (!is_numeric($id)){
            return null;
        }
        if ($useCache && isset($cache[$id])){
            return $cache[$id];
        }
        
        $verifyInfo = Mysql::_getInstance('verify_record')
        ->sqlHead($field)
        ->sql('WHERE `user_id` = "' . $id . '" AND `state` = 1', true)
        ->queryOne()
        ->getResult();
    
        $cache[$id] = $verifyInfo;
        return $verifyInfo;
    }
    
    
    /**
     * 根据用户ID获取用户信息
     *
     * @param  int      $id
     * @param  array    $field
     * @param  boolean  $useCache
     * @return array || null
     * @author Ymj Created at 2017年6月27日
     */
    public function getUserInfoById ($id, $field = [], $useCache = true) {
        static $cache = [];
        if (!is_numeric($id)){
            return null;
        }
    
        if ($useCache && isset($cache[$id])){
            return $cache[$id];
        }
    
        $user = Mysql::_getInstance('user')
              ->sqlHead($field)
              ->sql('WHERE `id` = "' . $id . '" AND `state` = 1', true)
              ->queryOne()
              ->getResult();
    
        $cache[$id] = $user;
        return $user;
    }
    
    /**
     * 根据email用户用户信息
     * 
     * @param  string   $email
     * @param  array    $field
     * @param  boolean  $useCache
     * @return array || null
     * @author Ymj Create At 2017年6月27日
     */
    public function getUserInfoByEmail ($email, $field = [], $useCache = true) {
        static $cache = [];
        if (!Validator::isEmail($email)){
            return null;
        }
    
        if ($useCache && isset($cache[$email])){
            return $cache[$email];
        }
    
        $user = Mysql::_getInstance('user')
              ->sqlHead($field)
              ->sql('WHERE `email` = "' . addslashes($email) . '" AND `state` = 1', true)
              ->queryOne()
              ->getResult();
    
        $cache[$email] = $user;
        return $user;
    }
    
    /**
     * 验证邮箱
     * 
     * @param $uid		//用户ID
     * @param $email	//用户邮箱
     * @return boolean
     * @author Ymj Create At 2017年6月27日
     */
    public function setValifyEmail($uid, $email) {
    
        //数据有误
        if (!Validator::isInt($uid) || !Validator::isEmail($email)){
            return false;
        }
        $mysql = Mysql::_getInstance('user');
        $state = $mysql->sql('UPDATE `user` SET `email_verify` = 1')
               ->where("`id` = ". $uid ." AND `email` = '". $email ."' AND `state` = 1")
               ->exec()
               ->getState();

        return $state;
    }
    
    /**
    * 发送邮箱验证码
    * @param string $email        
    * @param int $userId        
    * @return 
    * @author XJW Create At 2017年8月2日
    */
    public function sendEmailVelifyCode($email,$userId){
        $params = [
            'uid' 		=> $userId,
            'webName' 	=> App::loadConf('webName'),
            'email' 	=> $email,
            'rand' 		=> mt_rand(102300, 999999),
        ];
        
        $subject = App::loadConf('webName').'-获取验证码';
        $emailClass = SendEmail::_getInstance();
        $token = $emailClass->getEmailToken($params['uid'], $params['email']);
        
        //打开输出控制缓冲
        ob_start();
        ob_implicit_flush(false);
         
        //layout里面的content
        extract($params, EXTR_OVERWRITE);
         
        require(PATH .'/lib/Email/templates/SendCode.php');
         
        $html_content = ob_get_clean();
         
        $result         = SendEmail::_getInstance()->send($params['email'], $subject, $html_content);
        $result['rand'] = $params['rand']; 
        
        return $result;
    }
}