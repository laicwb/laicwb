<?php
/**
 * 防止非法加载
 */
defined('LAIACC') || die('ERROR: 无法加载此文件...');

/**
 * 用于初始化配置项的
 * @param $name string/array 用于配置项名或者是组数
 * @param $value string/array 用于配置项名的值
 * @return string/array 用于返回数据
 */
function C($name = null, $value = null) {
	//用于存放配置项的数据
	static $info = array();
	//判断配置名是否为null,, 为null就返回配置项所有数据
	if (is_null($name)) {
		return $info;
	}
	//判断配置名是否为数组,,为数组就合并数据
	if (is_array($name)) {
		$info = array_merge($info, $name);
	}
	//判断配置名是否为字符串
	if (is_string($name)) {
		//判断配置项名的值是否为null,,为null就是取值,,不为null就是赋值
		if (is_null($value)) {
			return isset($info[$name]) ? $info[$name] : false;
		} else {
			$info[$name] = $value;
		}
	}
}

/**
 * 用于递归转义数组
 * @param array $arr为要转义的数组
 * @return array
 */
function _addslashes($arr) {
	//编历数组
	foreach ($arr as $k => $v) {
		if (is_string($v)) {
			//判断是否为字符串,是为转义
			$arr[$k] = addslashes($v);
		} else if (is_array($v)) {
			//判断是否为数组,是数组就递归调用自己
			$arr[$k] = _addslashes($v);
		}
	}
	//返回转义的数组
	return $arr;
}

/**
 * 加密字符串
 * @param string $str	加密的字符串
 * @param string $key	加密的密钥
 * @return string
 */
function encrypt($data, $key) {
	$char = '';
	$str = "";
	$key = md5($key);
	$x = 0;
	$len = strlen($data);
	$l = strlen($key);
	for ($i = 0; $i < $len; $i++) {
		if ($x == $l) {
			$x = 0;
		}
		$char .= $key[$x];
		$x++;
	}
	for ($i = 0; $i < $len; $i++) {
		$str .= chr(ord($data[$i]) + (ord($char[$i])) % 256);
	}
	return base64_encode($str);
}

/**
 * 解密字符串
 * @param string $str	解密的字符串
 * @param string $key	解密的密钥
 * @return string
 */
function decrypt($data, $key) {
	$char = '';
	$str = "";
	$key = md5($key);
	$x = 0;
	$data = base64_decode($data);
	$len = strlen($data);
	$l = strlen($key);
	for ($i = 0; $i < $len; $i++) {
		if ($x == $l) {
			$x = 0;
		}
		$char .= substr($key, $x, 1);
		$x++;
	}
	for ($i = 0; $i < $len; $i++) {
		if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
			$str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
		} else {
			$str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
		}
	}
	return $str;
}

/**
 * 生成URL地址
 * @param string $str	文件名
 * @param array $arr	参数(GET)以关联的一维数组
 * @param bool $html	是否要伪静态扩展名(TRUE)
 * @return string
 */
function U($str, $arr=array(), $html=TRUE) {
	if (empty($str)) {
		return FALSE;
	}
	if(empty($arr)){
		return '/'.$str . '.php';
	}
	if (C('PATHINFO')) {
		//以pathinfo的格式

		//组合字符串
		$tmp = '/'.$str . '.php/';
		foreach ($arr as $key => $value) {
			$tmp .= $key . '/' . $value . '/';
		}
		//去掉最后的/
		$tmp = substr($tmp, 0, -1);
		//组合伪静态扩展名
		if($html){
			$tmp .= '.' . C('PATHINFO_HTML');
		}
		return $tmp;
	} 

	//普通GET传参
	$tmp = '/'.$str . '.php?' . http_build_query($arr);
	return $tmp;
}
/**
 * 获取IP
 * @return string
 */
function get_ip(){
	if(isset($_SERVER['REMOTE_ADDR'])){
		return $_SERVER['REMOTE_ADDR'];
	}elseif(isset($_SERVER['HTTP_VIA'])){
		return $_SERVER['HTTP_VIA'];
	}elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	return '0.0.0.0';
}
/**
 * 判断验证码是否正确
 * @param string $str 要验证的字符串
 * @return bool
 */
function is_code($str){
	if(empty($str)){
		return FALSE;
	}
	//转换小写字符串
	$str = strtolower($str);
	if($_SESSION['getcode'] == $str){
		return TRUE;
	}
	return FALSE;
}


/**
 * 弹出错误信息并返回
 * @param string $str 错误信息
 */
function alertback($str){
	echo '<script type="text/javascript">alert("'.$str.'");history.back();</script>';
	exit;
}
/**
 * 弹出错误信息并返回
 * @param string $str	错误信息
 * @param string $str	跳转地址的文件名
 * @param array $arr	参数(GET)以关联的一维数组
 * @param bool $html	是否要伪静态扩展名(TRUE)
 */
function alerthref($str,$url='',$arr=array(),$html=TRUE){
	//弹出错误信息
	echo '<script type="text/javascript">alert("'.$str.'");</script>';
	//跳转地址
	if(!empty($url)){
		if(empty($arr)){
			echo '<script type="text/javascript">location.href="'.U($url).'";</script>';
		}else{
			echo '<script type="text/javascript">location.href="'.U($url,$arr,$html).'";</script>';
		}
	}
	exit;
}
/**
 * 解析pathinfo的模式的字符串(/id/1/age/55)
 * @param string $str 字符串(/id/1/age/55)
 * @return array
 */
function painfo($str=''){
	//
	if(empty($str)){
		if(empty($_SERVER['PATH_INFO'])){
			return FALSE;
		}
		$info =  trim($_SERVER['PATH_INFO'],'/');
	}else{
		$info =  trim($str,'/');
	}
		
	//查找是否有 /
	if(strpos($info, '/')){
		//伪静态扩展名
		$pathinfo_html = '.'.C('PATHINFO_HTML');
		//获得pathinfo (去掉伪静态扩展名并再次去掉两边的的 /)
		$info = trim(str_ireplace($pathinfo_html, '', $info),'/');
		
		$info = explode('/', $info);
		//取得拆分后的个数
		$couns = count($info);
		//如果不是成对出现就移出最后一个
		if($couns % 2 != 0){
			array_pop($info);
			//重新取得拆分后的个数
			$couns = count($info);
		}
		
		$get = array();
		//每隔两个取
		for($i=0;$i<$couns;$i+=2){
			$get[$info[$i]] = $info[$i+1];
		}
		return $get;
	}
	return FALSE;
}
/**
 * 显示错误信息
 */
function error404(){
	include ROOT.'tpl/err404.php';
	exit;
}






