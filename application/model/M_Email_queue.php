<?php
/**
 * File: M_Email_queue.php
 * Functionality: 邮件队列 model
 * Author: 资料空白
 * Date: 2015-9-4
 */

class M_Email_queue extends Model
{

	public function __construct()
	{
		$this->table = TB_PREFIX.'email_queue';
		parent::__construct();
	}

}