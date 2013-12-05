<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php $this->view('title');?>
<link href="/favicon.ico" rel="shortcut icon" />
<link href="<?php $mb->theme_url('style.css'); ?>" type="text/css" rel="stylesheet"/>
<script src="http://static.weixiaodeyu.com/atm/js/miniblog.js" type="text/javascript"></script>
</head>
<body>
<div id="main">
  <div id="header">
    <div id="sitename"><a href="<?php echo base_url(); ?>" title="<?php $mb->site_desc(); ?>"><?php $mb->site_name(); ?></a></div>
  </div>
  <div class="clear"></div>
  <div id="side">
    <div class="search">
        <input type="text" name="search" class="keyword" id="keyword" value="" />
        <input type="button" class="submit" value="搜索" onclick="Search()" />
    </div>
    <div class="photo"><img src="<?php $mb->theme_url('photo.jpg'); ?>"></div>
    <div class="about"><?php echo config_item('user_about');?></div>
    <div id="navbar"><a href="<?php echo base_url(); ?>" class="home" title="首页">首页</a><a href="<?php echo site_url('archive'); ?>" class="archive" title="文章存档">文章存档</a><a href="<?php echo site_url('contact'); ?>" class="contact" title="联系方式">联系方式</a><a href="<?php echo base_url('rss'); ?>" class="rss" title="RSS订阅" target="_blank">RSS订阅</a></div>
    <div class="clear"></div>
    <script type="text/javascript">atm('gg200x200');</script>
    <div class="clear"></div>
    <div id="footer">Powered by <a href="http://blog.weixiaodeyu.com/miniblog/" target="_blank">MiniBlog</a>&nbsp;&nbsp;<script src="http://s13.cnzz.com/stat.php?id=4854734&web_id=4854734" language="JavaScript"></script></div>
  </div>
  <div id="content">
    <div id="content_box">
      <?php if ($mb->is_post()) { ?>
      <div class="post">
        <?php if($mb->post['title']){?><h1 class="title"><?php $mb->the_link(); ?></h1><?php }?>
        <div class="content">
          <?php $mb->the_content(); ?>
        </div>
            <div class="post_meta">
              <div class="post_date"><?php $mb->the_date(); ?></div>
              <?php if($mb->post['tags']){?><div class="post_tag"><?php $mb->the_tags('','',''); ?></div><?php }?>
              <div class="post_comm"><a href="<?php $mb->post_link(); ?>#comm">评论</a></div>
            </div>
      </div>
        <?php if ($mb->can_comment()) { ?>
        <div id="comm"><?php $mb->comment_code(); ?></div>
        <?php } ?>
      <?php } else if ($mb->is_page()) { ?>
      <div class="post">
        <h1 class="title"><?php $mb->page_title(); ?></h1>
        <div class="content">
          <?php $mb->the_content(); ?>
        </div>
      </div>
      <?php if ($mb->can_comment()) { ?>
      <div id="comm"><?php $mb->comment_code(); ?></div>
      <?php } ?>
      <?php } else if ($mb->is_archive()) { ?>
      <div class="post">
        <h1 class="title">文章存档</h1>
        <div class="content">
<table width="280" border="0" align="center" cellpadding="0" cellspacing="0" style="margin:30px auto;"><tbody><tr><td width="140" style="vertical-align:top;"><h1 class="date_list">月份</h1><ul id="list"><?php $mb->date_list(); ?></ul></td><td width="140" style="vertical-align:top;"><h1 class="tag_list">标签</h1><ul id="list"><?php $mb->tag_list(); ?></ul></td></tr></tbody></table>
        </div>
      </div>
      <?php } else { ?>
      <?php if ($mb->is_tag()) { ?>
      <div id="page_info">存档标签：<span><?php $mb->tag_name(); ?></span></div>
      <?php } else if ($mb->is_date()) { ?>
      <div id="page_info">存档日期：<span><?php $mb->date_name(); ?></span></div>
      <?php } else if ($mb->is_search()) { ?>
      <div id="page_info">搜索：<span><?php $mb->date_name(); ?></span></div>
      <?php } ?>
      <?php while ($mb->next_post()) { ?>
      <div class="post">
        <?php if($mb->post['title']){?><h1 class="title"><?php $mb->the_link(); ?></h1><?php }?>
        <div class="content">
          <?php $mb->the_content(); ?>
        </div>
            <div class="post_meta">
              <div class="post_date"><?php $mb->the_date(); ?></div>
              <?php if($mb->post['tags']){?><div class="post_tag"><?php $mb->the_tags('','',''); ?></div><?php }?>
              <div class="post_comm"><a href="<?php $mb->post_link(); ?>#comm">评论</a></div>
            </div>
      </div>
      <?php } ?>
    </div>
      <div class="clear"></div>
      <div id="page_bar">
        <?php if ($mb->has_new()) { ?><?php $mb->goto_new('«'); ?><?php } ?>
        <?php if ($mb->has_old()) { ?><?php $mb->goto_old('»'); ?><?php } ?>
      </div>
      <?php } ?>
  </div>
</div>
<script type="text/javascript" >
function Search()
{
	var key = document.getElementById('keyword').value;
	if(key != '')
	{
		location.href = '<?php echo base_url('search');?>/' + key ;
	}
	else
	{
		alert('请输入关键词');
	}
}
</script>
</body>
</html>
