<?php
/**
 * 防止非法加载
 */
defined('LAIACC') || die('ERROR: 无法加载此文件...');

/**
 * 基础配置项
 */
return array(
	'HOST'=>'',						//网站域名
	
	'DBMS'=>'mysql',				//数据库的类型
	'DBHOST'=>'localhost',			//数据库主机地址
	'DBNAME'=>'test',				//数据库的库名
	'DBUSER'=>'root',				//数据库用户名
	'DBPASS'=>'',					//数据库密码
	'DBCHAR'=>'utf8',				//数据库的字符集
	
	'DEBUT'=>FALSE,					//开启设置报错级别,默认为FALSE
	
	'CACHE_FILE'=>TRUE,				//是否开启文件缓存,默认为true
	'CACHETIME'=>86400,				//设置缓存的时间有效期,默认为一天以秒为单位
	
	'LOGNAME'=>'curr.log',			//设置日志文件的文件名
	'LOGSIZE'=>20000,				//设置日志文件的保存大小(字节)
	
	'PATHINFO'=>TRUE,				//是否为pathinfo模式,主要用于U函数
	'PATHINFO_HTML'=>'html',		//伪静态扩展名
);