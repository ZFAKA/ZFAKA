<?php
/**
 * File: F_Network.php
 * Author: 资料空白
 * Date: 2016-11-11再整理
 */
	/**
	 * Get client IP Address
	 */
if ( ! function_exists('getClientIP')){
	function getClientIP(){
		if(isset($_SERVER['HTTP_ALI_CDN_REAL_IP']) AND $_SERVER['HTTP_ALI_CDN_REAL_IP']){
			$ip = $_SERVER["HTTP_ALI_CDN_REAL_IP"]; 
		}elseif ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]) { 
			$ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]; 
		}elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]) { 
			$ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"]; 
		}elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"]) { 
			$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"]; 
		}elseif (getenv("HTTP_X_FORWARDED_FOR")) { 
			$ip = getenv("HTTP_X_FORWARDED_FOR"); 
		}elseif (getenv("HTTP_CLIENT_IP")) { 
			$ip = getenv("HTTP_CLIENT_IP"); 
		}elseif (getenv("REMOTE_ADDR")){ 
			$ip = getenv("REMOTE_ADDR"); 
		}else { 
			$ip = "Unknown"; 
		}
		//只取第一个
		$ip=explode(',',$ip)[0];
		
		if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6)) {
		    return $ip;
		}else {
		    return "invalid ip";
		}
		#return $ip;
	}
}

	/**
	 * Is visitor a spider ?
	 */
if ( ! function_exists('isSpider')){
	function isSpider(){

		if (empty($_SERVER['HTTP_USER_AGENT'])) {
			return '';
		}

		$searchengine_bot = array(
			'googlebot',
			'mediapartners-google',
			'baiduspider+',
			'msnbot',
			'yodaobot',
			'yahoo! slurp;',
			'yahoo! slurp china;',
			'iaskspider',
			'sogou web spider',
			'sogou push spider'
		);

		$searchengine_name = array(
			'GOOGLE',
			'GOOGLE ADSENSE',
			'BAIDU',
			'MSN',
			'YODAO',
			'YAHOO',
			'Yahoo China',
			'IASK',
			'SOGOU',
			'SOGOU'
		);

		$spider = strtolower($_SERVER['HTTP_USER_AGENT']);

		foreach ($searchengine_bot AS $key => $value) {
			if (strpos($spider, $value) !== false) {
				$spider = $searchengine_name[$key];

				return $spider;
			}
		}

		return '';
	}
}

	/**
	 *  Get user broswer type
	 */
if ( ! function_exists('getUserAgent')){
	function getUserAgent(){
        $user_agent = '';         
		$server_user_agent = $_SERVER["HTTP_USER_AGENT"];
		if(empty($server_user_agent)){
			return '';
		}elseif(strpos($server_user_agent,"MSIE")){
			if(strpos($server_user_agent,"MSIE 6.0")){
				$user_agent = 'IE6';
			}elseif(strpos($server_user_agent,"MSIE 7.0")){
				$user_agent = 'IE7';
			}elseif(strpos($server_user_agent,"MSIE 8.0")){
				$user_agent = 'IE8';
			}elseif(strpos($server_user_agent,"MSIE 9.0")){
				$user_agent = 'IE9';
			}elseif(strpos($server_user_agent,"MSIE 10.0")){
				$user_agent = 'IE10';
			}else{
				$user_agent = 'IE';
			}
		}elseif(strpos($server_user_agent,"Netscape")){
            $user_agent = 'Netscape';
        }elseif(strpos($server_user_agent,"Firefox")){
            $user_agent = 'Firefox';
        }elseif(strpos($server_user_agent,"MicroMessenger")){
            $user_agent = 'Weixin';		
        }elseif(strpos($server_user_agent,"Chrome")){
			if(strpos($server_user_agent,"UCBrowser")){
				$user_agent = 'UCBrowser';
			}elseif(strpos($server_user_agent,"QQBrowser")){
				$user_agent = 'QQBrowser';
			}elseif(strpos($server_user_agent,"BIDUBrowser")){
				$user_agent = 'BIDUBrowser';
			}elseif(strpos($server_user_agent,"LBBROWSER")){
				$user_agent = 'LBBROWSER';
			}elseif(strpos($server_user_agent,"MetaSr")){
				$user_agent = 'SOGOU';	
			}elseif(strpos($server_user_agent,"Maxthon")){
				$user_agent = 'Maxthon';
			}elseif(strpos($server_user_agent,"TheWorld")){
				$user_agent = 'TheWorld';
			}elseif(strpos($server_user_agent,"Edge")){
				$user_agent = 'IE Edge';					
			}else{
				$user_agent = 'Chrome';
			}
			return $user_agent;
        }elseif(strpos($server_user_agent,"Safari")){
            $user_agent = 'Safari';
        }elseif(strpos($server_user_agent,"Opera")){
            $user_agent = 'Opera';
        }elseif(strpos($server_user_agent,"UCWEB")){
            $user_agent = 'UCWEB';
        }else{
            $user_agent = 'other';
		}
        return $user_agent;
	}
}

	/**
	 *  Get user OS
	 */
