<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MiniBlog
 *
 * @package		MiniBlog
 * @author		微笑的鱼
 * @license		http://www.apache.org/licenses/LICENSE-2.0
 * @link		http://blog.weixiaodeyu.com/miniblog
 * @since		0.1.3 - 2013.12.04
 */
 
// ------------------------------------------------------------------------


/**
 * Miniblog Class
 *
 * 迷你博客核心类
 * 所有操作通过此类进行
 *
 */
class Miniblog
{
	/**
	 * MiniBlog版本
	 * @var string
	 */
	private $version = 'Version 0.1.3';
	/**
	 * CodeIgniter对象
	 * @var object
	 */
	private $CI;
	/**
	 * 主题名称
	 * @var string
	 */
	public $theme = 'default';
	/**
	 * 当前请求的页面类型，默认为站点首页
	 * @var string
	 */
	public $get_type = 'index';
	/**
	 * 当前请求的页面名称
	 * @var string
	 */
	public $get_name = '';
	/**
	 * 文章数组
	 * @var array
	 */
	public $posts;
	/**
	 * 文章ID数组
	 * @var array
	 */
	public $post_ids;
	/**
	 * 当前文章ID
	 * @var string
	 */
	public $post_id;
	/**
	 * 当前文章
	 * @var array
	 */
	public $post;
	/**
	 * 标签数组
	 * @var array
	 */
	public $tags;
	/**
	 * 日期数组
	 * @var array
	 */
	public $dates;
	/**
	 * 当前文章内容
	 * @var string
	 */
	public $content;
	/**
	 * 文章总数
	 * @var int
	 */
	public $post_count = 0;
	/**
	 * 分页：当前页码
	 * @var int
	 */
	public $page_num  = 1;
	/**
	 * 分页：分页大小
	 * @var int
	 */	
	public $page_size = 10;
	
	// ------------------------------------------------------------------------
	
	/**
	 * 构造函数
	 * @var object
	 */
	public function __construct()
	{
		//初始化CodeIgniter对象
		$this->CI = & get_instance();
		
		//数据文件初始化
		$this->data_init();
		
		//主题名称
		$this->theme = config_item('theme');
		
		//初始化分页大小
		$this->page_size = intval(config_item('page_size')) ? config_item('page_size') : 10;
	}
	/**
	 * 用Cookie判断是否已经登录后台
	 */
	public function is_login()
	{
		$token = $this->CI->input->cookie('mb_token');
		if($token)
		{
			if ($token == md5(config_item('user_name').'_'.config_item('user_pass')))
			{
				return TRUE;
			}
		}
		return FALSE;
	}
	/**
	 * 检测是否已经登录后台，未登录跳转到登录面
	 */
	public function check_login()
	{
		if(!$this->is_login())
		{
			redirect(admin_url());
		}
	}
	/**
	 * 后台登录验证
	 */
	public function login($user_name, $user_pass)
	{
		if ($user_name == config_item('user_name') && $user_pass == config_item('user_pass'))
		{
			$this->set_cookie($user_name, $user_pass);
			return TRUE;
		}
		return FALSE;
	}
	/**
	 * 配置文件写入
	 */
	public function save_config($data, $user_name, $user_pass,$user_name_changed)
	{
		//组合文件内容
		$code = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\$config = ".var_export($data, true)."\n?>";
		//配置文件路径
		$file_path = APPPATH . 'config/miniblog.php';
		//返回信息
		$message = '无法写入文件,请检查'.$file_path.'是否有写入权限！';
		//写入文件
		if (write_file($file_path, $code))
		{
			$message = '设置保存成功！';
			//如果修改了用户名或密码，则重设Cookie
			if ($user_pass || $user_name_changed)
			{
				$this->set_cookie($user_name, $user_pass);
			}
		}
		return $message;
	}
	/**
	 * 索引文件路径
	 */
	public function index_file_path($state = 'publish', $type = 'posts')
	{
		//不是已知状态则指定一个状态
		if($state != 'draft' && $state != 'delete' && $state != 'publish')
		{
			$state = 'publish';
		}
		//不是已知类型则指定一个类型
		if($type != 'posts' && $type != 'pages')
		{
			$type = 'posts';
		}
		;
		$index_file = $this->data_dir($type, 'index') .$state. '.php';
		return $index_file;
	}
	/**
	 * jdb数据路径
	 */
	public function jdb_file_path($id, $type = 'posts')
	{		
		$index_file = $this->data_dir($type) .$id. '.jdb';
		return $index_file;
	}
	
