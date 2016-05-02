<?php
/**
 * 解析URL的PATHINFO模式
 */
final class Url{
	//保存PATHINFO信息
	protected static $pathinfo;
	
	/**
	 * 解析URL
	 */
	public static function pathUrl(){
		if(self::getpathinfo()){
			$info = explode('/', self::$pathinfo);
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
			//
			$_GET = $get;
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * 解析PATHINFO
	 */
	protected static function getpathinfo(){
		//获得pathinfo的兼容模式GET变量
		if(!empty($_GET[C('PATHINFO_VAR')])){
			$info = $_GET[C('PATHINFO_VAR')];
		}elseif(!empty($_SERVER['PATH_INFO'])){
			//获得pathinfo的模式
			$info = $_SERVER['PATH_INFO'];
		}else{
			return FALSE;
		}
		
		//伪静态扩展名
		$pathinfo_html = '.'.C('PATHINFO_HTML');
		//取得最后一个 . 后的字符串
		$_html = strrchr($info, '.');
		if($_html != FALSE){
			//伪静态扩展名是否符合
			if(($pathinfo_html != $_html) && (strpos($_html, '/') == FALSE)){
				//显示错误信息
				error404();
				exit;
				//return FALSE;
			}
		}
		
		//获得pathinfo (去掉伪静态扩展名并再次去掉两边的的 /)
		$info = trim(str_ireplace($pathinfo_html, '', $info),'/');
		//查找是否有 /
		if(strpos($info, '/') == FALSE){
			return FALSE;
		}
		
		self::$pathinfo = $info;
		return TRUE;
	}
}
