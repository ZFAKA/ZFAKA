<?php
/**
 * File: M_User.php
 * Functionality: 用户 model
 * Author: 资料空白
 * Date: 2018-05-21
 */

class M_User extends Model
{

	public function __construct()
	{
		$this->table = TB_PREFIX.'user';
		parent::__construct();
	}

	/**
	 * 登录
	 * @param string $email
	 * @param string $password
	 * @return params on success or 0 or failure
	 */
	public function checkLogin($email, $password)
	{
		$field = array('id', 'email','password','nickname','groupid');
		if(isEmail($email)){
			$where = array('email' => $email);
		}else{
			return FALSE;
		}
		$result=$this->Field($field)->Where($where)->SelectOne();
		if($result){
			if(md5($password)===$result['password']){
				unset($result['password']);
				$this->_session($result);
				return $result;
			}
		}
		return FALSE;
	}

	public function newRegister($params)
	{
		$m=array();
		$m['email'] = $params['email'];
		$m['nickname'] = $params['nickname'];
		$m['createtime']= time(); 
		$m['password'] = md5($params['password']);

		if(isset($params['qq'])){
			$m['qq'] = $params['qq'];
		}else{
			$m['qq'] = '';
		}
		if(isset($params['tag'])){
			$m['tag'] = $params['tag'];
		}else{
			$m['tag'] = '';
		}
		$m['groupid'] = 1;
		$m['money'] = $m['integral'] = 0;

        if($uid=$this->Insert($m)){
			$m['id'] = $uid;
			unset($m['password']);
			$this->_session($m);
            return TRUE;
        }else{
            return FALSE;
        }
	}

	public function checkEmail($email)
	{
		$field = array('id');
		$where = array('email' => $email);
		$result=$this->Field($field)->Where($where)->SelectOne();
		if($result){
            return array('id'=>$result['id']);
		}
		return array();
	}
	
    /*
     * 修改密码
     */
	public function changePWD($userid,$password)
	{
		if($userid AND $password){
			$new=md5($password);
			return $this->Where(array('id'=>$userid))->Update(array('password'=>$new));
		}else{
			return FALSE;
		}
    }
	
	/**
	* 登录session处理
	* @param $params
	* @return 1
	*/
	private function _session($params)
	{
		if(!empty($params)){
			$params['expiretime']= time() + 15*60;
			\Yaf\Session::getInstance()->__set('uinfo', $params);
		}
	}

}