if ( ! function_exists('getUserOS')){
	function getUserOS() {
		if (empty($_SERVER['HTTP_USER_AGENT'])) {
			return 'Unknown';
		}

		$os = '';
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);

		if (strpos($agent, 'win') !== false) {
			if (strpos($agent, 'nt 10.0') !== false) {
				$os = 'Windows 10';
			}elseif (strpos($agent, 'nt 6.1') !== false) {
				$os = 'Windows 7';
			}elseif (strpos($agent, 'nt 5.1') !== false) {
				$os = 'Windows XP';
			} elseif (strpos($agent, 'nt 5.2') !== false) {
				$os = 'Windows 2003';
			} elseif (strpos($agent, 'nt 5.0') !== false) {
				$os = 'Windows 2000';
			} elseif (strpos($agent, 'nt 6.0') !== false) {
				$os = 'Windows Vista';
			} elseif (strpos($agent, 'nt') !== false) {
				$os = 'Windows NT';
			}
		}elseif(strpos($agent, 'android') !== false){	
			$os = 'Android';
		} elseif (strpos($agent, 'linux') !== false) {
			$os = 'Linux';
		} elseif (strpos($agent, 'mac') !== false) {
			$os = 'Apple';
		} else {
			$os = 'Unknown';
		}
		return $os;
	}
}

if ( ! function_exists('isMobile')){
	function isMobile(){ 
	  	$phonesregex = '/android .+ mobile|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i';
		$modelsregex = '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i';
		return (preg_match($phonesregex, $_SERVER['HTTP_USER_AGENT']) || preg_match($modelsregex, substr($_SERVER['HTTP_USER_AGENT'], 0, 4)));
	}
}

if ( ! function_exists('isWeixin')){
	function isWeixin(){
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
			return true;
		}
		return false;
	}
}

//通过URL获取域名
if ( ! function_exists('getDomainByUrl')){
	function getDomainByUrl($url){
		$domain='';
		if($url){
			$url = str_replace("http://","",$url);
			$url = str_replace("https://","",$url);
			if($url){
				$strdomain = explode("/",$url);
				if(is_array($strdomain) AND !empty($strdomain) AND isset($strdomain[0])){
					$domain = $strdomain[0];
				}	
			}
		}
		return $domain;
	}
}

//通过淘宝接口获取IP归属地
if ( ! function_exists('getIpInfo')){
	function getIpInfo($ip){
		if($ip){
			$url='http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT,30);
			$result=curl_exec($ch);
			curl_close($ch);
			if($result){
				$result=json_decode($result,true);
				if(!$result['code']){
					if(isset($result['data']['region']) AND isset($result['data']['city'])){
						if($result['data']['region']){
							return array('ip'=>$ip,'region'=>$result['data']['region'],'city'=>$result['data']['city']);
						}else{
							return array('ip'=>$ip,'region'=>$result['data']['country'],'city'=>$result['data']['country']);
						}
					}
				}
			}
		}
		return false;	
	}
}
//地址处理
if (!function_exists('siteUrl')){
    function siteUrl($domain,$path='',$str='',$http=FALSE){
		//1.处理HTTP HTTPS问题
		if($http){
			$http='http://';
		}else{
			if(isHttps()){
				$http='https://';
			}else{
				$http='http://';
			}			
		}

		if($domain==''){
			$domain =$_SERVER['HTTP_HOST'];
		}	
		//2.判断是否只是处理域名URL并组装
		if($path=='' AND $str==''){
			$domain = str_replace("http://","",$domain);
			$domain = str_replace("https://","",$domain);
			$url=$domain;		
		}elseif($str==''){
			$domain = getDomainByUrl($domain);
			$url=$domain.'/'.$path;
		}elseif($path==''){
			$domain = getDomainByUrl($domain);
			$url=$domain.'/?'.$str;
		}else{
			$domain = getDomainByUrl($domain);
			$url=$domain.'/'.$path.'/?'.$str;
		}
		//4.去 // ??
		$url = str_replace("//","/",$url);
		$url = str_replace("??","?",$url);
		$url = $http.$url;
		return $url;
    }
}
//判断是否为https
if ( ! function_exists('isHttps')){
	function isHttps(){
		if(isset($_SERVER['HTTPS']) AND ! empty($_SERVER['HTTPS']) AND strtolower($_SERVER['HTTPS']) !== 'off') {
			return TRUE;
		}elseif(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) AND strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https'){
			return TRUE;
		}elseif(isset($_SERVER['HTTP_FRONT_END_HTTPS']) AND !empty($_SERVER['HTTP_FRONT_END_HTTPS']) AND strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off'){
			return TRUE;
		}elseif(isset($_SERVER['SERVER_PORT']) AND $_SERVER['SERVER_PORT'] == 443){
			return TRUE;  
		}elseif(isset($_SERVER['HTTP_X_CLIENT_SCHEME']) AND strtolower($_SERVER['HTTP_X_CLIENT_SCHEME']) =='https'){
			return TRUE;  
		}else{
			return FALSE;
		}
	}
}

/**
 * 是否是AJAx提交的
 * @return bool
 */
if (!function_exists('isAjax')){
	function isAjax(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			return true;
		}else{
			return false;
		}
	}
}
/**
 * 是否是GET提交的
 */
if (!function_exists('isGet')){
	function isGet(){
		return strtolower($_SERVER['REQUEST_METHOD']) == 'get' ? true : false;
	}
}
/**
 * 是否是POST提交
 * @return int
 */
if (!function_exists('isPost')){
	function isPost() {
		return ($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SERVER['HTTP_REFERER']) || preg_replace("~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("~([^\:]+).*~", "\\1", $_SERVER['HTTP_HOST']))) ? 1 : 0;
	}
}

if (!function_exists('curlPost')){
	function curlPost($url,$params){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,300); //设置超时
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		$result=curl_exec($ch);
		curl_close($ch);
		return $result;	
	}
}	
