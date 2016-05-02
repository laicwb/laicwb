<?php
/**
 * 日志操作类
 */
class Log{
	
	/**
	 * 写日志
	 * @access public
	 * @param string $str  日志内容
	 */
	public static function write($str){
		//给日志内容追加换行
		$str .="\r\n";
		//判断日志大小并返回日志的路径
		$log = self::isBak();
		//打开日志文件
		$fh = fopen($log, 'ab');
		//写入日志文件
		fwrite($fh, $str);
		//关闭打开的日志文件
		fclose($fh);
	}
	
	/**
	 * 备份日志
	 * 以 年-月-日 的形式备份
	 * @param $log string 日志的路径
	 * @return bool 是否备份成功
	 */
	private static function bak($log){
	 	//备份日志的路径
	 	$bak = LOG_DIR.date('Y-m-d-').uniqid().'.bak';
		//进行文件改名备份并返回
	 	return rename($log, $bak);
	}
	
	 /**
	  * 读取并判断日志的大小
	  * @return string 返回日志的路径
	  */
	 private static function isBak(){
	 	//日志的路径
	  	$log = LOG_DIR.C('LOGNAME');
		//判断日志文件是否存在，不存在则创建文件日志并返回
		if(!file_exists($log)){
			touch($log);
			return $log;
		}
		//获取日志文件大小
		$size = filesize($log);
		//如果日志文件大小小于配置大小就返回
		if($size <= C('LOGSIZE')){
			return $log;
		}
		//进行备份日志,如果备份失败则继续写入
		if(!self::bak($log)){
			return $log;
		}else{
			//备份成功则创建文件日志并返回
			touch($log);
			return $log;
		}
		
	 }
	 
}
