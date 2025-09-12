<?php
/**
 * File: F_Validate.php
 * Functionality: 验证函数集合
 * Author: 资料空白
 * Date: 2016-11-11再整理
 */


//检查是否为邮箱格式
if (!function_exists('isEmail')){
	function isEmail($email) {
		if (!$email) {
			return false;
		}

		return preg_match('/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$/', $email);
	}
}
//检查是否为代码
if (!function_exists('isPostalCode')){
	function isPostalCode($postalCode) {
		if (!$postalCode) {
			return false;
		}

		return preg_match("/^[1-9]\d{5}$/", $postalCode);
	}
}
//检查是否IP地址
if (!function_exists('isIPAddress')){
	function isIPAddress($IPAddress) {
		if (!$IPAddress) {
			return false;
		}
		return preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])" .
	                    "(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $IPAddress);
	}
}
//检查是否为身份证号码
if (!function_exists('isIDCard')){
	function isIDCard($IDCard) {
		if (!$IDCard) {
			return false;
		}
		return preg_match('/(^([\d]{15}|[\d]{18}|[\d]{17}x)$)/', $IDCard);
	}
}

//检查中文
if (!function_exists('isCn')){
	function isCn($str){
		if(preg_match("/[\x{4e00}-\x{9fa5}]+/u", $str)) {
			return true;
		}
		return false;
	}
}
/**
 * 检查数字
 * @param string $str 标签字符串
 */
if (!function_exists('isNumber')){
	function isNumber($str){
		if(preg_match('/^\d+$/', $str)) {
			return true;
		}
		return false;
	}
}
/**
 * 检查是否每位相同
 * @param string $str 标签字符串
 */
if (!function_exists('isNumSame')){
	function isNumSame($str){
		if(preg_match('/^(\w)\1+$/', $str)) {
			return true;
		}
		return false;
	}
}
/**
 * 检查是否为空
 * @param string $str 标签字符串
 */
if (!function_exists('isEmpty')){
	function isEmpty($str){
		if(preg_match('/^\s*$/', $str)) {
			return true;
		}
		return false;
	}
}

/**
 * 检测是否为合法url
 */
if (!function_exists('isUrl')){
	function isUrl($url){
	    if(!preg_match('/http[s]?:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$url)){
	        return false;
	    }
	    return true;
	}
}

if (!function_exists('isPhoneNumber')){
	function isPhoneNumber($phone) {
		if (!$phone) {
			return false;
		}
		return preg_match('/^((\(d{2,3}\))|(\d{3}\-))?1(1|3|4|5|6|7|8|9)\d{9}$/', $phone);
	}
}

if (!function_exists('isAreaCode')){
	function isAreaCode($code){
		if (!$code) {
			return false;
		}

		return preg_match('/^(0\d{3})|(0\d{2})$/', $code);
	}
}
