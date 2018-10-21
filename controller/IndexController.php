<?php

class IndexController extends CustomBaseController {
    
    /**
     * 首页
     *
     * @return 
     * @author Ymj Created at 2017年6月26日
     */
    public function IndexAction () {
        //页面初始化工作
        $this->pageInit();
        
        $timeStamp = time();
        $mysql     = Mysql::_getInstance();
        //获取广告信息
        $adSql    = 'SELECT * FROM `advertisement` WHERE `state` = 1 AND `open_state` = 1'
                    .' AND `begintime` < '.$timeStamp.' AND `endtime` > '.$timeStamp.' ORDER BY `sort`';
        $adResult = $mysql->sql($adSql)->query()->getResult();
                
        //获取公告信息
        $announSql    = 'SELECT * FROM `announcement` WHERE `state` = 1 AND `open_state` = 1'
                        .' ORDER BY `release_time` DESC LIMIT 5';
        $announResult = $mysql->sql($announSql)->query()->getResult();
        
        //获取项目信息
        $projectClass = new ProjectClass();
        $sort = ' ORDER BY `begintime` LIMIT 3';
        //获取进行中ICO
        $goingList = $projectClass->listProjectByType(1,[],$sort);
        foreach ($goingList as &$v1){
            //去多余的零
            $v1['btc_target'] = Format::formatNumber($v1['btc_target'],8);
            $v1['btc_done']   = Format::formatNumber($v1['btc_done'],8);
            $v1['btc_min']    = Format::formatNumber($v1['btc_min'],8);
            $v1['eth_target'] = Format::formatNumber($v1['eth_target'],8);
            $v1['eth_done']   = Format::formatNumber($v1['eth_done'],8);
            $v1['eth_min']    = Format::formatNumber($v1['eth_min'],8);
        }
        //获取即将到来ICO
        $waitList = $projectClass->listProjectByType(2,[],$sort);
        foreach ($waitList as &$v2){
            //去多余的零
            $v2['btc_target'] = Format::formatNumber($v2['btc_target'],8);
            $v2['btc_done']   = Format::formatNumber($v2['btc_done'],8);
            $v2['btc_min']    = Format::formatNumber($v2['btc_min'],8);
            $v2['eth_target'] = Format::formatNumber($v2['eth_target'],8);
            $v2['eth_done']   = Format::formatNumber($v2['eth_done'],8);
            $v2['eth_min']    = Format::formatNumber($v2['eth_min'],8);
        }
        //获取已完成ICO
        $doneList = $projectClass->listProjectByType(3,[],' ORDER BY '.time().'- `endtime`  LIMIT 3');
        foreach ($waitList as &$v3){
            //去多余的零
            $v3['btc_target'] = Format::formatNumber($v3['btc_target'],8);
            $v3['btc_done']   = Format::formatNumber($v3['btc_done'],8);
            $v3['btc_min']    = Format::formatNumber($v3['btc_min'],8);
            $v3['eth_target'] = Format::formatNumber($v3['eth_target'],8);
            $v3['eth_done']   = Format::formatNumber($v3['eth_done'],8);
            $v3['eth_min']    = Format::formatNumber($v3['eth_min'],8);
        }
        
        
        App::$view->layout = 'main';
        App::$view->loadView('index',[
            'sponsor_info' => $adResult,
            'announ_info'  => $announResult,
            'goingList'    => $goingList,
            'waitList'     => $waitList,
            'doneList'     => $doneList,
        ]);
        
    }
    
    /**
    * 获取项目新闻消息
    * 
    * @param         
    * @return 
    * @author XJW Create At 2017年6月30日
    */
    public function GetProjectNewsAction(){
        $projectNews = Mysql::_getInstance('project_news')
        ->sqlHead()
        ->where('`state` = 1')
        ->query()
        ->getResult();
        foreach ($projectNews as $k=>&$v){
            $v['publish_time'] = date('Y-m-d', strtotime($v['publish_time']));
        }
        App::$response->simpleJsonSuccessWithData($projectNews);
    }
    
    /**
     * 登录
     * 
     * @param 
     * @return
     * @author Ymj Create At 2017年6月27日
     */
    public function LoginAction () {
        //判断是否已经登录
        if($this->checkLoginAndGetInfo()){
            App::$response->gotoPage('/');
        }
        
        App::$view->layout = 'login-register';
        App::$view->loadView('login');
    }
    
