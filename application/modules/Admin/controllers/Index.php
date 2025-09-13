<?php

/*
 * 功能：后台中心－首页
 * 作者: ZFAKA
 * 日期: 2025-09-13
 */

class IndexController extends AdminBasicController
{
	private $github_url = "https://api.github.com/repos/ZFAKA/ZFAKA/releases";
	private $remote_version = '';
	private $m_order;
	private $versiondomain = "ver.zfaka.sql.pub";
	private $fallback_base = 'https://zk-cash.com/res';

	public function init()
	{
		parent::init();
		$this->m_order = $this->load('order');
	}

	public function indexAction()
	{
		if(file_exists(INSTALL_LOCK)){
			if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
				$this->redirect('/'.ADMIN_DIR."/login");
				return FALSE;
			}else{
				$version = @file_get_contents(INSTALL_LOCK);
				$version = str_replace(array("\r","\n","\t"), "", $version);
				$version = strlen(trim($version))>0?$version:'1.0.0';
				if(version_compare($this->normalizeVersion($version), $this->normalizeVersion(VERSION), '<' )){
					$this->redirect("/install/upgrade");
					return FALSE;
				}else{
					//这里要查询待处理的订单
					$data = array();
					$field = array('id','orderid','email','productname','addtime','status','paymoney','number');
					$where = array('isdelete'=>0);
					$where1 = "status = 1 or status = 3";
					$order = $this->m_order->Field($field)->Where($where)->Where($where1)->Order(array('id'=>'DESC'))->Select();
					$data['order'] = $order;
					$this->getView()->assign($data);
				}
			}
		}else{
			$this->redirect("/install/");
			return FALSE;
		}
	}

	public function updatecheckajaxAction()
	{
		if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
			$data = array('code' => 1000, 'msg' => '请登录');
			Helper::response($data);
		}
		$method = $this->getPost('method',false);
		if($method AND $method=='updatecheck'){
			if ($this->VerifyCsrfToken($csrf_token)) {
				$up_version = $this->getSession('up_version');
				if(!$up_version){
					$up_version = $this->_getUpdateVersion();
					$this->setSession('up_version',$up_version);
				}
				if(version_compare($this->normalizeVersion(VERSION), $this->normalizeVersion($up_version), '<' )){
					$params = array(
						'update'=>1,
						'url'=>$this->github_url,
						'zip'=>sprintf("https://github.com/ZFAKA/ZFAKA/releases/download/%s/ZFAKA-main.zip", $up_version),
						'fallback_zip'=>rtrim($this->fallback_base, '/').'/release.zip'
					);
					$data = array('code' => 1, 'msg' => '有更新','data'=>$params);
				}else{
					$params = array('update'=>0,'url'=>$this->github_url,'remote_version'=>$this->remote_version);
					$data = array('code' => 1, 'msg' => '没有更新','data'=>$params);
				}
			} else {
				$data = array('code' => 1001, 'msg' => '页面超时，请刷新页面后重试!');
			}
		}else{
			$data = array('code' => 1000, 'msg' => '丢失参数');
		}
		Helper::response($data);
	}

	private function _getUpdateVersion()
	{
		$version = VERSION;
		try{
			$version_json= $this->_get_url_contents($this->github_url);
			$arr = json_decode($version_json,true);
			if (is_array($arr) && count($arr) > 0) {
				$latest_version = $arr[0];
				if (isset($latest_version['tag_name'])) {
					$version = $latest_version['tag_name'];
				}
			}
			if (empty($version) || !$this->looksLikeValidVersion($version)) {
				$version = $this->_getVersionFromFallback();
			}
		} catch(\Exception $e) {
			$version = $this->_getVersionFromFallback();
		}
		return $version;
	}
	
	private function _get_url_contents($url,$params='')
	{
		if(is_array($params) AND !empty($params)){
			$url .= "?" . http_build_query($params);
		}

		$ch = curl_init($url);
		$headers = array(
			"Accept: application/vnd.github.v3+json",
			"User-Agent: ZFAKA-Updater/1.0"
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER , $headers );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20 );
		curl_setopt($ch, CURLOPT_HEADER, 0 ); // 不返回 header
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		$html =  curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$err = curl_error($ch);
		curl_close($ch);
		if ($html === false) {
			throw new \Exception('请求失败: '.$err);
		}
		if ($httpCode < 200 || $httpCode >= 300) {
			throw new \Exception('HTTP 错误: '.$httpCode.' '.$err);
		}
		return $html;
	}

	private function _getVersionFromFallback()
	{
		$version = VERSION;
		try{
			$url = rtrim($this->fallback_base, '/') . '/latest_version.txt';
			$body = $this->_get_url_contents($url);
			if ($body && strlen(trim($body))>0) {
				return trim($body);
			}
		} catch(\Exception $e) {
			// 忽略异常，返回本地 VERSION
		}
		return $version;
	}

	private function looksLikeValidVersion($v)
	{
	    if (empty($v)) return false;
	    $v = $this->normalizeVersion($v);
	    return preg_match('/^\d+(\.\d+)*/', $v) === 1;
	}

	private function normalizeVersion($v)
	{
	    $v = trim($v);
	    if (strlen($v) > 0 && ($v[0] == 'v' || $v[0] == 'V')) {
	        $v = substr($v, 1);
	    }
	    return $v;
	}


}