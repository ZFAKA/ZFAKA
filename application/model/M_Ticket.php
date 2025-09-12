<?php
/**
 * File: M_Ticket.php
 * Functionality: 工单
 * Author: 资料空白
 * Date: 2016-03-21
 */

class M_Ticket extends Model
{

	public function __construct()
	{
		$this->table = TB_PREFIX.'ticket';
		parent::__construct();
	}


}