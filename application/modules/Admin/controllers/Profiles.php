<?php

/*
 * 功能：后台中心－基础设置
 * Author:资料空白
 * Date:20180509
 */

class ProfilesController extends AdminBasicController
{
    private $m_admin_user;
	
	public function init()
    {
        parent::init();
		$this->m_admin_user = $this->load('admin_user');
    }

    public function indexAction()
    {
        if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
            $this->redirect('/'.ADMIN_DIR."/login");
            return FALSE;
        }
		$data = array();
        $this->getView()->assign($data);
    }

	
	public function passwordAction()
	{
        if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
            $this->redirect('/'.ADMIN_DIR."/login");
            return FALSE;
        }
		$data = array();
        $this->getView()->assign($data);
	}
	
	public function emailajaxAction()
	{
        if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
            $data = array('code' => 1000, 'msg' => '请登录');
			Helper::response($data);
        }
		$email = $this->getPost('email',false);
		$csrf_token = $this->getPost('csrf_token', false);
		
		$data = array();

		if($email AND $csrf_token AND isEmail($email)){
			if ($this->VerifyCsrfToken($csrf_token)) {
				if ($email!=$this->AdminUser['email']) {
					if ($this->AdminUser['email']!="demo@demo.com") {
						$data = array('code' => 1002, 'msg' => '管理账户只允许修改一次');
					} else {
						$m = array("email"=>$email);
						$u = $this->m_admin_user->UpdateByID($m,$this->AdminUser['id']);
						if ($u) {
							$data = array('code' => 1, 'msg' => '修改成功');
							$this->unsetSession('AdminUser');
						} else {
							$data = array('code' => 1004, 'msg' => '数据更新异常');
						}
					}
				} else {
					$data = array('code' => 1001, 'msg' => '您没有进行任何有效的修改');
				}
			} else {
                $data = array('code' => 1001, 'msg' => '页面超时，请刷新页面后重试!');
            }
		}else{
			$data = array('code' => 1000, 'msg' => '丢失参数/参数格式不正确');
		}
		Helper::response($data);
	}
	
	public function passwordajaxAction()
	{
        if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
            $data = array('code' => 1000, 'msg' => '请登录');
			Helper::response($data);
        }
		$password = $this->getPost('password',false);
		$oldpassword = $this->getPost('oldpassword',false);
		$csrf_token = $this->getPost('csrf_token', false);
		
		$data = array();

		if($password AND $oldpassword AND $csrf_token){
			if ($this->VerifyCsrfToken($csrf_token)) {
				if ($oldpassword !== $password) {
					if (strlen($password) < 6 ) {
						$data = array('code' => 1002, 'msg' => '密码过于简单,密码至少6位');
					} else {
						$check = $this->m_admin_user->checkLogin($this->AdminUser['email'], $oldpassword);
						if ($check) {
								$update = $this->m_admin_user->changePWD($this->AdminUser['id'], $password);
								if ($update) {
									$data = array('code' => 1, 'msg' => '修改密码成功');
									$this->unsetSession('AdminUser');
								} else {
									$data = array('code' => 1004, 'msg' => '数据更新异常');
								}

						} else {
							$data = array('code' => 1003, 'msg' => '原始密码不正确');
						}
					}
				} else {
					$data = array('code' => 1001, 'msg' => '新旧密码不能相同');
				}
			} else {
                $data = array('code' => 1001, 'msg' => '页面超时，请刷新页面后重试!');
            }
		}else{
			$data = array('code' => 1000, 'msg' => '丢失参数');
		}
		Helper::response($data);
	}

}