/**
 * 数据校验类
 */
define([], function(){
	var validator;
	
	validator = {
			/**
			 * 检查邮箱地址
			 * 
			 */
			checkEmail : function (email) {
				return /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/.test(email);
			},
			
			/**
			 * 检查手机号码
			 * 
			 */
			checkPhone : function (phone) {
				return /^(13[0-9]|147|15[^4]|18[^14])\d{8}$/.test(phone);
			},
			
			/**
			 * 检查密码
			 * 
			 */
			checkPassword : function (password) {
				return /^.{6,32}$/.test(password);
			}
	};
	
	return validator;
});