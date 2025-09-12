<?php
/**
 * File: M_Products_type.php
 * Functionality: 产品分类 model
 * Author: 资料空白
 * Date: 2015-9-4
 */

class M_Products_type extends Model
{

	public function __construct()
	{
		$this->table = TB_PREFIX.'products_type';
		parent::__construct();
	}
	/**
	 * 获取参数配置文件
	 * @param string $roleid
	 * @param string $password
	 * @return params on success or 0 or failure
	 */
	public function getConfig($new=0)
	{
		$data = $config = array();
			$file_path=TEMP_PATH ."/products_type.json";
			if(file_exists($file_path) AND !$new){
				$data = json_decode(file_get_contents($file_path),true);
			}
		//取旧值
		if(!empty($data) AND isset($data['config']) AND (isset($data['expire_time']) AND $data['expire_time'] > time())){
			$config =$data['config'];
		}

		if (empty($config) OR $new){
    		$data['config'] = $config = $this->_getData();
    		$data['expire_time'] = time() + 600;
			file_put_contents($file_path,json_encode($data));
    	}
		

		return $config;
	}

	private function _getData()
	{
		$_config = array();
		$result=$this->Where(array('active'=>1))->Select();
		foreach($result AS $i){
			$k=$i['id'];
			$_config[$k]=$i;
		}
		return $_config;
	}  
}