	// ------------------------------------------------------------------------
	// 文章操作
	// ------------------------------------------------------------------------

	/**
	 * 加载文章索引文件
	 */
	public function load_posts($state = 'publish')
	{
		if(!$state)
		{
			$state = 'publish';
		}
		$index_file = $this->index_file_path($state);
		include $index_file;
		return $posts;
	}
	/**
	 * 保存文章索引文件
	 */
	public function save_posts($state, $posts)
	{
		$index_file = $this->index_file_path($state);
		$code = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\$posts = ".var_export($posts, true)."\n?>";
		return write_file($index_file, $code);
	}
	/**
	 * 保存文章数据文件
	 */
	public function save_posts_jdb($id, $data)
	{
		$file_path = $this->jdb_file_path($id);
		return write_file($file_path, serialize($data));
	}
	/**
	 * 删除文章
	 */
	public function delete_post($id, $posts, $state = 'recover')
	{
		if(!array_key_exists($id, $posts))
		{
			return;
		}
		$post = $posts[$id];
		$post['prev_state'] = $state;
		unset($posts[$id]);
		$this->save_posts($state, $posts);//写入索引文件
		if ($state != 'delete')
		{
			$posts = $this->load_posts('delete');
			$posts[$id] = $post;
			$this->save_posts('delete', $posts);//写入索引文件
		}
		else
		{
			unlink($this->jdb_file_path($id));
		}		
	}
	/**
	 * 还原文章
	 */
	public function revert_post($id, $posts, $state = 'delete')
	{
		if(!array_key_exists($id, $posts))
		{
			return;
		}
		
		$post = $posts[$id];
		$prev_state = $post['prev_state'];
		unset($post['prev_state']);
		unset($posts[$id]);
		$this->save_posts($state, $posts);//写入索引文件
		
		$posts = $this->load_posts($prev_state);
		$posts[$id] = $post;
		uasort($posts, "post_sort");
		$this->save_posts($prev_state, $posts);//写入索引文件	
	}
	
	// ------------------------------------------------------------------------
	// 页面操作
	// ------------------------------------------------------------------------

	/**
	 * 加载页面索引文件
	 */
	public function load_pages($state = 'publish')
	{
		if(!$state)
		{
			$state = 'publish';
		}
		$index_file = $this->index_file_path($state, 'pages');
		include $index_file;
		return $pages;
	}
	/**
	 * 保存页面索引文件
	 */
	public function save_pages($state, $pages)
	{
		$index_file = $this->index_file_path($state, 'pages');
		$code = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\$pages = ".var_export($pages, true)."\n?>";
		return write_file($index_file, $code);
	}
	/**
	 * 保存页面数据文件
	 */
	public function save_pages_jdb($id, $data)
	{
		$file_path = $this->jdb_file_path($id, 'pages');
		return write_file($file_path, serialize($data));
	}
	/**
	 * 删除页面
	 */
	public function delete_page($id, $pages, $state = 'recover')
	{
		if(!array_key_exists($id, $pages))
		{
			return;
		}
		$page = $pages[$id];
		$page['prev_state'] = $state;
		unset($pages[$id]);
		$this->save_pages($state, $pages);//写入索引文件
		if ($state != 'delete')
		{
			$pages = $this->load_pages('delete');
			$pages[$id] = $page;
			$this->save_pages('delete', $pages);//写入索引文件
		}
		else
		{
			unlink($this->jdb_file_path($page['file'], 'pages'));
		}		
	}
	/**
	 * 还原文章
	 */
	public function revert_page($id, $pages, $state = 'delete')
	{
		if(!array_key_exists($id, $pages))
		{
			return;
		}
		
		$page = $pages[$id];
		$prev_state = $page['prev_state'];
		unset($page['prev_state']);
		unset($pages[$id]);
		$this->save_pages($state, $pages);//写入索引文件
		
		$pages = $this->load_pages($prev_state);
		$pages[$id] = $page;
		uasort($pages, "post_sort");
		$this->save_pages($prev_state, $pages);//写入索引文件	
	}
	
