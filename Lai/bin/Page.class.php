<?php
/**
 * 分页类
 * @param int $total 	总条数
 * @param int $pageSize	每页的条数
 * @param int $bothunm  保持数字分页两边的数量(默认为 3)
 */
class Page{
	//当前的页码
	private $_page = 0;
	//每页的条数
	private $_pageSize = 0;
	//总条数
	private $_total = 0;
	//总页码
	private $_pagenum = 0;
	//limit翻页
	private $_limit = '';
	//URL地址
	private $_url = '';
	//保持数字分页两边的数量
	private $_bothunm = 0;
	
	/**
	 * 构造方法初始化
	 * @access public
	 * @param int $total	总条数
	 * @param int $pageSize	每页的条数
	 * @param int $bothunm  保持数字分页两边的数量(默认为 3)
	 */
	public function __construct($total,$pageSize,$bothunm=3){
		$this->_total = $total;										//总条数
		$this->_pageSize = $pageSize;								//每页的条数
		$this->_pagenum = ceil($this->_total / $this->_pageSize);	//总页码
		$this->_page = $this->_setPage();							//当前的页码
		$this->_limit = 'LIMIT '.($this->_page-1)*$this->_pageSize.','.$this->_pageSize;	//limit翻页
		$this->_url = $this->_setUrl();		//URL地址
		$this->_bothunm = $bothunm;			//保持数字分页两边的数量
	}
	/**
	 * 拦截器
	 */
	public function __get($_key){
		if($_key == '_limit' || $_key == '_pagenum'){
			return $this->$_key;
		}
		return FALSE;
	}
	public function __set($_key,$_value){
		return FALSE;
	}
	
	/**
	 * 获取当前页码
	 */
	private function _setPage(){
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		//不能小于1
		if($page <= 0){
			$page = 1;
		}
		//不能大于总页码
		if($page > $this->_pagenum){
			$page = $this->_pagenum;
		}
		return $page;
	}
	
	/**
	 * 获取URL地址
	 */
	private function _setUrl(){
		/**
		//当前的url地址
		$url = $_SERVER['REQUEST_URI'];
		//分折当前的url地址
		$par = parse_url($url);
		if(isset($par['query'])){
			//解析url的get参数成一个一维的关联数组
			parse_str($par['query'],$query);
			unset($query['page']);
			//组合成新的url地址没有page参数的
			$url = $par['path'].'?'.http_build_query($query);
		}else{
			$url = $par['path'].'?'.uniqid();
		}
		return $url;
		**/
		
		//当前的url地址
		$url = $_SERVER['REQUEST_URI'];
		//分折当前的url地址
		$par = parse_url($url);
		//取得文件名 (取得第一 . 前的字符串并去掉两边的的 /)
		$filename = trim(strstr($par['path'], '.',TRUE),'/');
		//判断是否有get参数
		if(isset($par['query'])){
			//解析url的get参数成一个一维的关联数组
			parse_str($par['query'],$query);
			//删除
			unset($query['page']);
			//再保证最后面
			$query['page'] = '';
			//组合成新的url地址page参数没有值的
			$url = U($filename, $query,FALSE);
			return $url;
		}else{
			//解析pathinfo的模式的字符串
			$get = painfo();
			if($get){
				//删除
				unset($get['page']);
				//再保证最后面
				$get['page'] = '';
				//组合成新的url地址page参数没有值的
			 	$url = U($filename, $get,FALSE);
			}else{
				//组合成新的url地址page参数没有值的
				$url = U($filename, array('page'=>''),FALSE);
			}
			return $url;
		}
	}
	/**
	 * 数字目录
	 */
	private function _pageList(){
		$pagelist = '';
		//前部分
		for($i=$this->_bothunm;$i>=1;$i--){
			$page = $this->_page - $i;
			//当前页已经是第一页了就不显示了
			if ($page < 1) continue;
			$pagelist .= '<a href="'.$this->_url.$page.'">'.$page.'</a>';
		}
		//当前页
		$pagelist .= '<span class="disabled-me">'.$this->_page.'</span>';
		//后部分
		for($i=1;$i<=$this->_bothunm;$i++){
			$page = $this->_page + $i;
			//当前页已经是最一后了就不显示了
			if ($page > $this->_pagenum) break;
			$pagelist .= '<a href="'.$this->_url.$page.'">'.$page.'</a>';
		}
		return $pagelist;
	}
	/**
	 * 首页
	 */
	private function _first(){
		//当前页要大于保持数字分页两边的数量
		if ($this->_page > ($this->_bothunm + 1)){
			return '<a href="'.$this->_url.'">1</a>...';
		}
		
	}
	/**
	 * 上一页
	 */
	private function _prev(){
		//当前页是第一页
		if ($this->_page==1){
			return '<span class="disabled">上一页</span>';
		}
		return '<a href="'.$this->_url.($this->_page-1).'">上一页</a>';
	}
	/**
	 * 下一页
	 */
	private function _next(){
		//当前页等于总页数
		if ($this->_page == $this->_pagenum){
			return '<span class="disabled">下一页</span>';
		}
		return '<a href="'.$this->_url.($this->_page+1).'">下一页</a>';
	}
	/**
	 * 末页
	 */
	private function _last(){
		//当总页数减当前页数要大于保持数字分页两边的数量
		if(($this->_pagenum - $this->_page) > $this->_bothunm){
			return '...<a href="'.$this->_url.$this->_pagenum.'">'.$this->_pagenum.'</a>';
		}
	}
	/**
	 * 分页信息
	 */
	public function showpage(){
		$pagea = '';
		$pagea .=$this->_first();
		$pagea .=$this->_pageList();
		$pagea .=$this->_last();
		$pagea .=$this->_prev();
		$pagea .=$this->_next();
		return $pagea;
	}	
}