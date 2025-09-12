<?php
/**
 * File: zfbweb.php
 * Functionality: 支付宝面对面支付
 * Author: 资料空白
 * Date: 2018-6-29
 */
namespace Pay\zfbweb;

use \Payment\Notify\PayNotifyInterface;

use \Pay\notify;

class callback implements PayNotifyInterface
{
	
	//处理返回回调callback
	public function notifyProcess(array $params)
	{
		if($params['body']=='zfbweb'){
			$config = array('paymethod'=>$params['body'],'tradeid'=>$params['trade_no'],'paymoney'=>$params['total_amount'],'orderid'=>$params['out_trade_no'] );
			$notify = new \Pay\notify();
			$data = $notify->run($config);
		}else{
			$data =array('code'=>1002,'msg'=>'支付方式不对');
		}
		return $data;
	}
	
}