    /**
     * 提交登录
     * 
     * @param 
     * @return
     * @author Ymj Create At 2017年6月27日
     */
    public function TakeLoginAction () {
        $username = trim(App::$request->getParam('username'));
        $password = App::$request->getParam('password');
        $remember = App::$request->getParam('remember');
         
        if (empty($username)){
            App::$response->simpleJsonError('username|用戶名不能為空');
        }
        
        if (empty($password)){
            App::$response->simpleJsonError('password|密碼不能為空');
        }
        
        $userClass = new UserClass();
        $userInfo = $userClass->getUserInfoByEmail($username);
        
        if(empty($userInfo)){
            App::$response->simpleJsonError('username|用戶名或密碼錯誤');
        }
        
        if($userInfo['password'] != sha1($password)){
            App::$response->simpleJsonError('username|用戶名或密碼錯誤');
        }
        
        if($userInfo['email_verify'] == '0'){
            App::$response->simpleJsonError('尚未完成郵箱驗證 <a href="javascript:;" data-email="' . $username . '" id="reVerify">點擊重新發送驗證郵件</a>');
        }
        
        //设置session
        $session = Session::_getInstance('uSession')->setSessionBlock([
            'uid' => $userInfo['id']
        ]);
        if($remember == '1'){
            $session->setTimeOut(3600 * 24 * 30);
        }
        $session->sendSession();

        Mysql::_getInstance('login_log')
                ->insert([
                    'user_id'=>$userInfo['id'],
                    'ip'     =>App::$request->getUserIp(),
                    'createtime'=>time()
                ])->getLastId();
        
        App::$response->simpleJsonSuccessWithData('/');
    }
    
    /**
     * 注册
     * 
     * 
     * @param 
     * @return
     * @author Ymj Create At 2017年6月27日
     */
    public function RegisterAction () {
        //判断是否已经登录
        if($this->checkLoginAndGetInfo()){
            App::$response->gotoPage('/');
        }
        App::$view->layout = 'login-register';
        App::$view->loadView('register');
    }
    
    /**
     * 提交注册
     * 
     * @param 
     * @return
     * @author Ymj Create At 2017年6月27日
     */
    public function TakeRegisterAction () {
        $email = trim(App::$request->getParam('email'));
        $password = App::$request->getParam('password');
        
        //暂时关闭注册
        //App::$response->simpleJsonError('当前平台已关闭注册');
        
        if(!Validator::isEmail($email)){
            App::$response->simpleJsonError('email|郵箱格式有誤');
        }
        
        $userClass = new UserClass();
        $userInfo = $userClass->getUserInfoByEmail($email);
        if(!empty($userInfo)){
            App::$response->simpleJsonError('email|該郵箱已經存在');
        }
        
        if (!Validator::isValidPassword($password)){
            App::$response->simpleJsonError('password|密碼為6-32位，區分大小寫');
        }
        
        //插入user表
        $userId = Mysql::_getInstance('user')->insert([
            'email' => $email,
            'password' => sha1($password),
            'createtime' => time()
        ])->getLastId();
        if(empty($userId)){
            App::$response->simpleJsonError('抱歉，註冊失敗了，請刷新頁面後重新嘗試');
        }
        //分配充值地址
        $RechargeClass = new RechargeClass();
        $RechargeClass->allocateAddress($userId, 'btc');
        $RechargeClass->allocateAddress($userId, 'eth');
        
        //发送注册验证邮件
        $this->sendRegisterEmail($email, $userId);
        
        //输出
        App::$response->printJson(["success" => true, "error" => '']);
        
        exit;
    }
    
    /**
     * 注销登录
     *
     * @return
     * @author Ymj Created at 2017年6月27日
     */
    public function LogoutAction () {
        Session::_getInstance('uSession')->cleanSession()->sendSession();
        App::$response->gotoPage('/');
    }
    
