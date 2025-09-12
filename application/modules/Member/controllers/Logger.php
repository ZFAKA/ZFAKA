<?php

/*
 * 功能：会员中心－日志中心
 * Author:资料空白
 * Date:20150902
 */

class LoggerController extends MemberBasicController
{
	private $m_user_login_logs;
    public function init()
    {
        parent::init();
		$this->m_user_login_logs = $this->load('user_login_logs');
    }

    public function indexAction()
    {
        if ($this->login==FALSE AND !$this->userid) {
            $this->redirect("/member/login");
            return FALSE;
        }
		$data = array();
		$data['title'] = "登录日志";
        $this->getView()->assign($data);
    }

	
	//登录日志ajax
	public function ajaxAction()
	{
        if ($this->login==FALSE AND !$this->userid) {
            $data = array('code' => 1000, 'msg' => '请登录');
			Helper::response($data);
        }
		
		$where = array('userid'=>$this->userid);
		
		$page = $this->get('page');
		$page = is_numeric($page) ? $page : 1;
		
		$limit = $this->get('limit');
		$limit = is_numeric($limit) ? $limit : 10;
		
		$total=$this->m_user_login_logs->Where($where)->Total();
		
        if ($total > 0) {
            if ($page > 0 && $page < (ceil($total / $limit) + 1)) {
                $pagenum = ($page - 1) * $limit;
            } else {
                $pagenum = 0;
            }
			
            $limits = "{$pagenum},{$limit}";
			$items=$this->m_user_login_logs->Where($where)->Limit($limits)->Order(array('id'=>'DESC'))->Select();
			
            if (empty($items)) {
                $data = array('code'=>0,'count'=>0,'data'=>array(),'msg'=>'无数据');
            } else {
                $data = array('code'=>0,'count'=>$total,'data'=>$items,'msg'=>'有数据');
            }
        } else {
            $data = array('code'=>0,'count'=>0,'data'=>array(),'msg'=>'无数据');
        }
		Helper::response($data);
	}
}