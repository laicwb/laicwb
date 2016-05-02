<?php
/**
 * 用于数据库的抽像数
 */
abstract class Db{
	/**
	 * 连接数据库
	 */
	abstract protected function connect();
	/**
	 * 发送执行 返回执行成功后的影响行数
	 * @access public
	 * @param string $sql	sql语句
	 * @param array $arr	用于预处理(一维的关联数组)
	 * @param bool $insid	是否要有'最后插入ID'(默认false)
	 * @return int
	 */
	abstract public function _query($sql,$arr,$insid);
	/**
	 * 获取一行数据
	 * @access public
	 * @param string $sql	sql语句
	 * @param array $arr	用于预处理(一维的关联数组)
	 * @param bool $fetch	用于返回结果的方式 true为关联数组(默认) false为索引数组
	 * @return array/bool
	 */
	abstract public function _fetch($sql,$arr,$fetch);
	/**
	 * 获取多行数据
	 * @access public
	 * @param string $sql	sql语句
	 * @param array $arr	用于预处理(一维的关联数组)
	 * @param bool $fetch	用于返回结果的方式 true为关联数组(默认) false为索引数组
	 * @return array/bool
	 */
	abstract public function _fetchAll($sql,$arr,$fetch);
	/**
	 * 最后插入的自增ID
	 * @return int
	 */
	abstract public function insert_id();
}