    /**
     * 重新发送验证邮件
     * 
     * @param 
     * @return
     * @author Ymj Create At 2017年6月27日
     */
    public function ReSendRegisterEmailAction () {
        $email = trim(App::$request->getParam('email'));
        
        if(!Validator::isEmail($email)){
            App::$response->simpleJsonError('email|郵箱格式有誤');
        }
        
        $userClass = new UserClass();
        $userInfo = $userClass->getUserInfoByEmail($email);
        if(empty($userInfo) || $userInfo['email_verify'] == '1'){
            App::$response->simpleJsonError('email|用戶不存在或已通過驗證');
        }
        
        $state = $this->sendRegisterEmail($email, $userInfo['id']);
        
        App::$response->simpleJsonForState($state, '抱歉，發送驗證郵件失敗');
    }
    
    /**
     * 发送注册邮件
     * 
     * @param 
     * @return
     * @author Ymj Create At 2017年6月27日
     */
    public function sendRegisterEmail ($email, $userId) {
        $params = [
            'uid' 		=> $userId,
            'webName' 	=> App::loadConf('webName'),
            'email' 	=> $email,
            'url' 		=> '',
        ];
        
        $subject = '欢迎注册' . App::loadConf('webName');
        $emailClass = SendEmail::_getInstance();
        $token = $emailClass->getEmailToken($params['uid'], $params['email']);
        
        $params['url'] = App::$request->getBaseUrl()
                       . '/index/validateEmail?email='. $params['email'] .'&tk='. urlencode($token)
                       . '&exp='. time() .'&rand='. mt_rand(103240, 945870);
        
       //打开输出控制缓冲
       ob_start();
       ob_implicit_flush(false);
       
       //layout里面的content
       extract($params, EXTR_OVERWRITE);
       
       require(PATH .'/lib/Email/templates/register.php');
       
       $html_content = ob_get_clean();
       
       $result = SendEmail::_getInstance()->send($params['email'], $subject, $html_content);
       
       return $result;
    }
    
    
    /**
     * 验证邮箱
     * 
     * @param 
     * @return
     * @author Ymj Create At 2017年6月27日
     */
    public function ValidateEmailAction() {
        $reqData = App::$request->getParam();
        $url = App::$request->getBaseUrl();
        $msg = '鏈接已失效，請重新發送驗證郵件！';
    
        if (empty($reqData) || empty($reqData['email']) || empty($reqData['tk']) || empty($reqData['exp']) || empty($reqData['rand'])) {
            //验证失败，退出
            $this->_outScript($msg, $url);
        }
    
        //解码失败
        $emailClass = SendEmail::_getInstance();
        $info = $emailClass->decryptToken($reqData['tk']);
        if (!$info) {
            //验证失败，退出
            $this->_outScript($msg, $url);
        }


        $gap = 3600;//1小时失效
        //解码成功		['uid' => 1000, 'email' => 'email@domain.com', 'time' => time()]
        if (
            Validator::isInt($info['uid'])
            &&
            Validator::isEmail($info['email'])
            &&
            !empty($info['time'])
            &&
            (($info['time'] + $gap) > time())
            ) {
                $uc = new UserClass();
                $state = $uc->setValifyEmail($info['uid'], $info['email']);
                if ($state) {
                    //验证成功，退出
                    $this->_outScript('郵箱驗證成功！', '/login');
                }
        }
        //验证失败
        $this->_outScript($msg, $url);
    }
    
    private function _outScript($msg, $url){
        echo '<script type="text/javascript">'
             . 'alert("'. $msg .'");'
             . 'window.location.href="' . $url . '";'
             .'</script>';
        exit;
    }
    
    /**
     * 忘记密码
     * 
     * @param 
     * @return
     * @author Ymj Create At 2017年6月27日
     */
    public function ForgetAction () {
        //判断是否已经登录
        if($this->checkLoginAndGetInfo()){
            App::$response->gotoPage('/');
        }
        
        App::$view->layout = 'login-register';
        App::$view->loadView('forget');
    }
    
