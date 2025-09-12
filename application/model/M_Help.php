<?php
/**
 * File: M_Help.php
 * Functionality: 帮助 model
 * Author: 资料空白
 * Date: 2015-9-4
 */

class M_Help extends Model
{

	public function __construct()
	{
		$this->table = TB_PREFIX.'help';
		parent::__construct();
	}

}