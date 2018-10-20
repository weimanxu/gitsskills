<?php
/**
 * 微信小程序
 *
 */
class Wxapp {
    
    private $appid;
    private $secret;
    
    public function __construct($appid = null, $secret = null) {
        if($appid == null || $secret == null) {
            $appid  = App::loadConf('wxapp/appid');
            $secret = App::loadConf('wxapp/secret');
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
     * code 换取 session_key
     *
     * @return array || null
     * @author Ymj Created at 2017年3月7日
     */
    public function fetchSessionKey($code){
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $this->appid . '&secret=' . $this->secret . '&js_code=' . $code . '&grant_type=authorization_code';
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        if (isset($result['openid'])){
            return $result;
        }
        return null;
    }
    
    /**
     * 发送带JSON数据请求
     *
     * @return 
     * @author Ymj Created at 2017年3月1日
     */
    public function requestWithJson($url, $params) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($params)
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
