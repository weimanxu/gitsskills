<?php
/**
 * 七牛云
 * 
 */
class Qiniu {
    
    private $accessKey;
    private $secretKey;
	
	public function __construct($accessKey = null, $secretKey = null) {
	    if ($accessKey == null || $secretKey == null) {
	        $accessKey = App::loadConf('qiniu/access');
	        $secretKey = App::loadConf('qiniu/secret');
	    }
	    
	    $this->accessKey = $accessKey;
	    $this->secretKey = $secretKey;
	}
    
    
	/**
	 * 生成签名
	 *
	 * @desc 签名运算
	 * @param string $access_key
	 * @param string $secret_key
	 * @param string $url
	 * @param array  $params
	 * @return string
	 */
	
	public function generateAccessToken($url, $params = ''){
		$parsed_url = parse_url($url);
		$path = $parsed_url['path'];
		$access = $path;
		if (isset($parsed_url['query'])) {
			$access .= "?" . $parsed_url['query'];
		}
		$access .= "\n";
		if($params){
			if (is_array($params)){
				$params = http_build_query($params);
			}
			$access .= $params;
		}
		$digest = hash_hmac('sha1', $access, $this->secretKey, true);
		return $this->accessKey.':'. $this->urlsafeBase64Encode($digest);
	}
	
	/**
	 *
	 * @desc URL安全形式的base64编码
	 * @param string $str
	 * @return string
	 */
	
	
	public function urlsafeBase64Encode($str){
		$find = array("+","/");
		$replace = array("-", "_");
		return str_replace($find, $replace, base64_encode($str));
	}
	
	/**
	 * 远程抓取资源
	 *
	 * @param string $fetch
	 * @param string $to
	 * @return array 
	 * @author Ymj Created at 2016年6月17日
	 */
	public function fetch($fetch, $to) {
	    $fetch = $this->urlsafeBase64Encode($fetch);
	    $to    = $this->urlsafeBase64Encode($to);
	    $url   = 'http://iovip.qbox.me/fetch/'. $fetch .'/to/' . $to;
	    $accessToken = $this->generateAccessToken($url);
	    $header[] = 'Content-Type: application/x-www-form-urlencoded';
	    $header[] = 'Authorization: QBox '. $accessToken;
	    
	    
	    $data = $this->send($url, $header);
	    if(empty($data))
	        return null;
	    $data = json_decode($data[1], true);
	    //补充URL
	    $data['url'] = App::loadConf('fileServer') . $data['key'];
	    return $data;
	}
	
	/**
	 * 获取图片基本信息
	 *
	 * @return 
	 * @author Ymj Created at 2016年6月17日
	 */
	public function getImageInfo ($url) {
	    $url = $url . '?imageInfo';
	    $result = file_get_contents($url);
	    try {
	        $result = json_decode($result, true);
	    }catch (Exception $e){
	        return null;
	    }
	    if(isset($result['error']))
	        return null;
	    
	    return $result;
	}
	
	/**
	 * 生成上传凭证
	 * 
	 * @param  array $putPolicy 上传策略（参照http://developer.qiniu.com/article/developer/security/put-policy.html）
	 * @return string
	 * @author Ymj Created at 2016年7月24日
	 */
	public function uploadToken ($putPolicy){
	    $putPolicyJson = json_encode($putPolicy);
	    $encodedPutPolicy = $this->urlsafeBase64Encode($putPolicyJson);
	    
	    $sign = hash_hmac('sha1', $encodedPutPolicy, $this->secretKey, true);
	    $encodedSign = $this->urlsafeBase64Encode($sign);
	    $uploadToken = $this->accessKey . ':' . $encodedSign . ':' . $encodedPutPolicy;
	    
	    return $uploadToken;
	}
	
	/**
	 * 发送数据
	 *
	 * @return 
	 * @author Ymj Created at 2016年6月17日
	 */
	private function send($url, $header = []) {
	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl, CURLOPT_HEADER,1);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	    //curl_setopt($curl, CURLOPT_POST, 1);
	    $response = curl_exec($curl);
	    
	    if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != '200') {
	        return null;
	    }
	    $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
	    $header = substr($response, 0, $headerSize);
	    $body = substr($response, $headerSize);
	    
	    curl_close($curl);
	    
	    return [$header, $body];
	}
}