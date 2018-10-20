<?php
/**
 * 错误代码
 *
 */
class ErrorCode {    
    const SUCCESS               = 200;      //成功
    const ERROR                 = 10000;    //错误，原因不明
    const NO_LOGIN              = 10001;    //尚未登录
    const USER_NOT_EXIST        = 10002;    //用户不存在
    const PARAM_ERROR           = 10003;    //参数错误
    const PARAM_MISSING         = 10004;    //缺少必要参数
    const TOO_MANY_REQUEST      = 10005;    //请求太过频繁
    const BAN_IP                = 10006;    //IP被禁止访问
    
    //项目自定义错误代码放置位置
    
}