<?php
/**
 * File: M_Email_code.php
 * Functionality: 邮箱验证码
 * Author: 资料空白
 * Date: 2015-9-4
 */

class M_Email_code extends Model
{

	public function __construct()
	{
		$this->table = TB_PREFIX.'email_code';
		parent::__construct();
	}

}