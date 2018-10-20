<?php
class Async {
   /**
	* 模拟多线程调用接口
	* 
	* @param array $record		//数据
	* @param string $path		//系统内的接口地址
	* @return boolean
	*/
	public static function multiCall($record, $path){
		ignore_user_abort(true);//关闭浏览器也继续运行
		
		$host = App::$request->getHost();
		$port = $_SERVER['SERVER_PORT'];
		
		$scheme = '';
		if (App::$request->isHttps()){
			$scheme = 'ssl://';
		}
		$send_type = 'POST'; // 请求方法(POST方式)
		
		//连接服务器
		//连接失败则 $errno/$errstr会被填充内容
		//timeout 建立连接的超时时间
		$fp = fsockopen($host, $port, $errno, $errstr, 5);
		
		//连接建立失败
		if (!$fp) {
			return false;
		}
		//设置为非阻塞模式
		stream_set_blocking($fp, 0);
		
		
		//code为识别验证码
		$record['code'] = App::loadConf('secretKey');
	
		$encoded = "";
		//键值对组装成字符串，如：a=123&b=456
		foreach ($record as $key => $value){
			$encoded .= $encoded ? "&" : "";
			$encoded .= rawurlencode($key) .'='. rawurlencode($value);
		}
	
		//模拟http请求头信息
		$post = $send_type ." ". $path ." HTTP/1.1\n";
		$post .= "Host: $host\n";
		$post .= "Content-type: application/x-www-form-urlencoded\n";
		$post .= "Content-length: " . strlen ( $encoded ) . "\n";
		$post .= "Connection: close\n\n";
		$post .= "$encoded\n";
		//传送信息
		fputs($fp, $post);
		//sleep(1);//休眠1s，使得数据能全部发送出去。
		
		/*忽略执行结果
		while (!feof($fp)) {
			echo fgets($fp, 128);
		}*/
		
		//关闭链接
		fclose($fp);
		
		return true;
	}
}