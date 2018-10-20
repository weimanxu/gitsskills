<?php

/**
 * IP 地理位置
 * （聚合数据）
 */

class JuheApi {
	
	//APPKEY URL
	private static $ip_appkey 	= '';
	private static $ip_url 		= 'http://apis.juhe.cn/ip/ip2addr';
	
	private static $sms_appkey 	= '';
	private static $sms_url 	= 'http://v.juhe.cn/sms/send';
	
	/**
	 * 获取IP地理位置
	 * 
	 * @param str $ipAddr	//要查询的IP地址
	 * @param bool $ispost	//是否用POST发送数据，默认用GET
	 * @return array
	 *  ['success' => true | false, 'error' => '', 'area' => '', 'location' => '']
	 * 
	 */
	public static function getIpLocation ($ipAddr, $ispost = false) {
		//保留地址 或 局域网地址
		if ($ipAddr == '127.0.0.1' || !filter_var($ipAddr, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
			return [
				'success' 	=> true,
				'error' 	=> '',
				'area' 		=> '局域网或保留地址',
				'location' 	=> '',
			];
		}
		
		//组装参数
		$params = 'ip='. $ipAddr .'&key='. self::$ip_appkey .'&dtype=json';
		$url = self::$ip_url;
		
		$result = self::toJuhe($url, $params, $ispost);
		
		//成功获取数据
		if ($result['error_code'] == 0) {
			return [
				'success' 	=> true,
				'error' 	=> '',
				'area' 		=> $result['result']['area'],
				'location' 	=> $result['result']['location']
			];
		}
		
		return self::_errArr($result['reason']);
	}
	
	
	/**
	 * 发送手机验证码
	 * 
	 * 查看模板ID以及里面的变量：http://www.juhe.cn/sms
	 * -----------------------------------------------------------
	 * |  ID 	|				内容
	 * |----------------------------------------------------------
	 * | 17482	|	【股友圈】您本次登录的验证码是#code#。如非本人操作，请忽略本短信
	 * |----------------------------------------------------------
	 * |		|
	 * |----------------------------------------------------------
	 * |		|
	 * |----------------------------------------------------------
	 * |		|
	 * -----------------------------------------------------------
	 * eg：
	 * 【XX】尊敬的#user#，您绑定手机的验证码是#code#。如非本人操作，请忽略本短信
	 * 
	 * $template_values = ['user' => '', 'code' => '']
	 * 
	 * 
	 * @param $phone	//手机号码
	 * @param $template_id		//聚合平台的模板ID
	 * @param $template_values	//模板的变量（键值对）
	 * @param $ispost			//是否用POST发送数据，默认用GET
	 * @return array
	 * ['success' => false | true, 'error' => '', 'data' => str | array]
	 * 
	 */
	public static function sendSms ($phone, $template_id, $template_values, $ispost = false) {
	    
// 		$tp_value = '';
// 		foreach ($template_values as $key => $value) {
// 			$tp_value .= '#'. $key .'#='. urlencode($value) .'&';
// 		}
// 		$tp_value = urlencode(substr($tp_value, 0, -1));
		
		//组装参数
		$params = 'mobile='. $phone .'&tpl_id='. $template_id .'&tpl_value='. $template_values .'&key='. self::$sms_appkey .'&dtype=json';
		$url = self::$sms_url;
		
		$result = self::toJuhe($url, $params, $ispost);
		/**
		*  [
		* 		"error_code" 	=> 0,							//发送成功
		*	    "reason" 		=> "",							//文字描述
		*	    "result" 		=> [
		*	        					"count" => 1, 			//发送数量
		*	        					"fee" => 1, 			//扣除条数
		*	        					"sid" => 2029865577 	//短信ID
		*	    				   ]
		*  ]
		*/
		
		
		//成功获取数据
		if ($result['error_code'] == 0) {
			return self::_successArr();
		}
		
		return self::_errArr('发送失败！');
	}
	
	
	/**
	 * 获取聚合接口数据
	 * 
	 * @param str $ipAddr	//要查询的IP地址
	 * @param bool $ispost	//是否用POST发送数据，默认用GET
	 * @return array
	 * 
	 * 
	 *  [
	 * 		"error_code" 	=> 0,							//发送成功
	 *	    "reason" 		=> "",							//文字描述
	 *	    "result" 		=> [
	 *	        					"key1" => value1, 		//发送数量
	 *	        					"key2" => value2, 		//扣除条数
	 *								...........
	 *	    				   ]
	 *	]
	 * 
	 * 
	 */
	public static function toJuhe($url, $params, $ispost = false){
		
		$httpInfo = [];
		$ch = curl_init();
	
		curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_0 );
		curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
		curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
		if( $ispost ) {
			curl_setopt( $ch , CURLOPT_POST , true );
			curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
			curl_setopt( $ch , CURLOPT_URL , $url );
		}else {
			if($params) {
				curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
			}else{
				curl_setopt( $ch , CURLOPT_URL , $url);
			}
		}
		$response = curl_exec( $ch );
		
		if ($response === false) {
			//echo "cURL Error: " . curl_error($ch);
			return [
					'error_code' 	=> 'err',
					'reason' 		=> curl_error($ch),
					'result' 		=> []
			];
		}
		$httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
		$httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
		curl_close( $ch );
		
		$result =json_decode($response, true);
		$err = json_last_error();
		
		//JSON格式有误
		if ($err !== JSON_ERROR_NONE)
			return [
					'error_code' 	=> 'err',
					'reason' 		=> 'json error',
					'result' 		=> []
			];
		
		return $result;
	}
	
	/**
	 * 返回错误数组
	 * 
	 * @param string $error		//错误说明
	 * @return 
	 */
	private static function _errArr ($error = '') {
		return ['success' => false, 'error' => $error];
	}
	
	
	private static function _successArr($data = []) {
		return ['success' => true, 'error' => '', 'data' => $data];
	}
}