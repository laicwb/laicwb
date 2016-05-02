<?php
/**
 * 上传文件类
 */
class Uploadfile{
	//设置附件上传类型 上传错误提示错误信息
	protected $exts = array('jpg','jpeg','gif','bmp','png');
	//设置附件上传大小以MB单位
	protected $maxSize = 5;
	//错误代码
	protected $errno = 0;
	//错误信息
	protected $error = array(
			0=>'无错误',
			1=>'上传文件超出系统限制',
			2=>'上传文件大小超出网页表单页面',
			3=>'文件只有部分被上传',
			4=>'没有文件被上传',
			6=>'找不到临时文件夹',
			7=>'文件写入失败',
			8=>'不允许的文件后缀',
			9=>'文件大小超出的类的允许范围',
			10=>'创建目录失败',
			11=>'移动失败'
	);
	
	/**
	 * 拦截器
	 */
	public function __get($name){
		return false;
	}
	public function __set($name,$value){
		if($name == 'exts' || $name == 'maxSize'){
			$this->$name = $value;
			return true;
		}
		return false;
	}
	
	
	/**
	 * 单文件上传
	 * @param string $name 上传的文件名
	 * @return string 上传后的文件路径
	 */
	public function uploadOne($name){
		//判断有没有该文件
		if(!isset($_FILES[$name])){
			return false;
		}
		//存放准备上传文件的信息
		$file = $_FILES[$name];
		
		//检验上传有没有成功
		if($file['error']){
			$this->errno = $file['error'];
			return false;
		}
		
		//获取文件后缀
		$ext = $this->getExt($file['name']);
		//检查文件后缀
		if(!$this->inAllowExt($ext)){
			$this->errno = 8;
			return false;
		}
		//检查大小
		if(!$this->isAllowSize($file['size'])){
			$this->errno = 9;
			return false;
		}
		//按日期创建目录
		$dir = $this->mk_dir();
		if($dir == false){
			$this->errno = 10;
			return false;
		}
		//生成随机文件名
		$newname = $dir.'/'.$this->randName().'.'.$ext;
		//移动
		if(!move_uploaded_file($file['tmp_name'], $newname)){
			$this->errno = 11;
			return false;
		}
		//返回去掉项目路径
		return str_replace(dirname(ROOT),'',$newname);
	}
	
	/**
	 * 获取文件后缀
	 * @param string $file
	 * @return string 后缀
	 */
	protected function getExt($file){
		$tmp = explode('.', $file);
		return end($tmp);
	}
	
	/**
	 * 判断文件的后缀(支持)
	 * @param string $ext
	 * @return bool
	 */
	protected function inAllowExt($ext){
		return in_array(strtolower($ext), $this->exts);
	}
	
	/**
	 * 检查文件大小
	 * @param int $size
	 * @return bool
	 */
	protected function isAllowSize($size){
		return $size <= $this->maxSize * 1024 *1024;
	}
	
	/**
	 * 按日期创建目录
	 */
	protected function mk_dir(){
		//创建上传路径
		$dir = PUB_DIR.'upload/'.date('Ym/d');
		//创建上传目录
		if(is_dir($dir) || mkdir($dir,0777,true)){
			return $dir;
		}else{
			return false;
		}
	}
	
	/**
	 * 生成随机文件名
	 */
	protected function randName(){
		return uniqid().mt_rand();
	}
	
	/**
	 * 显示错误信息
	 */
	public function getErr() {
        return $this->error[$this->errno];
    }
	
	
}