<?php
//设置字符集
//header('Content-Type:text/html;charset=utf-8');
/**
 * 防止非法加载
 */
defined('LAIACC') || die('ERROR: 无法加载此文件...');
/**
 * 主框根地址
 */
define('ROOT', str_ireplace('\\','/',__DIR__.'/'));
//define('ROOT', str_ireplace('\\','/',dirname(__FILE__).'/'));
/**
 * 应用地址
 */
define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/');

//开启session
session_start();

//引入函数库
require ROOT.'function.php';
//用C函数加载配项文件数据
C(require ROOT.'conf.ini.php');
//应用目录初始化
require ROOT.'runfile.php';
//是否开启报错级别
C('DEBUT') ? error_reporting(E_ALL) : error_reporting(0);

//注册给定的函数作为 __autoload的实现自动加载类
spl_autoload_register(function($class){
	//截取并转换为小写
	if(strtolower(substr($class, -5)) == 'model'){
		//加载应用模型
		if(is_file(MODEL_DIR.$class.'.class.php')){
			require MODEL_DIR.$class.'.class.php';
		}
	}elseif(strtolower(substr($class, -10)) == 'controller'){
		//加载应用控制器
		if(is_file(CONTROLLER_DIR.$class.'.class.php')){
			require CONTROLLER_DIR.$class.'.class.php';
		}
	}else{
		//加载核心类库
		if(is_file(ROOT.'bin/'.$class.'.class.php')){
			require ROOT.'bin/'.$class.'.class.php';
		}
	}
});

//解析URL的PATHINFO模式
Url::pathUrl();


//判断是否有传进动态更新
if(isset($_GET['uphtml'])){
	//暂时关闭文件缓存
	C('CACHE_FILE',FALSE);
}

//设置公共目录
$host = C('HOST');
if(empty($host)){
	/**
	 * 公共目录
	 */
	define('__PUBLIC__','/Public/');
}else{
	/**
	 * 公共目录
	 */
	define('__PUBLIC__', $host.'/Public/');
}

//是否开启缓冲区
if(C('CACHE_FILE')){
	ob_start();
}

//组合数据库类型
//$dbms = ucfirst(C('DBMS'));
//连接数据库
//$db = $dbms::getdb();
