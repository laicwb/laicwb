<?php
//创建mysqli对象(资源句柄),并连接数据库
@$db = new mysqli('localhost','root','','test');
//使用了构造函数,就同等这个函数连接数据库
//$db->connect('localhost','root','','test');
//判断连接数据库是否有错误,0为没有错误
if(mysqli_connect_errno()){
	echo '数据库连接错误->',mysqli_connect_error();
}
//数据库连接的默认字符编码
$db->set_charset('utf8');
//执行sql语句,把结果集(资源句柄)赋给$result
$result = $db->query('SELECT CURRENT_USER()');
//通过结果集,取得第一行数据(每执行一次下标自动向下移动一次)
var_dump($result->fetch_row());		//返回索引数组
var_dump($result->fetch_assoc());	//返回关联数组
//释放查询结果集(资源句柄)
$result->free();
//释放数据库(资源句柄)
$db->close();
