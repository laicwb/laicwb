<?php
/**
 * 购物车类
 */

class Cart{
	//用于单例存放本对象
	private static $ins = null;
	//存商品
	private $items = array();
	
	//定义为最终的 防止被重写构造方法
	final protected function __construct(){
	}
	//定义为最终的 防止被重写克隆方法
	final protected function __clone(){
		
	}
	//拦截器
	public function __get($name){
	}
	public function __set($name,$value){
	}
	
	/**
	 * 获取实例(单例)
	 */
	protected static function getIns(){
		//判断自身的单例对象实例是否是自身的实例(单例模式)
		if(!(self::$ins instanceof self)){
			self::$ins = new self();
		}
		return self::$ins;
	}
	
	/**
	 * 把购物车的单例对象放到sessqion里
	 */
	public static function getCart(){
		//如果session没有购物车或者不自身的实例
		if(!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof self)){
			//
			$_SESSION['cart'] = self::getIns();
		}
		return $_SESSION['cart'];
	}
	
	/**
	 * 添加商品
	 * @access public
	 * @param int $id		商品的主键id
	 * @param string $name	商品名称
	 * @param float $price	商品价格
	 * @param int $num		商品购买数量(默认为 1)
	 * @return bool
	 */
	public function addItem($id,$name,$price,$num=1){
		//如果该商品已存在,则直接加数量
		if($this->hasItem($id)){
			//商品数量增加
			$this->incNum($id,$num);
			return true;
		}
		//将商品放入数组
		$this->items[$id] = array();
		$this->items[$id]['name'] = $name;
		$this->items[$id]['price'] = $price;
		$this->items[$id]['num'] = $num;
		return true;
	}
	
	/**
	 * 修改购物车中的商品数量
	 * @access public
	 * @param int $id	商品的主键id
	 * @param int $num	某个商品修改后的数量，即直接把某商品的数量改为$num
	 * @return bool
	 */
	public function modNum($id,$num){
		//判断商品是否已存在
		if($this->hasItem($id)){
			$this->items[$id]['num'] = $num;
			return true;
		}
		
		return false;
	}
	
	/**
	 * 商品数量增加
	 * @access public
	 * @param int $id	商品的主键id
	 * @param int $num	某个商品修改后的数量(默认为 1)
	 * @return bool
	 */
	public function incNum($id,$num = 1){
		//判断商品是否已存在
		if($this->hasItem($id)){
			$this->items[$id]['num'] += $num;
			return true;
		}
		return false;
	}
	
	/**
	 * 商品数量减少
	 * @access public
	 * @param int $id	商品的主键id
	 * @param int $num	某个商品修改后的数量(默认为 1)
	 * @return bool
	 */
	public function decNum($id,$num = 1){
		//判断商品是否已存在
		if($this->hasItem($id)){
			$this->items[$id]['num'] -= $num;
			//如果减少后,数量为0了,则把这个商品从购物车中删除
			if($this->items[$id]['num'] < 1){
				$this->delItem($id);
			}
			return true;
		}
		return false;
	}
	
	/**
	 * 判断某商品是否已存在
	 * @access public
	 * @param int $id	商品的主键id
	 * @return bool
	 */
	public function hasItem($id){
		return array_key_exists($id, $this->items);
	}
	
	/**
	 * 删除商品
	 * @access public
	 * @param int $id	商品的主键id
	 */
	public function delItem($id){
		unset($this->items[$id]);
	}
	
	/**
	 * 查询购物车中商品的种类
	 * @access public
	 * @return int
	 */
	public function getCnt(){
		return count($this->items);
	}
	
	/**
	 * 查询购物车中商品个数
	 * @access public
	 * @return int
	 */
	public function getNum(){
		//判断是否有商品的种类
		if($this->getCnt() == 0){
			return 0;
		}
		//存入个数的总数
		$sum = 0;
		foreach($this->items as $item){
			$sum += $item['num'];
		}
		return $sum;
	}
	
	/**
	 * 查询购物车中商品总金额
	 * @access public
	 * @return int
	 */
	public function getPrice(){
		//判断是否有商品的种类
		if($this->getCnt() == 0){
			return 0;
		}
		//存入总金额
		$pirce = 0.0;
		foreach($this->items as $item){
			$pirce += $item['num'] * $item['price'];
		}
		return $pirce;
	}
	
	/**
	 * 返回购物车中的所有商品
	 * @access public
	 * @return array
	 */
	public function getAll(){
		return $this->items;
	}
	
	/**
	 * 清空购物车
	 */
	public function clear(){
		$this->items = array();
	}
}