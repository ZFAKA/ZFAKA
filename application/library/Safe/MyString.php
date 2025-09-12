<?php
/**
 * @date 2017-11-23
 * @author zlkb.net
 * @desc 安全模块－字符串类
 */

namespace Safe;
class MyString extends \Safe\Base
{
	
	/**
	* 去除符号（保留汉字、数字、字母）
	* 
	* return object
	*/	
	public function qufuhao()
	{
		$this->value = preg_replace('#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u','',$this->value);
		return $this;
	}
	 
	/**
	* 去除符号（保留数字、字母）
	* 
	* return object
	*/	
	public function qufuhao2()
	{
		$this->value = preg_replace('/[^0-9a-zA-Z]+/','',$this->value);
		return $this;
	}	 
	
}