	// ------------------------------------------------------------------------
	// 前台操作
	// ------------------------------------------------------------------------
	/**
	 * 请求类型是否为文章
	 */	
	public function is_home() 
	{
		return $this->get_type == 'home';
	}
	/**
	 * 请求类型是否为文章
	 */	
	public function is_post() 
	{
		return $this->get_type == 'post';
	}
	/**
	 * 请求类型是否为页面
	 */	
	public function is_page() 
	{
		return $this->get_type == 'page';
	}
	/**
	 * 请求类型是否为标签
	 */	
	public function is_tag() 
	{
		return $this->get_type == 'tag';
	}
	/**
	 * 请求类型是否为日期
	 */	
	public function is_date() 
	{
		return $this->get_type == 'date';
	}
	/**
	 * 请求类型是否为存档
	 */	
	public function is_archive()
	{
		return $this->get_type == 'archive';
	}	
	/**
	 * 请求类型是否为搜索
	 */	
	public function is_search()
	{
		return $this->get_type == 'search';
	}	
	/**
	 * 读取配置文件中的网站名称
	 */	
	public function site_name($print = true)
	{
		$site_name = htmlspecialchars(config_item('site_name'));
		if ($print) {
			echo $site_name;
			return;
		}
		return $site_name;
	}
	/**
	 * 读取配置文件中的网站描述
	 */	
	public function site_desc($print = true)
	{
		$site_desc = htmlspecialchars(config_item('site_desc'));
		if ($print) {
			echo $site_desc;
			return;
		}
		return $site_desc;
	}
	/**
	 * 读取配置文件中的站长描述
	 */		
	public function nick_name($print = true)
	{
		$nick_name = htmlspecialchars(config_item('user_nick'));
		if ($print) {
			echo $nick_name;
			return;
		}
		return $nick_name;
	}
	/**
	 * 生成主题文件的路径
	 */	
	public function theme_url($path, $print = true)
	{
		$url = base_url('/themes/'. $this->theme .'/'.$path);
		if ($print) {
			echo $url;
			return;
		}
		return $url;
	}
	/**
	 * 文章循环器
	 */	
	public function next_post() 
	{
		//如果数据为空直接退出
		if ($this->posts == NULL)
			return false;

		if (!isset($this->tmp_post_i)) {
			$this->tmp_post_i = 0 + ($this->page_num - 1) * $this->page_size;
			$this->tmp_post_i_end = $this->tmp_post_i + $this->page_size;
			if ($this->post_count < $this->tmp_post_i_end)
				$this->tmp_post_i_end = $this->post_count;
		}
		
		if ($this->tmp_post_i == $this->tmp_post_i_end)
			return false;
		
		$this->post_id = $this->post_ids[$this->tmp_post_i];
		$this->post = $this->posts[$this->post_id];
		$this->tmp_post_i += 1;
		
		return true;
	}
	/**
	 * 当前文章链接
	 */	
	public function the_link() 
	{
		echo anchor($this->post_id, htmlspecialchars($this->post['title']), 'title="'.htmlspecialchars($this->post['title']).'"');
	}
	/**
	 * 当前文章内容
	 */	
	public function the_content($print = true)
	{
		
		if ($this->content == NULL) {
			$data = unserialize(file_get_contents($this->jdb_file_path($this->post_id)));
			$html = $data['content']; 
		}
		else {
			$html = $this->content['content'];
		}
		
		if ($print) {
			echo $html;
			return;
		}
		return $html;
	}
	/**
	 * 当前文章发布日期
	 */	
	public function the_date($print = true)
	{
		if ($print) {
			echo $this->post['date'];
			return;
		}
		return $post['date'];
	}
	/**
	 * 当前文章发布时间
	 */	
	public function the_time($print = true)
	{
		if ($print) {
			echo $this->post['time'];
			return;
		}
		return $post['time'];
	}
	/**
	 * 当前文章标签
	 */	
	public function the_tags($item_begin='', $item_gap=', ', $item_end='')
	{
		$tags = $this->post['tags'];
		$count = count($tags);
		for ($i = 0; $i < $count; $i ++) {
			$tag = htmlspecialchars($tags[$i]);
			echo $item_begin;
			echo anchor('tag/'.urlencode($tag), htmlspecialchars($tag), 'title="'.htmlspecialchars($tag).'" class="tags"');
			echo $item_end;
			if ($i < $count - 1)
				echo $item_gap;
		}
	}
	/**
	 * 当前文章链接
	 */	
	public function post_link() 
	{
		echo site_url(urlencode($this->post_id));
	}
	/**
	 * 当前文章标题
	 */	
	public function the_title($print = true) {
		if ($print) {
			echo htmlspecialchars($this->post['title']);
			return;
		}
		return htmlspecialchars($this->post['title']);
	}
	
