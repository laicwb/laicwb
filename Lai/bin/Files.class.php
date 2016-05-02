<?php
/**
 * 文件操作类
 */
class Files{
	/**
	 * 设置存放或读取文件目录的路径
	 */
	static public $file = null;
	/**
	 * 设置存放或读取文件的文件名
	 */
	static public $fileName = null;
	/**
	 * 返回完整的文件路径
	 */
	static public $filePath = null;
	
	/**
	 * 读取文件内容
	 * @access public
	 * 
	 */
	static public function getFile(){
		//组合成完整的路径
		self::pathFile();
		//一次性读取文件内容
		return file_get_contents(self::$filePath);
	}
	/**
	 * 写入文件内容
	 * @access public
	 * @param string $str 要写入的内容
	 * @return string
	 */
	static public function setFile($str){
		//组合成完整的路径
		self::pathFile();
		//一次性写入文件内容
		return file_put_contents(self::$filePath, $str);

	}
	/**
	 * 判断存放或读取文件的路径
	 */
	static private function isFile(){
		if(is_null(self::$file)){
			self::$file = PUB_DIR;
		}else{
			if(!is_dir(self::$file)){
				die('File: '.self::$file.' Not directory!');
			}
		}
	}
	/**
	 * 判断是否已定义存放或读取文件的文件名,没有以当天日期
	 */
	static private function isFileName(){
		if(is_null(self::$fileName)){
			self::$fileName = date('Y-m-d').'-'.uniqid().'.txt';
		}
	}
	/**
	 * 组合成完整的路径
	 */
	static private function pathFile(){
		self::isFile();
		self::isFileName();
		self::$filePath = self::$file.self::$fileName;
	}
	
	
}