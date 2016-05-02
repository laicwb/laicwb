<?php
/**
 * 图片处理类
 */
class Image{
	/**
	 * 分析图片的信息
	 * @param string $image 图片
	 * @return array
	 */
	public static function imageInfo($image){
		//判断图片是否存在
		if(!is_file($image)){
			return false;
		}
		//取得图像信息
		$info = getimagesize($image);
		if($info == false){
			return false;
		}
		//组合新的图像信息
		$img = array();
		$img['width'] = $info[0];	//宽
		$img['height'] = $info[1];	//高
		$img['ext'] = substr($info['mime'],strpos($info['mime'], '/')+1);	//后缀
		
		return $img;
	}
	
	/**
	 * 加水印功能
	 * @param string $dst 	等操作图片
	 * @param string $water 水印小图
	 * @param string $save 	加完水印保存的路径,默认替换原始图
	 * @param string $pos 	水印的位置,默认为2(0左上角1右上角2右下角3左下角)
	 * @param string $alpha 透明度,默认为50%
	 * @return bool
	 */
	public static function water($dst,$water,$save='',$pos=2,$alpha=50){
		//先保证2个图片都存在
		if(!is_file($dst) || !is_file($water)){
			return false;
		}
		//保证水印图不能比待操作图片还大
		$dinfo = self::imageInfo($dst);
		$winfo = self::imageInfo($water);
		if($winfo['height'] > $dinfo['height'] || $winfo['width'] > $dinfo['width']){
			return false;
		}
		
		//用后缀组成动态函数名
		$dfunc = 'imagecreatefrom'.$dinfo['ext'];
		$wfunc = 'imagecreatefrom'.$winfo['ext'];
		//判断是否有这两个函数
		if(!function_exists($dfunc) || !function_exists($wfunc)){
			return false;
		}
		//动态加载函数来创建画布
		$dim = $dfunc($dst);	//创建待操作的画布
		$wim = $wfunc($water);	//创建水印画布
		
		//水印的位置
		switch ($pos){
			case 0:
				$posx = 0;
				$posy = 0;
				break;
			case 1:
				$posx = $dinfo['width'] - $winfo['width'];
				$posy = 0;
				break;
			case 3:
				$posx = 0;
				$posy = $dinfo['height'] - $winfo['height'];
				break;
			default:
				$posx = $dinfo['width'] - $winfo['width'];
				$posy = $dinfo['height'] - $winfo['height'];
		}
		
		//加水印
		imagecopymerge($dim, $wim, $posx, $posy, 0, 0, $winfo['width'], $winfo['height'], $alpha);
		//保存,加完水印保存的路径,默认替换原始图
		if(empty($save)){
			$save = $dst;
			//删除原始图
			unlink($dst);
		}
		
		//用后缀组成动态函数名
		$createfunc = 'image'.$dinfo['ext'];
		//动态加载函数来创建图片
		$createfunc($dim,$save);
		
		//销毁图像
		imagedestroy($dim);
		imagedestroy($wim);
		return true;
	}
	
	/**
	 * 生成缩略图(等比例缩放,两边留白)
	 * @param string $dst 	等操作图片
	 * @param string $save 	生成缩略图保存的路径,默认替换原始图
	 * @param int $width 	生成缩略图宽度
	 * @param int $height 	生成缩略图高度
	 */
	public static function thumb($dst,$save='',$width=200,$height=200){
		//首先判断待处理的图片存不存在
		$dinfo = self::imageInfo($dst);
		if($dinfo == false){
			return false;
		}
		
		//计算缩放比例
		$calc = min($width/$dinfo['width'],$height/$dinfo['height']);
		
		//创建原始图画布
		//用后缀组成动态函数名
		$dfunc = 'imagecreatefrom'.$dinfo['ext'];
		//动态加载函数来创建原始图画布
		$dim = $dfunc($dst);
		
		//创建缩略画布
		$tim = imagecreatetruecolor($width, $height);
		//创建白色填充缩略画布
		$white = imagecolorallocate($tim, 255, 255, 255);
		//填充缩略画布
		imagefill($tim, 0, 0, $white);
		//复制并缩略
		$dwidth = (int)$dinfo['width']*$calc;		//
		$dheight = (int)$dinfo['height']*$calc;		//
		$paddingx = (int)($width - $dwidth) / 2;	//
		$paddingy = (int)($height - $dheight) /2;	//
		imagecopyresampled($tim, $dim, $paddingx, $paddingy, 0, 0, $dwidth, $dheight, $dinfo['width'], $dinfo['height']);
		
		//保存图片
		//生成缩略图保存的路径,默认替换原始图
		if(empty($save)){
			$save = $dst;
			//删除原始图
			unlink($dst);
		}
		//用后缀组成动态函数名
		$createfunc = 'image'.$dinfo['ext'];
		//动态加载函数来创建图片
		$createfunc($tim,$save);
		
		//销毁图像
		imagedestroy($dim);
		imagedestroy($tim);
		return true;
	}	
}