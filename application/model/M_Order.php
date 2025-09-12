<?php
/**
 * File: M_Order.php
 * Functionality: 订单 model
 * Author: 资料空白
 * Date: 2015-9-4
 */

class M_Order extends Model
{

	public function __construct()
	{
		$this->table = TB_PREFIX.'order';
		parent::__construct();
	}

}