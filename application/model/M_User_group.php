<?php
/**
 * File: M_User_group.php
 * Functionality: 用户分组 model
 * Author: 资料空白
 * Date: 2018-05-21
 */

class M_User_group extends Model
{
	public function __construct()
	{
		$this->table = TB_PREFIX.'user_group';
		parent::__construct();
	}
	
	public function getConfig($new=0){
		$data = $config = array();
		$file_path=TEMP_PATH ."/user_group.json";
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

	private function _getData(){
		$result=$this->Select();
		foreach($result AS $i){
			$config[$i['id']]=htmlspecialchars_decode($i['name'],ENT_QUOTES);
		}
		return $config;
	}	
}