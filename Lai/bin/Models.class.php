<?php
/**
 * 数据模型的父类
 */
class Models{
	/**
	 * 存放数据库对象实例
	 */
	protected $db = null;
	/**
	 * 存放model所控制的表(暂时不用)
	 */
	protected $table = '';
	/**
	 * 存放model所控制表的主键字段(暂时不用)
	 */
	protected $pk = '';
	/**
	 * 存放model所控制表的字段名(一维索引数组)
	 */
	protected $fields = array();
	/**
	 * 自动填充字段(二维索引数组)
	 * array(array('xx_no','value',1),array('xx_time','function','time'),array('字段字','类型','值'));
	 */
	protected $auto = array();

	
	
	/**
	 * 构造方法用于初始化数据库实例对象
	 */
	public function __construct(){
		//初始数据库实例对象
		$this->db = Mysql::getdb();
	}
	
	/**
	 * 过滤字段 过滤掉表中没有的字段 (需先手动填写表的字段名 protected $fields)
	 * @access public
	 * @param array $array 数据(一维关联数组)
	 * @return array
	 */
	public function facade($array = array()){
		//过滤后的新数据
		$data = array();
		//编历
		foreach($array as $k=>$v){
			//判断$k是否是表字段
			if(in_array($k, $this->fields)){
				$data[$k] = $v;
			}
		}
		return $data;
	}
	/**
	 * 自动填充字段 (需先手动填写自动填充的字段 protected $auto)
	 * @param array $data 数据(一维关联数组)
	 * @return array
	 */
	public function autoFill($data){
		foreach($this->auto as $v){
			if(!array_key_exists($v[0], $data)){
				switch($v[1]){
					case 'value':
						$data[$v[0]] = $v[2];
						break;
					case 'function':
						$data[$v[0]] = call_user_func($v[2]);
						break;
						
				}
			}
		}
		return $data;
	}
	
	
	
	
	
	
	
	
	
	
}
