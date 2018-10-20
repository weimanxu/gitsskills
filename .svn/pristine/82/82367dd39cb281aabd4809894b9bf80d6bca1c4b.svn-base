<?php
/**
 * 微信
 *
 */
class Weixin {
    
    private $appid;
    private $secret;
    
    public function __construct($appid = null, $secret = null) {
        if($appid == null || $secret == null) {
            $appid  = App::loadConf('weixin/appid');
            $secret = App::loadConf('weixin/secret');
        }
        
        $this->appid  = $appid;
        $this->secret = $secret;
    }
    
    /**
     * 获取微信普通access_token
     *
     * @return string | null
     * @author Ymj Created at 2016年6月17日
     */
    public function getAccessToken($appid = null, $secret = null) {
        //从数据库中读取access_token，并在中控服务器上实现access_token自动刷新
        $redis = RedisCli::getRedisIns();
        $accessToken = $redis->get('weixin:access_token');
        if (empty($accessToken)){
            //access_token不存在
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->appid . '&secret=' . $this->secret;
            $accessToken = file_get_contents($url);
            $accessToken = json_decode($accessToken, true);
            if (isset($accessToken['access_token'])){
                //一个小时过期
                $redis->setex('weixin:access_token', 3600, $accessToken['access_token']);
                $redis->close();
                return $accessToken['access_token'];
            }
            $redis->close();
            return null;
        }else{
            $redis->close();
            return $accessToken;
        }
    }
    
    /**
     * 获取微信H5 jsapi_ticket
     *
     * @return string | null
     * @author Ymj Created at 2016年6月17日
     */
    public function getJsapiTicket() {
        //从数据库中读取jsapi_ticket，并在中控服务器上实现jsapi_ticket自动刷新
        //此为普通access_token，不是网页授权access_token
        $redis = RedisCli::getRedisIns();
        $ticket = $redis->get('weixin:jsapi_ticket');
        
        if (empty($ticket)){
            $accessToken = $this->getAccessToken();
            if($accessToken == null){
                $redis->close();
                return null;
            }
            
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $accessToken . '&type=jsapi';
            $ticket = file_get_contents($url);
            $ticket = json_decode($ticket, true);
            if (isset($ticket['ticket'])){
                //一个小时过期
                $redis->setex('weixin:jsapi_ticket', 3600, $ticket['ticket']);
                $redis->close();
                return $ticket['ticket'];
            }
            $redis->close();
            return null;
        }else{
            $redis->close();
            return $ticket;
        }
        
    }
    
    /**
     * 获得下载多媒体URL
     *
     * @param  string $mediaId
     * @return string
     * @author Ymj Created at 2016年6月17日
     */
    public function getMediaDownloadUrl($mediaId) {
        $accessToken = $this->getAccessToken();
        return 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=' . $accessToken . '&media_id=' . $mediaId;
    }
    
    /**
     * 获取用户openId
     *
     * @return array
     * @author Ymj Created at 2016年6月17日
     */
    public function getUserOpenId ($code) {
        if (empty($code) || !is_string($code))
            return null;
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appid . '&secret=' . $this->secret . '&code=' . $code . '&grant_type=authorization_code';
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        if (isset($result['openid']))
            return $result;
        
        return null;
    }
    
    /**
     * 拉取用户信息 snsapi_userinfo
     *
     * @param  string $accessToken
     * @param  string $openId
     * @return 
     * @author Ymj Created at 2016年6月17日
     */
    public function getUserInfo ($accessToken, $openId) {
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $accessToken . '&openid=' . $openId . '&lang=zh_CN';
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        if (isset($result['openid']))
            return $result;
        
        return null;
        
    }
    
    /**
     * 生成获取snsapi_base信息重定向url
     * 
     * @param  string $redirect
     * @return string
     * @author Ymj Created at 2016年6月17日
     */
    public function getRedirectBaseUrl ($redirect = null) {
        if ($redirect == null) {
            $redirect = $this->cleanUrlParam();
        }
        $redirect = urlencode($redirect);
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->appid . '&redirect_uri=' . $redirect . '&response_type=code&scope=snsapi_base&state=base#wechat_redirect';
        return $url;
    }
    
    /**
     * 生成获取snsapi_userinfo信息重定向url
     *
     * @param  string $redirect
     * @return string
     * @author Ymj Created at 2016年6月17日
     */
    public function getRedirectUserinfoUrl ($redirect = null) {
        if ($redirect == null) {
            $redirect = $this->cleanUrlParam();
        }
        $redirect = urlencode($redirect);
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->appid . '&redirect_uri=' . $redirect . '&response_type=code&scope=snsapi_userinfo&state=userinfo#wechat_redirect';
        return $url;
    }
    
    /**
     * 清除URL中code state参数并返回
     * 
     * @param  string $url
     * @return string
     * @author Ymj Created at 2016年6月26日
     */
    public function cleanUrlParam ($url = null) {
        if ($url == null)
            $url = App::$request->getBaseUrl() . $_SERVER['REQUEST_URI'];
        
        $urlArr = explode('?', $url);
        if (count($urlArr) == 1)
            return $urlArr[0];
        
        $queryArr = explode('&', $urlArr[1]);
        $outQueryArr = [];
        foreach ($queryArr as $query) {
            if (stripos($query, 'code=') === 0 || stripos($query, 'state=') === 0){
                continue;
            }
            $outQueryArr[] = $query;
        }
        
        $queryStr = '';
        if (!empty($outQueryArr))
            $queryStr = '?' . implode('&', $outQueryArr);
            
        return $urlArr[0] . $queryStr;
    }
    
    /**
     * 判断是否微信浏览器
     *
     * @return bool
     * @author Ymj Created at 2016年6月26日
     */
    public function isWeixin (){
        return boolval(preg_match('/micromessenger/i', App::$request->getUserAgent()));
    }
}
