<?php
/**
 * File: M_User_login_logs.php
 * Functionality: 用户 model
 * Author: 资料空白
 * Date: 2018-05-21
 */

class M_User_login_logs extends Model 
{

	public function __construct()
	{
		$this->table = TB_PREFIX.'user_login_logs';
		parent::__construct();
	}

}