	/**
	 * 分页：判断当前页码是否是第1页
	 */	
	public function has_new()
	{
		return $this->page_num != 1;
	}
	/**
	 * 分页：判断当前页码是否在总分页大小范围内
	 */	
	public function has_old()
	{
		return $this->page_num < ($this->post_count / $this->page_size);
	}
	/**
	 * 分页：判断当前页码是否是超过总分页大小 add by 微笑的鱼 2013.12.04
	 */	
	public function page_num_is_overflow()
	{
		//分页总数计算
		$all_pages = round($this->post_count / $this->page_size);
		if($all_pages < 1)
		{
			$all_pages = 1;
		}
		return $this->page_num > $all_pages;
	}
	/**
	 * 分页：下一页
	 */		
	public function goto_old($text, $classname = '') {
		if(!$classname) $classname = 'nextpage';
		if ($this->get_type == 'tag') {
			echo '<a href="';
			echo site_url('tag/'.$this->get_name);
			echo '?page=';
			echo ($this->page_num + 1);
			echo '" class="'.$classname.'">';
			echo $text;
			echo '</a>';
		} 
		elseif ($this->get_type == 'date') {
			echo '<a href="';
			echo site_url('date/'.$this->get_name);
			echo '?page=';
			echo ($this->page_num + 1);
			echo '" class="'.$classname.'">';
			echo $text;
			echo '</a>';
		}
		elseif ($this->get_type == 'search') {
			echo '<a href="';
			echo base_url('search/'.$this->get_name);
			echo '?page=';
			echo ($this->page_num + 1);
			echo '" class="'.$classname.'">';
			echo $text;
			echo '</a>';
		} else {
			echo '<a href="';
			echo base_url();
			echo '?page=';
			echo ($this->page_num + 1);
			echo '" class="'.$classname.'">';
			echo $text;
			echo '</a>';
		}
	}
	/**
	 * 分页：上一页
	 */		
	public function goto_new($text, $classname = '') {
		if(!$classname) $classname = 'prevpage';
		if ($this->get_type == 'tag') {
			echo '<a href="';
			echo site_url('tag/'.$this->get_name);
			echo '?page=';
			echo ($this->page_num - 1);
			echo '" class="'.$classname.'">';
			echo $text;
			echo '</a>';
		} 
		elseif ($this->get_type == 'date') {
			echo '<a href="';
			echo site_url('date/'.$this->get_name);
			echo '?page=';
			echo ($this->page_num - 1);
			echo '" class="'.$classname.'">';
			echo $text;
			echo '</a>';
		}
		elseif ($this->get_type == 'search') {
			echo '<a href="';
			echo base_url('search/'.$this->get_name);
			echo '?page=';
			echo ($this->page_num + 1);
			echo '" class="'.$classname.'">';
			echo $text;
			echo '</a>';
		} else {
			echo '<a href="';
			echo base_url();
			echo '?page=';
			echo ($this->page_num - 1);
			echo '" class="'.$classname.'">';
			echo $text;
			echo '</a>';
		}
	}
	/**
	 * 页面标题
	 */	
	public function page_title() {
		echo htmlspecialchars($this->post['title']);
	}	
	/**
	 * 评论：是否开启了评论功能
	 */	
	public function can_comment() {
		return isset($this->post['can_comment']) ? $this->post['can_comment'] == '1' : true;
	}
	/**
	 * 评论：获得配置文件中的评论代码
	 */	
	public function comment_code() {
		echo config_item('comment_code');
		return;
	}
	/**
	 * 归档：生成日期归档
	 */	
	public function date_list($item_begin='<li>', $item_gap='', $item_end='</li>') {
		if ($this->dates != NULL) {
			$date_count = count($this->dates);
			for ($i = 0; $i < $date_count; $i ++) {
				$date = $this->dates[$i];
				echo $item_begin;
				echo anchor('date/'.urlencode($date), htmlspecialchars($date), 'title="'.htmlspecialchars($date).'"');
				echo $item_end;
				if ($i < $date_count - 1)
					echo $item_gap;
			}
		}
	}
	/**
	 * 归档：输出日期
	 */	
	public function date_name($print=true) {
		if ($print) {
			echo htmlspecialchars($this->get_name);
			return;
		}
		return $this->get_name;
	}
	/**
	 * 归档：生成标签归档
	 */		
	public function tag_list($item_begin='<li>', $item_gap='', $item_end='</li>') {
		if ($this->tags != NULL) {
			$tag_count = count($this->tags);
			for ($i = 0; $i < $tag_count; $i ++) {
				$tag = $this->tags[$i];
				echo $item_begin;
				echo anchor('tag/'.urlencode($tag), htmlspecialchars($tag), 'title="'.htmlspecialchars($tag).'"');
				echo $item_end;
				if ($i < $tag_count - 1)
					echo $item_gap;
			}
		}
	}
	/**
	 * 归档：标签名称
	 */		
	public function tag_name($print=true) {
		if ($print) {
			echo htmlspecialchars($this->get_name);
			return;
		}
		return $this->get_name;
	}
	/**
	 * 搜索：搜索关键字
	 */		
	public function search_name($print=true) {
		if ($print) {
			echo htmlspecialchars($this->get_name);
			return;
		}
		return $this->get_name;
	}

