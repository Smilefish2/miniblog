<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {

	/**
	 * 默认控制器
	 *
	 */
	public function index($query = 'index')
	{
		//站点首页
		if($query == 'index')
		{
			$this->MB->get_type = 'home';
			$this->MB->posts = $this->cache_posts();//启用缓存
			$this->MB->post_ids = array_keys($this->MB->posts);
			$this->MB->post_count = count($this->MB->post_ids);
		}
		else//文章和页面处理
		{
			$this->read($query);
		}
		//分页处理
		$this->pagination();
		//显示处理
		$data['mb'] = $this->MB;
		$this->load->view($this->MB->theme, $data);
	}
	/**
	 * 文章和页面读取
	 *
	 */
	public function read($query)
	{
		$is_post = file_exists($this->MB->jdb_file_path($query));
		//文章处理
		if($is_post)
		{
			$this->MB->get_type = 'post';
			$this->MB->posts = $this->cache_posts();//启用缓存
			if (array_key_exists($query, $this->MB->posts))
			{
				$this->MB->post_id = $query;
				$this->MB->post = $this->MB->posts[$query];
				$this->MB->content = unserialize(file_get_contents($this->MB->jdb_file_path($query)));
			}
			else
			{
				//未找到文章显示404错误
				show_404('文章['.$query.']未找到！');
			}
			
			
		}
		else
		{
			//页面处理
			$this->MB->get_type = 'page';
			$this->MB->posts = $this->cache_pages();//启用缓存
			if (array_key_exists($query, $this->MB->posts))
			{
				$this->MB->post_id = $query;
				$this->MB->post = $this->MB->posts[$query];
				$this->MB->content = unserialize(file_get_contents($this->MB->jdb_file_path($this->MB->post['file'], 'pages')));
				
			}
			else
			{
				//不是首页也不是文章或页面时，显示404错误
				show_404('页面['.$query.']未找到！');
			}
		}
		
	}
	
	/**
	 * 存档读取
	 *
	 */
	public function archive()
	{
		$this->MB->get_type = 'archive';
		
		$this->MB->posts = $this->cache_posts();//启用缓存
		$this->MB->post_ids = array_keys($this->MB->posts);
		$this->MB->post_count = count($this->MB->post_ids);
		
	    $tags_array = array();
		$date_array = array();
		
	    for ($i = 0; $i < $this->MB->post_count; $i ++) {
			$post_id = $this->MB->post_ids[$i];
			$post = $this->MB->posts[$post_id];
			$date_array[] = substr($post['date'], 0, 7);
			$tags_array = array_merge($tags_array, $post['tags']);
	    }
		$this->MB->tags  = array_values(array_unique($tags_array));
		$this->MB->dates = array_values(array_unique($date_array));
		
		$data['mb'] = $this->MB;
		$this->load->view($this->MB->theme, $data);
		
	}
	/**
	 * 标签归档文章读取
	 *
	 */
	public function tag($tag = '')
	{
		$this->MB->get_type = 'tag';
		$this->MB->posts = $this->cache_posts();//启用缓存
		$this->MB->get_name = urldecode($tag);
	    $this->MB->post_ids = array_keys($this->MB->posts);
	    $this->MB->post_count = count($this->MB->post_ids);
		
		//分页处理
		$this->pagination();

	    $tag_posts = array();
	    for ($i = 0; $i < $this->MB->post_count; $i ++) {
			$id = $this->MB->post_ids[$i];
			$post = $this->MB->posts[$id];
			if (in_array($this->MB->get_name, $post['tags'])) {
				$tag_posts[$id] = $post;
			}
	    }
		
		//重新计算
	    $this->MB->posts = $tag_posts;
	    $this->MB->post_ids = array_keys($this->MB->posts);
	    $this->MB->post_count = count($this->MB->post_ids);
				
		$data['mb'] = $this->MB;
		$this->load->view($this->MB->theme, $data);
		
	}
	/**
	 * 日期归档文章读取
	 *
	 */
	public function date($date='')
	{
		$this->MB->get_type = 'date';
		$this->MB->posts = $this->cache_posts();//启用缓存
		$this->MB->get_name = urldecode($date);
	    $this->MB->post_ids = array_keys($this->MB->posts);
	    $this->MB->post_count = count($this->MB->post_ids);
		
		//分页处理
		$this->pagination();
		
	    $date_posts = array();
	    for ($i = 0; $i < $this->MB->post_count; $i ++) {
			$id = $this->MB->post_ids[$i];
			$post = $this->MB->posts[$id];
			if (strpos($post['date'], $this->MB->get_name) === 0) {
				$date_posts[$id] = $post;
			}
	    }
		//重新计算
	    $this->MB->posts = $date_posts;
	    $this->MB->post_ids = array_keys($this->MB->posts);
	    $this->MB->post_count = count($this->MB->post_ids);
		
		$data['mb'] = $this->MB;
		$this->load->view($this->MB->theme, $data);
		
	}	
	/**
	 * 查询文章
	 *
	 */
	public function search($keyword='')
	{
		$this->MB->get_type = 'search';
		$this->MB->posts = $this->cache_posts();//启用缓存
		$this->MB->get_name = urldecode(trim($keyword));
	    $this->MB->post_ids = array_keys($this->MB->posts);
	    $this->MB->post_count = count($this->MB->post_ids);
		
		//分页处理
		$this->pagination();
		
	    $search_posts = array();
	    foreach($this->MB->posts as $k=>$v) 
		{
			//搜索标题
			if(preg_match('/'.$this->MB->get_name.'/i',$v['title']))
			{
				$search_posts[$k] = $this->MB->posts[$k];
			}
			else //搜索内容
			{
				$content = unserialize(file_get_contents($this->MB->jdb_file_path($v['id'])));
				if(preg_match('/'.$this->MB->get_name.'/i',$content['content']))
				{
					$search_posts[$k] = $this->MB->posts[$k];
				}
			}
	    }
		
		//重新计算
	    $this->MB->posts = $search_posts;
	    $this->MB->post_ids = array_keys($this->MB->posts);
	    $this->MB->post_count = count($this->MB->post_ids);
		
		$data['mb'] = $this->MB;
		$this->load->view($this->MB->theme, $data);
		
	}	
	/**
	 * 分页处理
	 *
	 */
	public function pagination()
	{
		$page_num = $this->input->get('page');
		if(!$page_num)
		{
			$page_num = 1;
		}
		$this->MB->page_num = $page_num;

		if(!$this->MB->is_post() && !$this->MB->is_page() && $this->MB->page_num_is_overflow())
			show_404('分面页码['.$page_num.']超出范围,URL：'.current_url());//如果当前不在分页总数范围内
		
	}
	/**
	 * RSS页面读取
	 *
	 */
	public function rss()
	{
		
		header("Content-Type: text/xml");
		
		$this->MB->posts = $this->cache_posts();//启用缓存
		$this->MB->post_ids = array_keys($this->MB->posts);
		$this->MB->post_count = count($this->MB->post_ids);
		
		$data['mb'] = $this->MB;
		$this->load->view('rss',$data);
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */