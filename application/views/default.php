<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <?php $this->view('title');?>
  
  <link href="/favicon.ico" rel="icon" type="image/x-ico">
  <link rel="stylesheet" href="<?php $mb->theme_url('style.css'); ?>" media="screen" type="text/css">
  <!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script src="http://static.weixiaodeyu.com/atm/js/miniblog.js" type="text/javascript"></script>
</head>
<body>
<header id="header" class="inner">
    <div class="alignleft">
      <h1><a href="<?php echo base_url(); ?>"><?php $mb->site_name(); ?></a></h1>
      <h2><a href="<?php echo base_url(); ?>"><?php $mb->site_desc(); ?></a></h2>
    </div>
    <nav id="main-nav" class="alignright">
    <ul>
        <li><a href="<?php echo base_url(); ?>" title="首页">首页</a></li>
        <li><a href="<?php echo site_url('archive'); ?>" title="文章存档">文章存档</a></li>
        <li><a href="<?php echo site_url('contact'); ?>" title="联系方式">联系方式</a></li>
        <li><a href="<?php echo base_url('rss'); ?>" title="RSS订阅" target="_blank">RSS</a></li>
        <li><a href="javascript:High();" title="High一下吧~">High一下</a></li>
    </ul>
    </nav>
    <div class="clearfix"></div>
</header>


