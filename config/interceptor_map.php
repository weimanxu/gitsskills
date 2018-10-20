<?php
/**
 * 拦截器配置
 * 
 * 拦截器内一般不做输出
 * 
 * 'Example/first'
 *      执行ExampleInterceptor/firstCondition 判断是否需要执行拦截器
 *      执行ExampleInterceptor/firstAction 若返回false，停止执行之后的拦截器
 * 
 * [
 *      'condition' => 'url|/^\/index$/',         //匹配/index URL目录，支持url,host,ip,useragent
 *      'action'    => 'Example/second'
 * ]
 * 
 * [
 *      'condition' => 'url|/^\/news$/',
 *      'action'    => function(){}               //支持callback
 * ]
 */
//拦截器配置
return [
];