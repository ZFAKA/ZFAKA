<?php
/**
 * File: F_Array.php
 * Author: 资料空白
 * Date: 2016-11-11再整理
 */

//检查是否为多级数组
if (!function_exists('isMultiArray')){
	function isMultiArray($arr){
		if(empty($arr)) return false;
		if(!is_array($arr)){
			return false;
		}else{
			foreach($arr as $a){
				if(is_array($a)){
					return true;
				}
			}
			return false;
		}
	}
}

//多数组排重
if (!function_exists('multiArrayUnique')){
	function multiArrayUnique($arr){
		foreach($arr as $k => $v){
			foreach($arr as $key => $value){
				if($k != $key){
					$v1 = json_encode($v);
					$v2 = json_encode($value);
					if($v1 == $v2){
						unset($arr[$k]);
					}
				}
			}
		}
		sort($arr);
		return $arr;
	}
}

//对象转化数组
if (!function_exists('object2Array')){
	function object2Array($obj){
		if(is_object($obj)){
			$obj = get_object_vars($obj);
		}
		return is_array($obj) ? array_map(__FUNCTION__, $obj):$obj;
	}
}

//数组转换成对象
if (!function_exists('array2Object')){
	function array2Object($arr) {
		return is_array($arr) ? (object) array_map(__FUNCTION__, $arr):$arr;
	}
}

// 通过数组的值获取数组的 key
if (!function_exists('getArrayKey')){
	function getArrayKey($arr, $value){
	 	if(!is_array($arr)){
	 		return null;
	 	}
	 	foreach($arr as $k =>$v){
	  		$return = getArrayKey($v, $value);
	  		if($v == $value){
	   			return $k;
	  		}
	  		if(!is_null($return)){
	   			return $return;
	  		}
	 	}
	}
}
//判断某个字符串是否包含一个数组中的某个值
if (!function_exists('check_in')){
	function check_in($arr, $text){
	    if(!is_array($arr)){
	     	return null;
	    }
	    foreach($arr as $key){
			if(strstr($text, $key) != ''){
				$result = $key;
				break;
			}
		}
		if($result == ''){
			foreach($arr as $key){
				if(strstr($text, mb_substr($key, 0, 1, 'utf-8')) != ''){
					$result = $key;
					break;
				}
			}
		}
		return $result;
	}
}

//以数组的形式保存数组
if (!function_exists('arraySave')){
	function arraySave($array, $file, $arrayName = false) {
		$data = var_export($array, true);
		if (!$arrayName) {
		   $data = "<?php\n return " .$data.";\n?>";
		} else {
		   $data = "<?php\n " .$arrayName . "=" .$data . ";\n?>";
		}
		return file_put_contents($file, $data);
	}
}
/**
*  说明:二维数组去重
*  @param    array2D    要处理二维数组
*  @param    stkeep     是否保留一级数组键值(默认不保留)
*  @param    ndformat   是否保留二级数组键值(默认保留)
*  @return   output     返回去重后的数组
*/
if (!function_exists('unique_arr')){
	function unique_arr($array2D, $stkeep = false, $ndformat = true) {
		if($stkeep){    //一级数组键可以为非数字
			$stArr = array_keys($array2D);
		}
		
		if($ndformat){   //二级数组键必须相同
			$ndArr = array_keys(end($array2D));
		}
		
		foreach ($array2D as $v){  //降维
			$v = join(',', $v);
			$temp[] = $v;
		}
		
		$temp = array_unique($temp);
		foreach ($temp as $k => $v){  //数组重新组合
			if($stkeep){
				$k = $stArr[$k];
			}
			
			if($ndformat){
				$tempArr = explode(",",$v);
				foreach($tempArr as $ndkey => $ndval){
					$output[$k][$ndArr[$ndkey]] = $ndval;
				}
			}else{
				$output[$k] = explode(",",$v);
			}
		}
		return $output;
	}
}
/**
 * 
 * 将二维数组转换成一维数组
 * @param array $array 待转换的二维数组
 * @param string $glue 需要转换的键  如id
 */
if (!function_exists('swapDoubleToSingle')){
	function swapDoubleToSingle($array, $glue){
	    $tmp = array();
		if($array) {
			foreach($array as $v) {
				$tmp[] = $v[$glue];
			}
		}
		
	    return $tmp;
	}
}    
/**
 * 
 * 将二维数组转换成对应格式的二维数组
 * @param array $array 待转换的二维数组
 * @param string $glue 需要转换的键,如id
 */
if (!function_exists('swapDoubleToDouble')){
	function swapDoubleToDouble($array, $glue){
	    $tmp = array();
		if($array) {
			foreach($array as $v) {
				$tmp[$v[$glue]] = $v;
			}
		}
	    return $tmp;
	}
}