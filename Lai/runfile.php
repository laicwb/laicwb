<?php
/**
 * 防止非法加载
 */
defined('LAIACC') || die('ERROR: 无法加载此文件...');

/**
 * 应用目录初始化
 */

/**
 * 控制器目录路径
 */
define('CONTROLLER_DIR', APP_PATH.'Controller/'); 
/**
 * 模型目录路径
 */
define('MODEL_DIR', APP_PATH.'Model/'); 
 /**
 * 缓存目录路径
 */
define('CACHE_DIR', APP_PATH.'Cache/');
/**
 * 日志目录路径
 */
define('LOG_DIR', CACHE_DIR.'log/');
/**
 * 模板目录路径
 */
define('TPL_DIR', APP_PATH.'Tpl/');
/**
 * 模板缓存目录路径
 */
define('TPL_CACHE_DIR', CACHE_DIR.'Tplcache/');
/**
 * 静态缓存目录路径
 */
//define('HTML_DIR', APP_PATH.'html/');
/**
 * 公共目录路径
 */
define('PUB_DIR', dirname(ROOT).'/Public/');

//创建目录
if(!is_dir(PUB_DIR)){
	//创建控制器目录
	is_dir(CONTROLLER_DIR) || mkdir(CONTROLLER_DIR,0777);
	//创建模型目录
	is_dir(MODEL_DIR) || mkdir(MODEL_DIR,0777);
	//创建缓存目录
	is_dir(CACHE_DIR) || mkdir(CACHE_DIR,0777);
	//创建日志目录
	is_dir(LOG_DIR) || mkdir(LOG_DIR,0777);
	//创建模板目录
	is_dir(TPL_DIR) || mkdir(TPL_DIR,0777);
	//创建模板缓存目录
	is_dir(TPL_CACHE_DIR) || mkdir(TPL_CACHE_DIR,0777);
	//创建公共目录
	is_dir(PUB_DIR) || mkdir(PUB_DIR,0777);
	//创建静态缓存目录
	//is_dir(HTML_DIR) || mkdir(HTML_DIR,0777);
}

//是否有应用项目的配置项
if(is_file(PUB_DIR.'conf.ini.php')){
	C(include PUB_DIR.'conf.ini.php');
}
//是否有应用项目的函数库
if(is_file(PUB_DIR.'functions.php')){
	include PUB_DIR.'functions.php';
}