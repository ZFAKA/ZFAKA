<?php
/**
 * File: M_Default.php
 * Functionality: 默认 model
 * Author: 资料空白
 * Date: 2015-9-4
 */

class M_Default extends Model
{

	public function __construct($table)
	{
		$this->table = TB_PREFIX.$table;
		parent::__construct();
	}

}