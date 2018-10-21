<?php
/**
 * APP自定义Controller基类
 *
 */
class CustomBaseController extends BaseController{
    
    /**
     * 判断是否登陆
     *
     * @return boolean
     * @author Ymj Created at 2017年6月27日
     */
    public function checkLogin () {
        $uSession = Session::_getInstance('uSession')->getSession();
        if (isset($uSession['uid'])) {
            return true;
        }
        return false;
    }
    
    
    /**
     * 判断是否登录，如登录获取用户信息
     *
     * @return 
     * @author Ymj Created at 2017年6月27日
     */
    public function checkLoginAndGetInfo () {
        $userClass = new UserClass();
        $uSession = Session::_getInstance('uSession')->getSession();
        if (isset($uSession['uid'])) {
            $userInfo = $userClass->getUserInfoById($uSession['uid']);
            if (!empty($userInfo))
                return $userInfo;
        }
        return false;
        
    }
    
    /**
     * 判断是否登陆，若没登录，重定向至首页
     *
     * @return array
     * @author Ymj Created at 2017年6月27日
     */
    public function checkLoginAndRedirect () {
        $userClass = new UserClass();
        $uSession = Session::_getInstance('uSession')->getSession();
        if (!isset($uSession['uid'])){
            header('location: /');
            exit;
            
        }
        
        $userInfo = $userClass->getUserInfoById($uSession['uid']);
        if (empty($userInfo)){
            Session::_getInstance('uSession')->cleanSession()->sendSession();
            header('location: /');
            exit;
        }
        return $userInfo;
    }
    
    /**
     * 异步请求前置处理，获取用户信息，若未登录，返回错误JSON
     *
     * @return array
     * @author Ymj Created at 2017年6月27日
     */
    public function checkLoginAsync () {
        $userClass = new UserClass();
        $uSession = Session::_getInstance('uSession')->getSession();
        if (isset($uSession['uid'])) {
            $userInfo = $userClass->getUserInfoById($uSession['uid']);
            if (!empty($userInfo))
                return $userInfo;
        }
        App::$response->simpleJsonError('請先登錄');
    }
    
    /**
     * 获取session用户信息
     *
     * @return array
     * @author Ymj Created at 2017年6月27日
     */
    public function getUserInfoFromSession () {
        return Session::_getInstance('uSession')->getSession();
    }
    
    /**
     * 获取session中用户ID信息
     *
     * @return string || null
     * @author Ymj Created at 2017年6月27日
     */
    public function getUserIdFromSession () {
        $uSession = Session::_getInstance('uSession')->getSession();
        if (isset($uSession['uid'])) {
            return $uSession['uid'];
        }
        return null;
    }
    
    /**
     * 页面初始化工作
     * 
     * @param 
     * @return
     * @author Ymj Create At 2017年6月28日
     */
    public function pageInit () {
        $userInfo = $this->checkLoginAndGetInfo();
        if ($userInfo){
            $userInfo['btc_usable'] = Format::formatNumber($userInfo['btc_usable'],8,false,true);
            $userInfo['btc_freeze'] = Format::formatNumber($userInfo['btc_freeze'],8,false,true);
            $userInfo['eth_usable'] = Format::formatNumber($userInfo['eth_usable'],8,false,true);
            $userInfo['eth_freeze'] = Format::formatNumber($userInfo['eth_freeze'],8,false,true);
        }
        App::$view->setData('userInfo', $userInfo);
        return $userInfo;
    }
    
}