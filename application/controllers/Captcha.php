<?php
/*
 * 功能：图形验证码 重新更新
 * Author:资料空白
 * Date:20180604
 */
class CaptchaController extends BasicController
{

    public function init()
	{
		\Yaf\Dispatcher::getInstance()->disableView();
    }

    public function indexAction()
	{ 
        $t = $this->get('t');
		$l_captcha = new \Captcha\Captcha();
		$code=$l_captcha->getPhrase();
		$this->setSession($t.'Captcha',$code);
		header('Content-Type: image/jpeg');
		$l_captcha->build()->output();
		exit();
    }

    public function checkAction()
	{
		$code = $this->getPost('code');
        $t = $this->get('t');
		if($code AND strlen($code)>0){
			if(strtolower($this->getSession($t.'Captcha')) ==strtolower($code)){
				$this->unsetSession($t.'Captcha');
				$data=array('code'=>1,'msg'=>'success');
			}else{
				$data=array('code'=>0,'msg'=>'failed');
			}
		}else{
			$data=array('code'=>0,'msg'=>'丢失参数');
		}	
		Helper::response($data);
    }
}