<div id="content" class="inner">
  <div id="main-col" class="alignleft">
    <div id="wrapper">
    <?php if ($mb->is_post()) : //文章页?>
        <article class="post">
      
          <div class="post-content">
          <?php if($mb->post['title']):?>
            <header>              
                <div class="icon"></div>
                <time><a href="<?php $mb->post_link(); ?>"><?php $mb->the_date(); ?></a></time>
                <h1 class="title"><?php $mb->the_link(); ?></h1>
            </header>
          <?php endif;?>
            <div class="entry">
                <?php $mb->the_content(); ?>
            </div>
            
            <footer>
                  <div class="alignleft">
                    <?php $mb->the_tags('','',''); ?>
                  </div>
                  <div class="alignright">
                    
                  </div>
              <div class="clearfix"></div>
            </footer>
            
          </div>
        </article>
        <?php if ($mb->can_comment()) : ?>
        <section id="comment">
			<?php $mb->comment_code(); ?>
        </section>
		<?php endif;?>
        
    <?php elseif ($mb->is_page())://独立页面?>
    
         <article class="post">
      
          <div class="post-content">
            <header>              
                <h1 class="title"><?php $mb->page_title(); ?></h1>
            </header>
            <div class="entry">
                <?php $mb->the_content(); ?>
            </div>
            
            <footer>
                  <div class="alignleft">

                  </div>
                  <div class="alignright">
                    
                  </div>
              <div class="clearfix"></div>
            </footer>
            
          </div>
        </article>
        <?php if ($mb->can_comment()) : ?>
        <section id="comment">
			<?php $mb->comment_code(); ?>
        </section>
		<?php endif;?>
    <?php elseif ($mb->is_archive())://归档页?>
        <article class="post">
      
          <div class="post-content">
            <header>              
                <h1 class="title">文章存档</h1>
            </header>
            <div class="entry">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                        <td width="50%"><h2>月份存档</h2><ul id="list"><?php $mb->date_list(); ?></ul></td>
                        <td width="50%"><h2>标签存档</h2><ul id="list"><?php $mb->tag_list(); ?></ul></td>
                    </tr>
                </tbody>
            </table>
                
            </div>
            
            <footer>
                  <div class="alignleft">

                  </div>
                  <div class="alignright">
                    
                  </div>
              <div class="clearfix"></div>
            </footer>
            
          </div>
        </article> 
    <?php else: //默认页?>
    
     	<?php if ($mb->is_tag()): ?>
        <h2 class="archive-title">存档标签：<?php $mb->tag_name(); ?></h2>
        <?php elseif ($mb->is_date()): ?>
        <h2 class="archive-title">存档月份：<?php $mb->date_name(); ?></h2>
        <?php elseif ($mb->is_search()): ?>
        <h2 class="archive-title">搜索结果：<?php $mb->date_name(); ?></h2>
        <?php endif;?>
   
		<?php while ($mb->next_post()) : ?>
            <article class="post">
          
              <div class="post-content">
              <?php if($mb->post['title']):?>
                <header>              
                    <div class="icon"></div>
                    <time><a href="<?php $mb->post_link(); ?>"><?php $mb->the_date(); ?></a></time>
                    <h1 class="title"><?php $mb->the_link(); ?></h1>
                </header>
              <?php endif;?>
                <div class="entry">
                    <?php $mb->the_content(); ?>
                </div>
                
                <footer>
                      <div class="alignleft">
                      <a href="<?php $mb->post_link(); ?>" class="more-link">阅读全文</a>
                        <?php $mb->the_tags('','',''); ?>
                      </div>
                      <div class="alignright">
                        <a href="<?php $mb->post_link(); ?>#comment" class="comment-link">评论</a>
                      </div>
                  <div class="clearfix"></div>
                </footer>
                
              </div>
            </article>
        <?php endwhile;?>   

        <nav id="pagination">
            <?php if ($mb->has_new()) { ?><?php $mb->goto_new('上一页', 'alignleft prev'); ?><?php } ?>
            <?php if ($mb->has_old()) { ?><?php $mb->goto_old('下一页', 'alignright next'); ?><?php } ?>
            <div class="clearfix"></div>
        </nav>    
    
    <?php endif;?>
    
    
    </div><!--end of #wrapper-->
  </div><!--end of #main-col-->
  <aside id="sidebar" class="alignright">
    <div class="search">
       <input type="text" class="keyword" id="search" placeholder="搜索" />
    </div>  
    
    <div class="widget tag">
    <h3 class="title">简介</h3>
        <ul class="entry">
        <li><div class="photo"><img src="<?php $mb->theme_url('photo.jpg'); ?>"></div></li>
        <li><div class="about"><?php echo config_item('user_about');?></div></li>
        </ul>
    </div>
    <?php if ($mb->is_home() || $mb->is_date() || $mb->is_search() )://在首页、日期归档页、搜索页，显示标签?>
    <div class="widget tag">
      <h3 class="title">标签</h3>
      <?php
	  //标签计算
      $tags_array = array();
	  for ($i = 0; $i < $mb->post_count; $i ++) {
		$post_id = $mb->post_ids[$i];
		$post = $mb->posts[$post_id];
		$tags_array = array_merge($tags_array, $post['tags']);
	  }
	  $mb->tags  = array_values(array_unique($tags_array));
	  ?>
      <ul class="entry">
		<?php $mb->tag_list(); ?>      
      </ul>
    </div>
    <?php endif;?>
    <div class="widget tag">
      <h3 class="title">赞助商</h3>
      <ul class="entry">
		<li style="text-align:center;"><script type="text/javascript">atm('gg200x200');</script></li>
      </ul>
    </div>
     
  </aside>
  <div class="clearfix"></div>
</div>


<footer id="footer" class="inner">
    <div class="alignleft">
      &copy; 2013 <?php $mb->site_name(); ?>
    </div>
    <div class="alignright">
      Powered by <a href="http://blog.weixiaodeyu.com/miniblog/" target="_blank">MiniBlog</a>
    </div>
    <div class="clearfix"></div>
</footer>
<script src="<?php $mb->theme_url('high.js'); ?>"></script>
<script type="text/javascript">
(function($){
	
	$('#search').keydown(function(e){
		var key = $('#search').val();
		if(e.keyCode == 13 && key != ''){
		   location.href = '<?php echo base_url('search');?>/' + key ; //转到搜索
		}
	});
	
})(jQuery);

</script>

</body>
</html>
