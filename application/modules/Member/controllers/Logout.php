<?php

/*
 * 功能：会员中心－退出类
 * Author:资料空白
 * Date:20150902
 */

class LogoutController extends MemberBasicController
{

    public function init()
    {
        parent::init();
        \Yaf\Dispatcher::getInstance()->disableView();
    }

    public function indexAction()
    {
        if (!$this->login OR !$this->userid) {
            $this->redirect("/member/login/");
            return FALSE;
        }
        $referer_url = $this->get('referer_url', false);
        $this->unsetSession('uinfo');
        $referer_url = $referer_url ? $referer_url : '/member/login/';
        $referer_url = str_replace('//', '/', $referer_url);
        $this->redirect($referer_url);
    }
}