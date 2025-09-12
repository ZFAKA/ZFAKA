<?php
/**
 * File: M_Config_cat.php
 * Functionality: 配置分类 model
 * Author: 资料空白
 * Date: 2015-9-4
 */

class M_Config_cat extends Model
{

	public function __construct()
	{
		$this->table = TB_PREFIX.'config_cat';
		parent::__construct();
	}       
}