<?php
/**
 * 模板生成静态页
 */
class Tpl {
	/**
	 * 存放模板文件名
	 */
	protected $_file = '';
	/**
	 * 存放模板分页(一维关联数组)
	 */
	protected $_filevar;
	/**
	 * 用于接收注入变量(一维关联数组)
	 */
	protected $vars = array();

	/**
	 * 载入模板文件
	 * @access public
	 */
	public function display() {
		//模板文件名
		$_file = $this -> _file;
		//配置模板文件的路径
		$tolFile = TPL_DIR . $_file . '.php';
		//判断是否有模板分页
		if (is_array($this -> _filevar)) {
			//进行路径组合(userid1.html)
			$_cacheFile = TPL_CACHE_DIR . $_file;
			foreach ($this->_filevar as $key => $value) {
				$_cacheFile .= $key . $value;
			}
			$_cacheFile .= '.html';
		} else {
			$_cacheFile = TPL_CACHE_DIR . $_file . '.html';
		}

		//判断模板文件是否存在
		if (!is_file($tolFile)) {
			exit('ERROR: ' . $tolFile . ' 模板文件不存在!');
		}

		//是否开启缓冲区(当第二次运行时直接载入缓存入文件)
		if (C('CACHE_FILE')) {
			//判断缓存文件是否存在 并且 模板的修改时间需小于缓存文件的修改时间 并且 缓存文件的修改时间 加上 缓存的保存时间 大于 时间当前时间
			if (is_file($_cacheFile) && (filemtime($tolFile) < filemtime($_cacheFile)) && ((filemtime($_cacheFile) + C('CACHETIME')) > time())) {
				//载入缓存文件
				include $_cacheFile;
				//
				return;
			}
		}

		//将数组的首层关联数组拆分为多个对应的变量
		extract($this -> vars);
		//载入模板文件
		include $tolFile;

		//是否开启缓冲区(首次缓存)
		if (C('CACHE_FILE')) {
			//获限缓冲区内的数据,并且创建缓存文件
			file_put_contents($_cacheFile, ob_get_contents());
			//清除缓冲区
			ob_end_clean();
			//载入缓存文件
			include $_cacheFile;
		}
	}

	/**
	 * 用于注入变量
	 * @access public
	 * @param string $name		注入的变量名
	 * @param string\array $value	注入变量的值
	 */
	public function assign($name, $value) {
		if (empty($name) && empty($value)) {
			return FALSE;
		}
		//接收注入变量
		$this -> vars[$name] = $value;
	}

	/**
	 * 载入模板文件(用header和footer这种模板不需要单独创建静态页的使用)
	 * @access public
	 * @param string $_file	模板文件名
	 */
	public function create($_file = '') {
		if (empty($_file)) {
			if (!empty($this -> _file)) {
				//模板文件名
				$_file = $this -> _file;
			}
		}
		//配置模板文件的路径
		$tolFile = TPL_DIR . $_file . '.php';
		//判断模板文件是否存在
		if (!is_file($tolFile)) {
			exit('ERROR: ' . $tolFile . ' 模板文件不存在!');
		}

		//将数组的首层关联数组拆分为多个对应的变量
		extract($this -> vars);
		//载入模板文件
		include $tolFile;
	}

	/**
	 * 用于网页加载时判断是否有缓存文件
	 * @access public
	 * @return bool
	 */
	public function is_file_cache() {
		//是否开启缓冲区(当第二次运行时直接载入缓存入文件)
		if (C('CACHE_FILE')) {
			//模板文件名
			$_file = $this -> _file;
			//配置模板文件的路径
			$tolFile = TPL_DIR . $_file . '.php';
			//判断是否有模板分页
			if (is_array($this -> _filevar)) {
				//进行路径组合(user-id-1.html)
				$_cacheFile = TPL_CACHE_DIR . $_file;
				foreach ($this->_filevar as $key => $value) {
					$_cacheFile .= $key . $value;
				}
				$_cacheFile .= '.html';
			} else {
				$_cacheFile = TPL_CACHE_DIR . $_file . '.html';
			}

			//判断缓存文件是否存在 并且 模板的修改时间需小于缓存文件的修改时间 并且 缓存文件的修改时间 加上 缓存的保存时间 大于 时间当前时间
			if (is_file($_cacheFile) && (filemtime($tolFile) < filemtime($_cacheFile)) && ((filemtime($_cacheFile) + C('CACHETIME')) > time())) {
				//载入缓存文件
				include $_cacheFile;
				//return TRUE;
				exit ;
			}
		}
		return FALSE;
	}

	/**
	 * 用于接收模板文件名和模板分页(一维关联数组)
	 * @access public
	 * @param string $fie	接收模板文件名
	 * @param array $arr	模板分页(一维关联数组)
	 */
	public function filevar($fie, $arr = '') {
		if (is_string($fie)) {
			$this -> _file = $fie;
		}
		if (is_array($arr) && !empty($arr)) {
			$this -> _filevar = $arr;
		}
		//网页加载时判断是否有缓存文件
		$this -> is_file_cache();
	}

}
