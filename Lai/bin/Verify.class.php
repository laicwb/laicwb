<?php
/**
 * 验证码类
 */
class Verify{
	//随机字符串
	private $_charset = 'qazwsxedcrfvtgbyhnumkpQAZWSXEDCRFVTGBYHNUMKLP23456789';
	//验证码
	private $_code = '';
	//验证码验证码字体的个数(长度)
	private $_codelen = 0;
	//图形资金资源句柄
	private $img = null;
	//验证码图宽度
	private $width = 0;
	//验证码图高度
	private $height = 0;
	//指定的字体的路径
	private $font = '';
	//指定字体大小
	private $fontsize = 0;
	
	/**
	 * 构造方法初始化
	 * @param int $codelen  验证码字体的个数(默认 4)
	 * @param int $width    验证码图宽度(默认 100)
	 * @param int $height   验证码图高度(默认 30)
	 * @param int $fontsize 指定字体大小(默认 18)
	 * @param string $font  指定的字体的路径(默认 'font/elephant.ttf')
	 */
	public function __construct($codelen=4,$width=100,$height=30,$fontsize=18,$font=''){
		$this->_codelen = $codelen;
		$this->width = $width;
		$this->height = $height;
		$this->fontsize = $fontsize;
		empty($font) ? $this->font = ROOT.'font/elephant.ttf' : $this->font = $font;
		//生成验证码
		$this->entry();
	}
	/**
	 * 拦截器
	 */
	public function __get($name){
		if($name == 'code'){
			return $this->_code;
		}
		return false;
	}
	public function __set($name,$value){
		return false;
	}
	
	/**
	 * 生成随机码
	 */
	private function createCode(){
		//打乱随机字符串并截取
		$this->_code = substr(str_shuffle($this->_charset), 0,$this->_codelen);
	}
	
	/**
	 * 生成背景
	 */
	private function createBg(){
		//生成图的宽高度
		$this->img = imagecreatetruecolor($this->width, $this->height);
		//生成图的的背景颜色
		$color = imagecolorallocate($this->img, mt_rand(160, 255), mt_rand(160, 255), mt_rand(160, 255));
		//画矩形(长方形)
		imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
	}
	
	/**
	 * 生成图形文字
	 */
	private function createFont(){
		//生成图的的背景颜色
		$color = imagecolorallocate($this->img, mt_rand(0, 150), mt_rand(0, 150), mt_rand(0, 150));
		//x坐标
		$x = $this->width / $this->_codelen;
		//y坐标
		$y = $this->height/1.4;
		//生成文字
		for ($i=0;$i<$this->_codelen;$i++){
			imagettftext($this->img, $this->fontsize, mt_rand(-30, 30), $x*$i+mt_rand(3, 6), $y, $color, $this->font, $this->_code[$i]);
		}
	}
	
	/**
	 * 生成图形线条
	 */
	private function createLine(){
		for ($i=0;$i<10;$i++){
			//生成图的的背景颜色
			$color = imagecolorallocate($this->img, mt_rand(0, 150), mt_rand(0, 150), mt_rand(0, 150));
			//生成线条
			imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
		}
	}
	
	/**
	 * 输出图形
	 */
	private function outPut(){
		//图形的标头
		header('Content-Type:image/png');
		//输出png
		imagepng($this->img);
		//销毁图形句柄
		imagedestroy($this->img);
	}
	
	/**
	 * 获取验证码字符串(为小写)存放session
	 */
	private function getCode(){
		$_SESSION['getcode'] = strtolower($this->_code);
	}
	
	/**
	 * 生成验证码
	 */
	private function entry(){
		//生成背景
		$this->createBg();
		//生成随机码
		$this->createCode();
		//生成图形线条
		$this->createLine();
		//生成图形文字
		$this->createFont();
		//获取验证码字符串存放session
		$this->getCode();
		//输出图形
		$this->outPut();
	}
	
}