	// ------------------------------------------------------------------------
	// 私有类
	// ------------------------------------------------------------------------
	/**
	 * data文件夹路径
	 */
	private function data_dir($post_type = 'posts', $data_type = 'jdb')
	{		
		$data_dir = APPPATH . 'data/' .$post_type. '/' .$data_type. '/';
		return $data_dir;
	}	
	/**
	 * 数据文件初始化
	 */
	private function data_init()
	{
		if( file_exists(APPPATH . 'data') )	return;//如果数据文件夹存在，则直接退出

		//数据文件夹初始化
		$post_jdb_dir = $this->data_dir('posts');
		if (!is_dir($post_jdb_dir)) mkdir($post_jdb_dir,777,TRUE);
			
		$page_jdb_dir = $this->data_dir('pages');
		if (!is_dir($page_jdb_dir)) mkdir($page_jdb_dir,777,TRUE);

		//索引文件夹初始化
		$post_index_dir = $this->data_dir('posts', 'index');
		if (!is_dir($post_index_dir)) mkdir($post_index_dir,777,TRUE);

		$this->save_posts('publish', array());
		$this->save_posts('draft', array());
		$this->save_posts('delete', array());
		
			
		$page_index_dir = $this->data_dir('pages', 'index');
		if (!is_dir($page_index_dir)) mkdir($page_index_dir,777,TRUE);
		
		$this->save_pages('publish', array());
		$this->save_pages('draft', array());
		$this->save_pages('delete', array());
	}	
	/**
	 * 设置Cookie
	 */
	private function set_cookie($user_name, $user_pass)
	{
		$cookie = array(
			'name'   => 'mb_token',
			'value'  => md5($user_name.'_'.$user_pass),
			'expire' => time()+3600,
			'prefix' => config_item('cookie_prefix'),
			'domain' => config_item('cookie_domain'),
			'path'   => config_item('cookie_path'),
			'secure' => config_item('cookie_secure'),
		);
		$this->CI->input->set_cookie($cookie);
	}	
}