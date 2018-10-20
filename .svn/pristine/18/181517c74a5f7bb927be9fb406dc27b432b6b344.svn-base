<?php
class SysCrypt {
	private $crypt_key 	= '7f83d61d6e695964';		// 256 -> 32 * 8, 128 -> 16 * 8
	private $iv 		= 'faf9cd39fe0646e0';
	const CIPHER 		= MCRYPT_RIJNDAEL_128;
	const MODE 			= MCRYPT_MODE_CBC;
	
	// 构造函数
	public function __construct($crypt_key = '', $iv = '') {
		!empty($crypt_key) && $this->crypt_key = $crypt_key;
		!empty($iv) && $this->iv = $iv;
	}
	
	
	public function php_encrypt($txt) {
		$tmp = mcrypt_encrypt(self::CIPHER, $this->crypt_key, $txt, self::MODE, $this->iv);
		return rtrim(strtr(base64_encode($tmp), '+/', '-_'), '=');
	}
	public function php_decrypt($txt) {
		$txt = str_pad(strtr($txt, '-_', '+/'), strlen($txt) % 4, '=', STR_PAD_RIGHT);
		return rtrim(mcrypt_decrypt(self::CIPHER, $this->crypt_key, base64_decode($txt), self::MODE, $this->iv));
	}
	public function __destruct() {
		$this->crypt_key = null;
	}
}