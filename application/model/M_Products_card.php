<?php
/**
 * File: M_Products_card.php
 * Functionality: 卡密 model
 * Author: 资料空白
 * Date: 2015-9-4
 */

class M_Products_card extends Model
{

	public function __construct()
	{
		$this->table = TB_PREFIX.'products_card';
		parent::__construct();
	}

}