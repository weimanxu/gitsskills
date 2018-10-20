<?php
return [
    //环境，dev（开发环境）或者pro（生产环境）
    'deployEnv'     => 'dev',
    
	'cdnServer' 	=> '',
    
    'fileServer'    => '',
    //版本号
    'deployVersion' =>  '2.0.0',  //'1.0.0',
    
    //是否开启debug
    'debug'         =>  false,
    
    //默认语言
    'language'      =>  'zh-CN',
    
    //默认时区
    'timezone'      =>  'Asia/Shanghai',
    
    //安全KEY
    'secretKey'     =>  '73E6C22F69814A2E',
		
    //网站名称
    'webName'       =>  '财酷ICO',
    
    //网页后缀
    'suffic'        =>  '.html',
     
    //用户自定义Controller基类
    'baseController' =>  'CustomBaseController',
    
    //用户自定义class基类
    'baseClass'     =>  'CustomBaseClass',
    
    //项目自定义配置
    'app'           =>  require(PATH . '/config/config_app.php')
];
