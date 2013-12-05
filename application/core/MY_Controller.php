<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MiniBlog
 *
 * @package		MiniBlog
 * @author		微笑的鱼
 * @license		http://www.apache.org/licenses/LICENSE-2.0
 * @link		http://blog.weixiaodeyu.com/miniblog
 * @since		0.1.2 - 2013.11.29
 */

// ------------------------------------------------------------------------


/**
 * MY_Controller Class
 *
 * Extends CodeIgniter Controller
 * Basic Model loads and settings set.
 *
 */
class MY_Controller extends CI_Controller 
{
	/**
	 * MiniBlog对象
	 * @var object
	 */
	protected $MB;
	/**
	 * 当前页面
	 * @var string
	 */
	protected $current_page;
	
	// ------------------------------------------------------------------------

	/**
	 * 构造函数
	 *
	 */
    public function __construct()
	{
		parent::__construct();
		
		date_default_timezone_set('Asia/Shanghai');//设置默认时区
		
		//加载CI辅助函数
		$this->load->helper('url');
		$this->load->helper('file');
		$this->load->helper('array');
		$this->load->helper('date');
		
		//加载MiniBlog配置文件
		$this->config->load('miniblog');
		//加载MiniBlog核心类
		$this->load->library('miniblog');
		//实例化MiniBlog核心类
		$this->MB = new Miniblog();
		//获得当前页面地址
		$this->current_page = $this->uri->segment(2, '');//当前页面的值为控制器类的方法名称，默认为空，主要用于后台当前页面判断

		$this->load->driver('cache', array('adapter' => 'file'));//加载缓存类适配器
    }
	/**
	 * 文章缓存
	 */
	public function cache_posts()
	{
		$name = 'POSTS.cache';
		$time = config_item('cache_time') ? config_item('cache_time') : 300;
		$cache = $this->cache->get($name);
		
		if ( ! $cache )
		{	
			$cache = $this->MB->load_posts();
			// Save into the cache for 5 minutes
			$this->cache->save($name, $cache, $time);
		}
		
		return $cache;
	}
	/**
	 * 页面缓存
	 */
	public function cache_pages()
	{
		$name = 'PAGES.cache';
		$time = config_item('cache_time') ? config_item('cache_time') : 300;
		$cache = $this->cache->get($name);
		
		if ( ! $cache )
		{	
			$cache = $this->MB->load_pages();
			// Save into the cache for 5 minutes
			$this->cache->save($name, $cache, $time);
		}
		
		return $cache;
	}

}

/* End of file MY_Controller.php */
/* Location: ./application/libraries/MY_Controller.php */