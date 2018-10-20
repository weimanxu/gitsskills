<?php
Class BaseClass {
    
	/**
	 * 返回错误信息数组
	 * 
	 * @param string $error
	 * @param int $errorCode
	 * @return array
	 */
	public function arrayError($error = '', $errorCode = ErrorCode::ERROR) {
		return ['success' => false, 'errorCode' => $errorCode, 'error' => $error];
	}
	
	/**
	 * 返回成功信息数组
	 * 
	 * @param string $error
	 * @param int $errorCode
	 * @return array
	 */
	public function arraySuccess($error = '', $errorCode = ErrorCode::SUCCESS) {
		return ['success' => true, 'errorCode' => $errorCode, 'error' => $error];
	}
	
	/**
	 * 返回成功信息数组
	 * 
	 * @param string | array $data
	 * @param int $errorCode
	 * @return array
	 */
	public function arraySuccessWithData($data = [], $error = '', $errorCode = ErrorCode::SUCCESS) {
		return ['success' => true, 'errorCode' => $errorCode, 'data' => $data, 'error' => $error];
	}
	
	/**
	 * 不直接
	 * @return 
	 */
	public function __call($name, $arguments) {
		throw new Exception('Method Not Exist!');
	}
}