<?php
class SendEmail{
	private static $Instance = null;
	private $mail;					//email类对象
	private static $size;
	private $email_key = '73E6C22F69814A2E';
	
	//发件人邮箱
	private $address = [
			[
					'name' => 'no-reply@caikuico.com',
					'pwd' => 'Caikuico469823'
			],
	];
	
	

	/**
	 * 单实例
	 */
	public static function _getInstance () {
		if (self::$Instance === null){
			self::$Instance = new self();
		}
		return self::$Instance;
	}
	
	/**
	 * 构造函数
	 */
	private function __construct(){
		self::$size = count($this->address);
		
 		require_once "Email/class.phpmailer.php";
		$mail = new PHPMailer();
		
		$mail->SetLanguage('zh_cn');				//设置语言
		$mail->IsSMTP();							//使用SMTP方式发送
		$mail->IsHTML(true);						// 是否HTML格式邮件
		
		$mail->Host 		= 'smtp.mxhichina.com';	//smtp服务器名
		$mail->SMTPAuth 	= true;					//启用SMTP验证功能
		$mail->SMTPSecure 	= 'ssl';
		$mail->Port 		= 465;					//端口号
		
		$mail->FromName 	= '财酷ICO';			    //发送人昵称
		$mail->WordWrap 	= 50;					//50个字符自动换行
		$mail->CharSet 		= 'utf-8';
		
		$this->mail = $mail;
	}
	
	/**
	 * 发送邮件
	 * @param string | array $emailAddress
	 * @param $subject
	 * @param $content
	 * @return array
	 */
	public function send($emailAddress, $subject, $content){
		$this->random();
		
		if (is_array($emailAddress)){
			foreach ($emailAddress as $oneEmail){
				$this->mail->AddAddress($oneEmail);
			}
		}else $this->mail->AddAddress($emailAddress);
		$this->mail->Subject = $subject;	//邮件主题
		$this->mail->Body    = $content;    //邮件内容
		
		if(!$this->mail->Send())
			return ['success' => false, 'error' => '发送失败，原因：'. $this->mail->ErrorInfo];
		
		return ['success' => true, 'error' => '验证邮件发送成功，请到您的邮箱：'. $emailAddress .' 查看！'];
	}
	
	private function random(){
		//获取随机邮箱
		$num = mt_rand(0, self::$size - 1);
		$this->mail->Username = $this->address[(int) $num]['name'];	//服务器认证用户名(完整的email地址)
		$this->mail->Password = $this->address[(int) $num]['pwd'];	//认证密码
		
		$this->mail->From = $this->address[(int) $num]['name'];		//发送人邮箱地址
	}
	
	/**
	 * 生成邮件验证token
	 * @return string
	 */
	public function getEmailToken($uid, $email){
		$info = [
				'time' 		=> time(),
				'uid' 	    => $uid,
				'email' 	=> $email
		];
		$txt = json_encode($info);
	
		$crypt = new SysCrypt($this->email_key);
		$crytext = $crypt->php_encrypt($txt);
	
		return $crytext;
	}
	
	
	/**
	 * 解码邮件token
	 * @return false | array
	 */
	public function decryptToken($token) {
		$syscrypt = new SysCrypt($this->email_key);
		$tokenStr = trim(
				$syscrypt->php_decrypt($token)
		);
		$info = json_decode($tokenStr, true);
		$err = json_last_error();
		if ($err === JSON_ERROR_NONE)
			return $info;
		
		else return false;
	}
}