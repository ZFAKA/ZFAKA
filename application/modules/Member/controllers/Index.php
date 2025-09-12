<?php

/*
 * 功能：会员中心－首页
 * Author:资料空白
 * Date:20180509
 */

class IndexController extends MemberBasicController
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        if ($this->login==FALSE AND !$this->userid) {
            $this->redirect("/member/login");
            return FALSE;
        }
		$data = array();
		$data['title'] = "我的主页";
		$this->getView()->assign($data);
    }
}