    /**
     * 发送重置密码URL
     * 
     * @param 
     * @return
     * @author Ymj Create At 2017年6月27日
     */
    public function TakeFindPwdAction () {
        $email = trim(App::$request->getParam('email'));
        
        if(!Validator::isEmail($email)){
            App::$response->simpleJsonError('email|郵箱格式有誤');
        }
        
        $userClass = new UserClass();
        $userInfo = $userClass->getUserInfoByEmail($email);
        if(empty($userInfo)){
            App::$response->simpleJsonError('email|用戶不存在');
        }
        
        $params = [
            'uid' 		=> $userInfo['id'],
            'webName' 	=> App::loadConf('webName'),
            'email' 	=> $email,
            'url' 		=> '',
        ];
        
        $subject = '您正在重置' . App::loadConf('webName') . '密碼';
        $emailClass = SendEmail::_getInstance();
        $token = $emailClass->getEmailToken($params['uid'], $params['email']);
        
        $params['url'] = App::$request->getBaseUrl()
                        . '/index/reset?email='. $params['email'] .'&tk='. urlencode($token)
                        . '&exp='. time() .'&rand='. mt_rand(103240, 945870);
        
        //打开输出控制缓冲
        ob_start();
        ob_implicit_flush(false);
         
        //layout里面的content
        extract($params, EXTR_OVERWRITE);
         
        require(PATH .'/lib/Email/templates/findpwd.php');
         
        $html_content = ob_get_clean();
         
        $state = SendEmail::_getInstance()->send($params['email'], $subject, $html_content);
        
        App::$response->simpleJsonForState($state, '抱歉，發送郵件失敗');
    }
    
    /**
     * 打开重设密码URL
     * 
     * @param 
     * @return
     * @author Ymj Create At 2017年6月27日
     */
    public function ResetAction () {
        $reqData = App::$request->getParam();
        $url = App::$request->getBaseUrl();
        $msg = '鏈接已失效，請重新發送郵件！';
        
        if (empty($reqData) || empty($reqData['email']) || empty($reqData['tk']) || empty($reqData['exp']) || empty($reqData['rand'])) {
            //验证失败，退出
            $this->_outScript('參數錯誤', $url);
        }
        
        //解码失败
        $emailClass = SendEmail::_getInstance();
        $info = $emailClass->decryptToken($reqData['tk']);
        if (!$info) {
            //验证失败，退出
            $this->_outScript($msg, $url);
        }
        
        
        $gap = 3600;//1小时失效
        //解码成功		['uid' => 1000, 'email' => 'email@domain.com', 'time' => time()]
        
        if (
            Validator::isInt($info['uid'])
            &&
            Validator::isEmail($info['email'])
            &&
            !empty($info['time'])
            &&
            (($info['time'] + $gap) > time())
            ) {
                $userClass = new UserClass();
                $userInfo = $userClass->getUserInfoById($info['uid']);
                if (!empty($userInfo)) {
                    //验证成功，加载试图
                    App::$view->layout = 'login-register';
                    App::$view->loadView('resetpwd', [
                        'userInfo' => $userInfo
                    ]);
                }
        }
        //验证失败
        $this->_outScript($msg, $url);
    }
    
    /**
     * 提交修改密码
     * 
     * @param 
     * @return
     * @author Ymj Create At 2017年6月27日
     */
    public function TakeResetAction () {
        $reqData = App::$request->getParam();
        $msg = '鏈接已失效，請重新發送郵件！';
        
        if (empty($reqData) || empty($reqData['email']) || empty($reqData['tk']) || empty($reqData['exp']) || empty($reqData['rand'])) {
            //验证失败，退出
            App::$response->simpleJsonError('參數錯誤');
        }
        
        if (!Validator::isValidPassword($reqData['password'])){
            App::$response->simpleJsonError('password|密碼為6-32位，區分大小寫');
        }
        
        //解码失败
        $emailClass = SendEmail::_getInstance();
        $info = $emailClass->decryptToken($reqData['tk']);
        if (!$info) {
            //验证失败，退出
            App::$response->simpleJsonError('此鏈接已失效，請重新操作');
        }
        
        
        $gap = 3600;//1小时失效
        //解码成功		['uid' => 1000, 'email' => 'email@domain.com', 'time' => time()]
        if (
            Validator::isInt($info['uid'])
            &&
            Validator::isEmail($info['email'])
            &&
            !empty($info['time'])
            &&
            (($info['time'] + $gap) > time())
            ) {
                //通过验证
                $userClass = new UserClass();
                $userInfo = $userClass->getUserInfoById($info['uid']);
                if (empty($userInfo)) {
                    App::$response->simpleJsonError('用戶不存在');
                }
                //修改密码
                Mysql::_getInstance('user')->update([
                    'id' => $userInfo['id'],
                    'password' => sha1($reqData['password'])
                ]);
                App::$response->simpleJsonSuccess();
        }
        //验证失败
        App::$response->simpleJsonError('此鏈接已失效，請重新操作');
    }
    
}
