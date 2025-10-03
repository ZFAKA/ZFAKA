<?php
/**
 * File: vpaywx.php
 * Functionality: V免签 -微信扫码支付
 * Author: 
 * Date: 2025-05-13
 */
namespace Pay\vpaywx;
use \Pay\notify;

class vpaywx
{
    private $paymethod ="vpaywx";

    //处理请求
    public function pay($payconfig,$params)
    {
        try {
            $payGateWay = $payconfig['configure3'];
            $payGateWay = rtrim($payGateWay, '/') . '/';
	        $payGateWay = $payGateWay . 'createOrder';
            $payId =$params['orderid'];
            $type  =1;//微信
            $price =(float)$params['money'];
            $param =$params['weburl'] . '/product/notify/?paymethod=' . $this->paymethod;  //支付成功后回调地址
            $key   =$payconfig['app_secret'];
            $isHtml=0;
            $return_url = $params['weburl']. "/query/auto/{$params['orderid']}.html";  //同步地址
            $notify_url = $params['weburl'] . '/product/notify/?paymethod=' . $this->paymethod;  //支付成功后回调地址
            $sign  = md5($payId . $param . $type . $price . $key);

            $config =array(
                'payId'=>$payId,
                'type'=>$type,
                'price'=>$price,
                'sign'=>$sign,
                "param" =>$param,
                "isHtml"=>$isHtml,
                "return_url"=>$return_url,
                'notifyUrl' => $notify_url,
            );

            $ch = curl_init(); //使用curl请求
            curl_setopt($ch, CURLOPT_URL,  $payGateWay);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $config);
            $tmdpay_json = curl_exec($ch);
            curl_close($ch);

            $tmdpay_data = json_decode($tmdpay_json,true);

            if(is_array($tmdpay_data)) {
                if($tmdpay_data['code']<1)
                {
                    return array('code'=>1002,'msg'=>$tmdpay_data['msg'],'data'=>'');
                } else {
                    $qr = $tmdpay_data['data']['payUrl'];
                    $money = isset($tmdpay_data['data']['reallyPrice'])?$tmdpay_data['data']['reallyPrice']:$params['money'];
                    //计算关闭时间
                    $closetime = $payconfig['overtime'];
                    $result = array('type'=>0,'subjump'=>0,'subjumpurl'=>$tmdpay_data['data']['payUrl'],'paymethod'=>$this->paymethod,'qr' => $params['qrserver'] . urlencode($tmdpay_data['data']['payUrl']),'payname'=>$payconfig['payname'],'overtime'=>$closetime,'money'=>$money);
                    return array('code'=>1,'msg'=>'success','data'=>$result);
                }
            }else {
                return array('code'=>1001,'msg'=>"支付接口请求失败",'data'=>'');
            }
        }
        catch (\Exception $e)
        {
            return array('code'=>1000,'msg'=>$e->getMessage(),'data'=>'');
        }
    }


    //处理返回
    public function notify($payconfig)
    {
        file_put_contents(YEWU_FILE, CUR_DATETIME . '-VPAYWX-NOTIFY-' . json_encode($_GET) . PHP_EOL, FILE_APPEND);

        if (!empty($_GET)) {
            $payId = $_GET['payId'] ?? '';
            $param = $_GET['param'] ?? '';
            $type = $_GET['type'] ?? '';
            $price = $_GET['price'] ?? '';
            $reallyPrice = $_GET['reallyPrice'] ?? '';
            $sign = $_GET['sign'] ?? '';
            $key = $payconfig['app_secret'];

            $config = array(
                'paymoney' => $reallyPrice,
                'paymethod' => $this->paymethod,
                'orderid' => $payId,
                'tradeid' => $param,
            );

            // Verify signature
            $calculated_sign = md5($payId . $param . $type . $price . $reallyPrice . $key);
            if ($sign === $calculated_sign) {
                $notify = new \Pay\notify();
                $data = $notify->run($config);
                if ($data['code'] > 1) {
                    return 'error|Notify: ' . $data['msg'];
                } else {
                    return 'success';
                }
            } else {
                return 'error|Notify: invalid';
            }
        }
    }

}
