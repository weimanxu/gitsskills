<?php
/*
 * 数据校验
 */
class Validator {
    
	/**
	 * 检查邮箱地址
	 * 
	 * @param string $email
	 * return boolean	true -> 验证通过
	 */
	static public function isEmail($email){
		return !empty($email) && preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email);
	}
	
	/**
	 * 检查手机号
	 * 
	 * @param string $phone
	 * @return boolean	true -> 验证通过
	 */
	static public function isPhoneNumber($phone) {
		return !empty($phone) && preg_match('/(^(13\d|15[^4,\D]|17[13678]|18\d)\d{8}|170[^346,\D]\d{7})$/', $phone);
	}
	
	/**
	 * 验证密码，密码由字母、数字、特殊字符组成，【6-32位】，区分大小写
	 * 
	 * @param string $password
	 * @return boolean	true -> 验证通过
	 */
	static public function isValidPassword($password){
		return !empty($password) && preg_match("/^[^\x{4e00}-\x{9fa5}]{6,32}$/u", $password);
	}
	
	/**
	 * 是否Integer
	 * 
	 * @param int | string	$num
	 * @return boolean  true -> 验证通过
	 */
	static public function isInt($num) {
		return isset($num) && is_numeric($num) && (strpos($num, '.') === false);
	}
	
	/**
	 * false -> 小于0的数 | .001 | 100. | 0xfff | 1.2e+10 | 其他非数字字符串
	 * 
	 * 是否大于0的高精度浮点数 | 整数（不允许科学计数法、其他进制数）
	 * 小数点前后必须有数字，不能以小数点开头和结尾
	 * 
	 * 
	 * @param string & numeric $num
	 * @return 
	 */
	static public function isHighPrecisionNumber ($num) {
		return isset($num) && is_string($num) && preg_match('/^(([0-9]+.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*.[0-9]+)|([0-9]*[1-9][0-9]*))$/', $num);
	}
	
	
	/**
	 * 是否IP地址
	 * @param string 	$ip		//ip地址
	 * @return boolean  true -> 验证通过
	 */
	static public function isIpAddress ($ip) {
		return isset($ip) && filter_var($ip, FILTER_VALIDATE_IP) !== false;
	}
}