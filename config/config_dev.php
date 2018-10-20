<?php
return [
    //mysql配置
    'mysql' => [
    		//主从数据库
    		'masters' => [
    				[
    						'host' => '127.0.0.1',
    						'port' => 3306,			        //数据库端口号
    						'database' => 'ico2',		//默认数据库
    						'user'	=>	'root',			    //连接数据库账号
    						'password' => 'root'
    				],
			
    		],
    		'slaves' =>[
    		]
	],

	//redis配置
	'redis' => [
			'host' => '127.0.0.1',
			'port' => 6379				//redis端口
	]
];