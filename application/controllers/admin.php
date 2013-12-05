<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller {
	/**
	 * 构造函数
	 *
	 */
    public function __construct()
	{
		parent::__construct();
		
		//默认管理后台路径保护(使用当前控制器可以绕过配置文件中的管理后路径而访问管理后台，所以在此保护)
		$current_admin_url = $this->uri->segment(1);
		
		if($current_admin_url != config_item('admin_url'))
		{
			show_404();//返回404状态
		}
    }
	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		//如果登录直接跳转到文章列表页
		if($this->MB->is_login())
		{
			redirect(admin_url('post'));
		}
		//登录后台，登录成功跳转到文章列表页
		$login = $this->input->post('login');
		$user = $this->input->post('user');
		$pass = $this->input->post('pass');
		if($login && $this->MB->login($user, $pass))
		{
			redirect(admin_url('post'));
		}
		
		//默认显示登录页面
		$this->load->view('admin/index');
	}
	/**
	 * 站点设置
	 *
	 */
	public function setting()
	{
		$this->MB->check_login();//检测登录	
		$save = $this->input->post('save');
		if($save)
		{
			$config['site_name'] = $this->input->post('site_name');
			$config['site_desc'] = $this->input->post('site_desc');
			$config['cache_time']= $this->input->post('cache_time');
			$config['page_size'] = $this->input->post('page_size');
			$config['theme'] 	 = $this->input->post('theme');
			$config['user_name'] = $this->input->post('user_name');		
			//密码处理
			$user_pass = $this->input->post('user_pass');
			$config['user_pass'] = config_item('user_pass');//不更改的情况使用原来密码
			if($user_pass)
			{
				$config['user_pass'] = $user_pass;//更改密码的情况使用新密码
			}
			$config['user_nick'] = $this->input->post('user_nick');
			$config['user_about'] = $this->input->post('user_about');
			$comment_code = $this->input->post('comment_code');
			//评论代码转码
			$config['comment_code'] = get_magic_quotes_gpc() ? stripslashes(trim($comment_code)) : trim($comment_code);
			//用户名是否修改
			$user_name_changed = $config['user_name'] != config_item('user_name');
			
			
			//写入配置文件
			$message = $this->MB->save_config($config, $config['user_name'], $user_pass, $user_name_changed);
			//跳转并刷新
			redirect(site_url(admin_url('setting')). '?msg=' . $message);
		}
		
		//默认显示设置页
		$data['current_page'] = $this->current_page;
		$data['message'] = $this->input->get('msg');
		$this->load->view('admin/header', $data);
		$this->load->view('admin/setting');
		$this->load->view('admin/footer');
	}
	/**
	 * 文章列表
	 */
	public function post()
	{
		$this->MB->check_login();//检测登录
		
		$state = $this->input->get('state'); //文章状态
		$posts = $this->MB->load_posts($state);// 加载文章列表
		
		
		$filter_date = $this->input->get('date'); //按日期过滤
		$filter_tag = $this->input->get('tag'); //按tag过滤
		$page_num = $this->input->get('page'); //分页页码
		
		$delete = $this->input->get('delete');
		$revert = $this->input->get('revert');
		$apply = $this->input->get('apply');
		$ids = $this->input->get('ids');
		//回收和删除操作
		if($delete || ($apply && $apply == 'delete'))
		{
			//批量回收/删除操作
			if($apply && $apply == 'delete')
			{

				$ids = explode(',', $ids);
				$idssssssssss= '';
				foreach ($ids as $id)
				{
					if (trim($id) == '')
						continue;
					$this->MB->delete_post($id, $posts, $state);//回收/删除操作
					$posts = $this->MB->load_posts($state);// 加载文章列表
				}
			}
			else//单个回收/删除操作
			{
				$this->MB->delete_post($delete, $posts, $state);//回收/删除操作
			}
			$this->cache->clean();//清空缓存
			redirect(site_url(admin_url('post')). '?done=true&state='.$state);
		}
		//还原操作
		if($revert || ($apply && $apply == 'revert'))
		{
			//批量还原操作
			if($apply && $apply == 'revert')
			{

				$ids = explode(',', $ids);
				$idssssssssss= '';
				foreach ($ids as $id)
				{
					if (trim($id) == '')
						continue;
					$this->MB->revert_post($id, $posts, $state);//还原操作
					$posts = $this->MB->load_posts($state);// 加载文章列表
				}
			}
			else //单个还原操作
			{
				$this->MB->revert_post($revert, $posts, $state);//还原操作
			}
			$this->cache->clean();//清空缓存
			redirect(site_url(admin_url('post')). '?done=true&state='.$state);
		}
		
		$message = '';
		$done = $this->input->get('done'); //操作状态
		if ($done) {
		  $message = '操作成功';
		}
			
		//加载页面
		$data['message'] = $message;
		$data['state'] = $state;
		$data['filter_date'] = $filter_date;
		$data['filter_tag'] = $filter_tag;
		$data['page_num'] = $page_num;
		$data['current_page'] = $this->current_page;
		$data['posts'] = $posts;
		$this->load->view('admin/header', $data);
		$this->load->view('admin/post');
		$this->load->view('admin/footer');
	}
	/**
	 * 文章编辑
	 */
	public function post_edit()
	{
		$this->MB->check_login();//检测登录
		//变量声明
		$data['post_id']			= '';
		$data['post_state']			= '';
		$data['post_title']			= '';
		$data['post_content']		= '';
		$data['post_tags']			= array();
		$data['post_date']			= '';
		$data['post_time']			= '';
		$data['post_can_comment']	= '';
		$data['error_msg']			= '';
		$data['succeed']			= false;
		
		//编辑文章操作
		$_IS_POST_BACK_ = $this->input->post('_IS_POST_BACK_');
		if($_IS_POST_BACK_)
		{
			$post_id          = $this->input->post('id');
			$post_state       = $this->input->post('state');
			$post_title       = trim($this->input->post('title'));
			if($post_title == '在此输入标题')
			{
				$post_title = '';
				
			}
			//文章内容处理
			$content     		= $this->input->post('content');
			$post_content     = get_magic_quotes_gpc() ? stripslashes(trim($content)) : trim($content);
			//标签处理
			$tags			  = $this->input->post('tags');
			if($tags == '在此输入标签，多个标签用英语逗号(,)分隔')
			{
				$tags = '';
				
			}
			$post_tags        = explode(',', trim($tags));
			
			
			//日期时间处理
			$post_date				= mdate("%Y-%m-%d");
			$post_time				= mdate("%h:%i:%s");
			
			$year = $this->input->post('year');
			$month = $this->input->post('month');
			$day = $this->input->post('day');
			$hourse = $this->input->post('hourse');
			$minute = $this->input->post('minute');
			$second = $this->input->post('second');
			if($year)
				$post_date = substr_replace($post_date, $year, 0, 4);
			if($month)
				$post_date = substr_replace($post_date, $month, 5, 2);
			if($day)
				$post_date = substr_replace($post_date, $day, 8, 2);
			if($hourse)
				$post_time = substr_replace($post_time, $hourse, 0, 2);
			if($minute)
				$post_time = substr_replace($post_time, $minute, 3, 2);
			if($second)
				$post_time = substr_replace($post_time, $second, 6, 2);
			
			$post_can_comment = $this->input->post('can_comment');
			
			//标签去除空格
			$post_tags_count = count($post_tags);
			for ($i = 0; $i < $post_tags_count; $i ++) {
				$trim = trim($post_tags[$i]);
				if ($trim == '') {
					unset($post_tags[$i]);
				} 
				else 
				{
					$post_tags[$i] = $trim;
				}
			}
			reset($post_tags);
			//新建文章
			if ($post_id == '')
			{
				$file_names = short_url($post_title);
				foreach ($file_names as $file_name)
				{
					$file_path = $this->MB->jdb_file_path($file_name);
					if (!is_file($file_path))
					{
						$post_id = $file_name;
						break;
					}
					
				}
				
			}
			else //文章修改
			{
				$file_path = $this->MB->jdb_file_path($post_id);
				$jdb = unserialize(file_get_contents($file_path));
				$post_old_state = $jdb['state'];
				if ($post_old_state != $post_state) 
				{
					$posts = $this->MB->load_posts($post_old_state);
					unset($posts[$post_id]);
					$this->MB->save_posts($post_old_state, $posts); //写入文件
				}
			}
			
			
			$data = array(
				'id'          => $post_id,
				'state'       => $post_state,
				'title'       => $post_title,
				'tags'        => $post_tags,
				'date'        => $post_date,
				'time'        => $post_time,
				'can_comment' => $post_can_comment,
			);
			$posts = $this->MB->load_posts($post_state);
			$posts[$post_id] = $data;
			uasort($posts, "post_sort");
			$this->MB->save_posts($post_state, $posts);//写入索引文件
			$data['content'] = $post_content;
			$this->MB->save_posts_jdb($post_id, $data);//写入数据文件
			
			//显示数据赋值
			$data['post_id']		= $post_id;
			$data['post_state']		= $post_state;
			$data['post_title']		= $post_title;
			$data['post_content']	= $post_content;
			$data['post_tags']		= $post_tags;
			$data['post_date']		= $post_date;
			$data['post_time']		= $post_time;
			$data['post_can_comment']	= $post_can_comment;
			
			$data['succeed'] = true;
			
			$this->cache->clean();//清空缓存
		}

		//读取文章操作
		$id = $this->input->get('id');
		if($id)
		{
			$file_path = $this->MB->jdb_file_path($id);
			$jdb = unserialize(file_get_contents($file_path));
			$data['post_id']		= $jdb['id'];
			$data['post_state']		= $jdb['state'];
			$data['post_title']		= $jdb['title'];
			$data['post_content']	= $jdb['content'];
			$data['post_tags']		= $jdb['tags'];
			$data['post_date']		= $jdb['date'];
			$data['post_time']		= $jdb['time'];
			$data['post_can_comment']	= isset($jdb['can_comment']) ? $jdb['can_comment'] : '1';
		}
		
		//加载页面
		$data['current_page'] = $this->current_page;
		$this->load->view('admin/header', $data);
		$this->load->view('admin/post-edit');
		$this->load->view('admin/footer');
		
	}
	
	/**
	 * 独立页面列表
	 */
	public function page()
	{
		$this->MB->check_login();//检测登录
		
		$state = $this->input->get('state'); //文章状态
		$pages = $this->MB->load_pages($state);// 加载文章列表
		
		
		$filter_date = $this->input->get('date'); //按日期过滤
		$page_num = $this->input->get('page'); //分页页码
		
		$delete = $this->input->get('delete');
		$revert = $this->input->get('revert');
		$apply = $this->input->get('apply');
		$ids = $this->input->get('ids');
		//回收和删除操作
		if($delete || ($apply && $apply == 'delete'))
		{
			//批量回收/删除操作
			if($apply && $apply == 'delete')
			{

				$ids = explode(',', $ids);
				$idssssssssss= '';
				foreach ($ids as $id)
				{
					if (trim($id) == '')
						continue;
					$this->MB->delete_page($id, $pages, $state);//回收/删除操作
					$pages = $this->MB->load_pages($state);// 加载文章列表
				}
			}
			else//单个回收/删除操作
			{
				$this->MB->delete_page($delete, $pages, $state);//回收/删除操作
			}
			$this->cache->clean();//清空缓存
			redirect(site_url(admin_url('page')). '?done=true&state='.$state);
		}
		//还原操作
		if($revert || ($apply && $apply == 'revert'))
		{
			//批量还原操作
			if($apply && $apply == 'revert')
			{

				$ids = explode(',', $ids);
				$idssssssssss= '';
				foreach ($ids as $id)
				{
					if (trim($id) == '')
						continue;
					$this->MB->revert_page($id, $pages, $state);//还原操作
					$pages = $this->MB->load_pages($state);// 加载文章列表
				}
			}
			else //单个还原操作
			{
				$this->MB->revert_page($revert, $pages, $state);//还原操作
			}
			$this->cache->clean();//清空缓存
			redirect(site_url(admin_url('page')). '?done=true&state='.$state);
		}
		
		$message = '';
		$done = $this->input->get('done'); //操作状态
		if ($done) {
		  $message = '操作成功';
		}
		
		
		
		//加载页面
		$data['message'] = $message;
		$data['state'] = $state;
		$data['filter_date'] = $filter_date;
		$data['page_num'] = $page_num;
		$data['current_page'] = $this->current_page;
		$data['pages'] = $pages;
		$this->load->view('admin/header', $data);
		$this->load->view('admin/page');
		$this->load->view('admin/footer');
	}
	/**
	 * 独立页面编辑
	 */
	public function page_edit()
	{
		$this->MB->check_login();//检测登录
		
		//变量声明
		$data['page_file']			= '';
		$data['page_path']			= '';
		$data['page_state']			= '';
		$data['page_title']			= '';
		$data['page_content']		= '';
		$data['page_date']			= '';
		$data['page_time']			= '';
		$data['page_can_comment']	= '';
		$data['error_msg']			= '';
		$data['succeed']			= false;
		
		//编辑文章操作
		$_IS_POST_BACK_ = $this->input->post('_IS_POST_BACK_');
		if($_IS_POST_BACK_)
		{
			$page_file        = $this->input->post('file');
			$page_path        = $this->input->post('path');
			$page_state       = $this->input->post('state');
			$page_title       = trim($this->input->post('title'));
			if($page_title == '在此输入标题')
			{
				$page_title = '';
				
			}
			//文章内容处理
			$content     		= $this->input->post('content');
			$page_content     = get_magic_quotes_gpc() ? stripslashes(trim($content)) : trim($content);
			
			
			//日期时间处理
			$page_date				= mdate("%Y-%m-%d");
			$page_time				= mdate("%h:%i:%s");
			
			$year = $this->input->post('year');
			$month = $this->input->post('month');
			$day = $this->input->post('day');
			$hourse = $this->input->post('hourse');
			$minute = $this->input->post('minute');
			$second = $this->input->post('second');
			if($year)
				$page_date = substr_replace($page_date, $year, 0, 4);
			if($month)
				$page_date = substr_replace($page_date, $month, 5, 2);
			if($day)
				$page_date = substr_replace($page_date, $day, 8, 2);
			if($hourse)
				$page_time = substr_replace($page_time, $hourse, 0, 2);
			if($minute)
				$page_time = substr_replace($page_time, $minute, 3, 2);
			if($second)
				$page_time = substr_replace($page_time, $second, 6, 2);
			
			$page_can_comment = $this->input->post('can_comment');
			
			$page_path_part  = explode('/', $page_path);
			$page_path_count = count($page_path_part);
			for ($i = 0; $i < $page_path_count; $i ++)
			{
				$trim = trim($page_path_part[$i]);
				if ($trim == '')
				{
					unset($page_path_part[$i]);
				}
				else
				{
					$page_path_part[$i] = $trim;
				}
			}
			reset($page_path_part);
			
			$page_path = implode('/', $page_path_part);
			$error_msg = '';
			if ($page_title == '')
			{
				$error_msg = '页面标题不能为空';
			}
			else if ($page_path == '')
			{
				$error_msg = '页面路径不能为空';
			}
			else
			{
				if ($page_file == '')
				{
					$file_names = short_url($page_title);
					foreach ($file_names as $file_name)
					{
						$file_path = $this->MB->jdb_file_path($file_name, 'pages');
						if (!is_file($file_path))
						{
							$page_file = $file_name;
							 break;
						}
					}
					
				}
				else
				{
					$file_path = $this->MB->jdb_file_path($page_file, 'pages');
					$jdb = unserialize(file_get_contents($file_path));
					$page_old_path  = $jdb['path'];
					$page_old_state = $jdb['state'];
					if ($page_old_state != $page_state || $page_old_path != $page_path)
					{
						$pages = $this->MB->load_pages($page_old_state, 'pages');
						unset($pages[$page_old_path]);
						$this->MB->save_pages($page_old_state, $pages); //写入文件
						
					}
				}
				
			}

			$data = array(
				'file'        => $page_file,
				'path'        => $page_path,
				'state'       => $page_state,
				'title'       => $page_title,
				'date'        => $page_date,
				'time'        => $page_time,
				'can_comment' => $page_can_comment,
			);
			$pages = $this->MB->load_pages($page_state, 'pages');
			$pages[$page_path] = $data;
			ksort($pages);
			$this->MB->save_pages($page_state, $pages);//写入索引文件
			$data['content'] = $page_content;
			$this->MB->save_pages_jdb($page_file, $data);//写入数据文件
			
			//显示数据赋值
			$data['page_file']		= $page_file;
			$data['page_path']		= $page_path;
			$data['page_state']		= $page_state;
			$data['page_title']		= $page_title;
			$data['page_content']	= $page_content;
			$data['page_date']		= $page_date;
			$data['page_time']		= $page_time;
			$data['page_can_comment']	= $page_can_comment;
			
			$data['error_msg'] = $error_msg;
			$data['succeed'] = true;
			
			$this->cache->clean();//清空缓存
		}

		//读取文章操作
		$file = $this->input->get('file');
		if($file)
		{
			$file_path = $this->MB->jdb_file_path($file, 'pages');
			$jdb = unserialize(file_get_contents($file_path));
			$data['page_file']		= $jdb['file'];
			$data['page_path']		= $jdb['path'];
			$data['page_state']		= $jdb['state'];
			$data['page_title']		= $jdb['title'];
			$data['page_content']	= $jdb['content'];
			$data['page_date']		= $jdb['date'];
			$data['page_time']		= $jdb['time'];
			$data['page_can_comment']	= isset($jdb['can_comment']) ? $jdb['can_comment'] : '1';
		}
		
		//加载页面
		$data['current_page'] = $this->current_page;
		$this->load->view('admin/header', $data);
		$this->load->view('admin/page-edit');
		$this->load->view('admin/footer');
	}
	/**
	 * 图片上传
	 *
	 */
	public function upload()
	{
		$config['upload_path'] = './static/uploads/';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['overwrite'] = FALSE;
		$config['max_size'] = '2048';
		$config['max_width']  = '0';
		$config['max_height']  = '0';
		$config['max_filename']  = '0';
		$config['encrypt_name']  = TRUE;
		
		$this->load->library('upload', $config);
		
		$field_name = "filedata";
		if ( ! $this->upload->do_upload($field_name))
		{
			echo '{"err":"'.$this->upload->display_errors('','').'","msg":""}';
		}
		else
		{
			$data = $this->upload->data();
			echo '{"err":"","msg":"'.base_url('static/uploads/' . $data['file_name']).'"}';
		}		
	}
	
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */