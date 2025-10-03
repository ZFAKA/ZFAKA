<?php
/**
 * File: uzhifu.php
 * Functionality: USDT pay -USDT支付
 * Author: 
 * Date: 2025-08-13
 */
namespace Pay\uzhifu;
use \Pay\notify;

class uzhifu
{
	private $paymethod ="uzhifu";
	
	//处理请求
	public function pay($payconfig,$params)
	{
		try
		{
	        $payGateWay= $payconfig['configure3'];
            $store_url = parse_url($payGateWay, PHP_URL_PATH);
            preg_match('/[^\/]+(?=\/?$)/', $store_url, $matches);
            $store_slug = $matches[0] ?? '';
            $payGateWay = rtrim($payGateWay, '/') . '/';
            $payGateWayBuy = $payGateWay . 'usdtapi/buy';
            $orderid =$params['orderid'];
            $pid=$params['pid'];
            $quantity=$params['quantity'];
            $email=$params['email'];
            $chapwd=$params['chapwd'];
            $price =(float)$params['price'];
            $money =(float)$params['money'];
            $productname = $params['productname'];
            $return_url = $params['weburl']. "/query/auto/{$params['orderid']}.html";  //同步地址
            $notify_url = $params['weburl'] . '/product/notify/?paymethod=' . $this->paymethod;  //支付成功后回调地址

            $key = $payconfig['app_secret'];
            $sign = md5($orderid . 'uzhifu' . $key);

			$config =array(
                'pid'=>$pid,
                'orderid'=>$orderid,
                'quantity'=>1,
                'store_slug'=>$store_slug,
                'email'=>$email,
                'chapwd'=>$chapwd,
                'price'=>$price,
                'money'=>$money,
                'productname'=>$productname,
				"return_url"=>$return_url,
				'notifyUrl' => $notify_url,
                'sign' => $sign,
            );

			$ch = curl_init(); //使用curl请求
            curl_setopt($ch, CURLOPT_URL,  $payGateWayBuy);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $config);
            $return_json = curl_exec($ch);
            curl_close($ch);
            $order_created = false;
            $return_data = json_decode($return_json,true);
			if(is_array($return_data))
			{
				if($return_data['code']<1)
				{
					return array('code'=>1002,'msg'=>$return_data['msg'],'data'=>'');
				} elseif($return_data['code']==1){
                    $order_created = true;
                } else{
                    return array('code'=>1002,'msg'=>$return_data['msg'],'data'=>'');
				}
			}else
			{
				return array('code'=>1001,'msg'=>"支付接口请求失败",'data'=>'');
			}

            if($order_created){
                $oid = $return_data['data']['oid'];
                $ch = curl_init(); //使用curl请求
                $payGateWayPay = $payGateWay . 'order/dopay';
                $config = array(
                    'oid' => $oid,
                    'paymethod' => 'usdtapi'
                );
                curl_setopt($ch, CURLOPT_URL, $payGateWayPay);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $config);
                $return_json = curl_exec($ch);
                curl_close($ch);
                $return_data = json_decode($return_json,true);
                if(is_array($return_data))
			    {
				    if($return_data['code']<1)
				    {
					    return array('code'=>1002,'msg'=>$return_data['msg'],'data'=>'');
				    }elseif($return_data['code']==1){
                        $qr_url = $return_data['data']['qr'];
                        $urlParts = parse_url($qr_url);
                        $queryParams = [];
                        if(isset($urlParts['query'])){
                            parse_str($urlParts['query'], $queryParams);
                            if (isset($queryParams['url'])){
                                $decoded = urldecode($queryParams['url']);
                                $address = str_replace('tron:', '', $decoded);
                                $address = strstr($address, '?', true) ?: $address;
                                $queryParams['url'] = $address;
                            }
                        }
                        $newUrl = $urlParts['path'];
                        if(!empty($queryParams)){
                            $newUrl .= '?' . http_build_query($queryParams);
                        }
                        $wallet_address = $return_data['data']['payment_wallet'];
                        $result = array('type'=>0,'paymethod'=>'uzhifu','wallet_address'=>$wallet_address,'payname'=>'USDT(TRC20)','qr'=>$newUrl,'money'=>$return_data['data']['money'],'overtime'=>$return_data['data']['overtime']);
                        return array('code'=>1,'msg'=>'success','data'=>$result);
                    }
				    else {
                        return array('code'=>1002,'msg'=>$return_data['msg'],'data'=>'');
				    }
			    }else
			    {
				    return array('code'=>1001,'msg'=>"支付接口请求失败",'data'=>'');
			    }
            } else {
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
        file_put_contents(YEWU_FILE, CUR_DATETIME . '-USDT-NOTIFY-' . json_encode($_POST) . PHP_EOL, FILE_APPEND);
        
        // Only accept POST requests
        if(!$_POST) {
            $data = array('code' => 1000, 'msg' => 'Invalid request method');
            return $data['msg'];
        }

        if (empty($_POST['orderid']) || empty($_POST['paymethod']) || empty($_POST['paymoney']) || empty($_POST['sign'])){
            $data = array('code' => 1000, 'msg' => 'Missing required parameters');
            return $data['msg'];
        }

        $orderid = $_POST['orderid'];
        $paymethod = $_POST['paymethod'];
        $paymoney = $_POST['paymoney'];
        $sign = $_POST['sign'];
        $key = $payconfig['app_secret'];
        $calculated_sign = md5($orderid . $paymethod . $key . $paymoney);
        if($calculated_sign === $sign){
            $config = array(
                'paymethod' => $paymethod,
                'tradeid'   => isset($_POST['tradeid']) ? $_POST['tradeid'] : $_POST['orderid'],
                'paymoney'  => $paymoney,
                'orderid'   => $orderid,
            );
            $notify = new \Pay\notify();
            $data = $notify->run($config);

            if ($data['code'] > 1) {
                return 'error|Notify: ' . $data['msg'];
            } else {
                return 'success';
            }
        } else{
            return 'error|Notify: invalid';
        }
    }
	
} 
