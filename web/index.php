<?php
	//入口时间
	define('START_TIME', microtime(true));
    //项目根目录
    define('PATH', dirname(__DIR__));
    //web目录路径
    define('PATH_WEB', PATH . '/web');
    //BEGIN
    require_once PATH . '/core/App.php';
    App::RUN();