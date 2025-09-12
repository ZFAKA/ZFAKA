<?php
/**
 * @date 2017-11-23
 * @author zlkb.net
 * @desc 安全模块－int类
 */

namespace Safe;
class MyInt extends \Safe\Base 
{
    public function __construct($str=null)
    {
		parent::__construct($str);
		$this->value = preg_replace('/[^\.0123456789]/s', '',$this->value);
	}
}