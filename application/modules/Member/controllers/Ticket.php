<?php

/*
 * 功能：帮助工单
 * Author:资料空白
 * Date:20180508
 */

class TicketController extends MemberBasicController
{

    private $m_ticket;
	public function init()
    {
        parent::init();
		$this->m_ticket = $this->load('ticket');
    }

	//工单列表
    public function indexAction()
    {
        if ($this->login==FALSE AND !$this->userid) {
            $this->redirect("/member/login");
            return FALSE;
        }
		$data = array();
		$data['title'] = "我的工单";
        $this->getView()->assign($data);
    }
	
	//工单列表ajax
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
		
		$total=$this->m_ticket->Where($where)->Total();
		
        if ($total > 0) {
            if ($page > 0 && $page < (ceil($total / $limit) + 1)) {
                $pagenum = ($page - 1) * $limit;
            } else {
                $pagenum = 0;
            }
			
            $limits = "{$pagenum},{$limit}";
			$items=$this->m_ticket->Where($where)->Limit($limits)->Order(array('id'=>'DESC'))->Select();
			
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
	
    public function addAction()
    {
        if ($this->login==FALSE AND !$this->userid) {
            $this->redirect("/member/login");
            return FALSE;
        }
		$data = array();
		$data['title'] = "提交工单";
        $this->getView()->assign($data);
    }
	
	public function addajaxAction()
	{
        if ($this->login==FALSE AND !$this->userid) {
            $data = array('code' => 1000, 'msg' => '请登录');
			Helper::response($data);
        }
		
		$priority = $this->getPost('priority',false);
		$subject = $this->getPost('subject',false);
		$typeid = $this->getPost('typeid',false);
		$content = $this->getPost('content',false);
		$csrf_token = $this->getPost('csrf_token', false);

		$content_string = new \Safe\MyString($content);
		$content = $content_string->trimall()->qufuhao()->getValue();
		
		if(is_numeric($priority) AND $subject AND is_numeric($typeid) AND $content AND $csrf_token){
			if ($this->VerifyCsrfToken($csrf_token)) {
				$m = array('userid'=>$this->userid,'typeid'=>$typeid,'priority'=>$priority,'subject'=>$subject,'content'=>$content,'status'=>0,'addtime'=>time());
				$tid=$this->m_ticket->Insert($m);
				$data = array('code' => 1, 'msg' => 'success','data'=>array('tid'=>$tid));
			} else {
                $data = array('code' => 1001, 'msg' => '页面超时，请刷新页面后重试!');
            }
		}else{
			$data = array('code' => 1000, 'msg' => '丢失参数');
		}
		Helper::response($data);
	}
	
	public function detailAction()
	{
        if ($this->login==FALSE AND !$this->userid) {
            $this->redirect("/member/login");
            return FALSE;
        }
		$id=$this->get('id');
		if(is_numeric($id) AND $id>0){
			$where = array('userid'=>$this->userid);
			$items=$this->m_ticket->Where($where)->SelectByID('',$id);
			$data = array();
			$data['items'] = $items;
			$data['title'] = "查看工单";
			$this->getView()->assign($data);
		}else{
            $this->redirect("/member/ticket/");
            return FALSE;
		}
	}
}