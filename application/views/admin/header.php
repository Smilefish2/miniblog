<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8" />
  <title>后台管理 - <?php echo config_item('site_name');?></title>
  <link style="text/css" rel="stylesheet" href="<?php echo base_url('themes/admin/style.css');?>" />
</head>
<body>
  <div id="menu">
    <h3 id="menu_title"><a href="<?php echo site_url();?>" target="_blank"><?php echo htmlspecialchars(config_item('site_name')); ?></a></h3>
    <ul>
      <li <?php echo $current_page == 'post' || $current_page == 'post_edit' ? 'class="current"' : ''; ?>><a href="<?php echo site_url(admin_url('post'));?>">文章</a></li>
      <li <?php echo $current_page == 'page' || $current_page == 'page_edit' ? 'class="current"' : ''; ?>><a href="<?php echo site_url(admin_url('page'));?>">页面</a></li>
      <li <?php echo $current_page == 'setting' ? 'class="current"' : ''; ?>><a href="<?php echo site_url(admin_url('setting'));?>">设置</a></li>
    </ul>
    <div class="clear"></div>
  </div>
  <div id="content">
    <div id="content_box">
