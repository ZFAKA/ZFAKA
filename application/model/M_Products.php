<?php
/**
 * File: M_Products.php
 * Functionality: 产品 model
 * Author: 资料空白
 * Date: 2015-9-4
 */

class M_Products extends Model
{

	public function __construct()
	{
		$this->table = TB_PREFIX.'products';
		parent::__construct();
	}

}