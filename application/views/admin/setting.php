<form action="" method="post">
  <?php if ($message) { ?>
  <div class="updated"><?php echo $message;?></div>
  <?php } ?>
  <div class="admin_page_name">站点设置</div>
  <div class="small_form small_form2">
    <div class="field">
      <div class="label">网站标题</div>
      <input class="textbox" type="text" name="site_name" value="<?php echo htmlspecialchars(config_item('site_name')); ?>" />
      <div class="info">网站title、网站名称，必须填写。</div>
    </div>
    <div class="clear"></div>
    <div class="field">
      <div class="label">网站描述</div>
      <input class="textbox" type="text" name="site_desc" value="<?php echo htmlspecialchars(config_item('site_desc')); ?>" />
      <div class="info">显示在网站title后面。部分主题也会显示出来。</div>
    </div>
    <div class="clear"></div>
    <div class="field">
      <div class="label">缓存时间</div>
      <input class="textbox" type="text" name="cache_time" value="<?php echo htmlspecialchars(config_item('cache_time')); ?>" />
      <div class="info">文章、页面的缓存时间，以秒为单位,默认300秒。</div>
    </div>
    <div class="clear"></div>
    <div class="field">
      <div class="label">分页大小</div>
      <input class="textbox" type="text" name="page_size" value="<?php echo htmlspecialchars(config_item('page_size')); ?>" />
      <div class="info">首页显示文章条数，请填入数字。</div>
    </div>
    <div class="clear"></div>
    <div class="field">
      <div class="label">前台主题</div>
      <select class="textbox" name="theme">
      <option value="default" <?php if(htmlspecialchars(config_item('theme')) == 'default'): ?> selected="selected" <?php endif;?>>默认主题</option>
      <option value="blogmi" <?php if(htmlspecialchars(config_item('theme')) == 'blogmi'): ?> selected="selected" <?php endif;?>>博客迷主题</option>
      </select>
      <div class="info">这里选择的值是主题文件的名称，调用views下面的同名文件,制作主题时在“application/views”建立文件，资源文件放在“/themes/主题名称/”文件夹下</div>
    </div>
    <div class="clear"></div>
    <div class="field">
      <div class="label">后台帐号</div>
      <input class="textbox" type="text" name="user_name" value="<?php echo htmlspecialchars(config_item('user_name')); ?>" />
      <div class="info">默认为admin。</div>
    </div>
    <div class="clear"></div>
    <div class="field">
      <div class="label">后台密码</div>
      <input class="textbox" type="password" name="user_pass" />
      <div class="info">默认为admin。</div>
    </div>
    <div class="clear"></div>
    <div class="field">
      <div class="label">站长昵称</div>
      <input class="textbox" type="text" name="user_nick" value="<?php echo htmlspecialchars(config_item('user_nick')); ?>" />
      <div class="info">页面中不显示，显示为RSS订阅中的"作者"。</div>
    </div>
    <div class="clear"></div>    
    <div class="field">
      <div class="label">站长简介</div>
      <textarea rows="3" class="textbox" name="user_about"><?php echo htmlspecialchars(config_item('user_about')); ?></textarea>
      <div class="info">
        <p>显示在前台照片下方</p>
      </div>
    </div>
    <div class="clear"></div>
    <div class="field">
      <div class="label">评论代码</div>
      <textarea rows="5" class="textbox" name="comment_code"><?php echo htmlspecialchars(config_item('comment_code')); ?></textarea>
      <div class="info">
        <p>第三方评论服务所提供的评论代码。设置后可拥有评论功能。每个页面、每篇文章可单独设置是否允许评论。</p>
        <p>例如：<a href="http://pinglun.la/" target="_blank">评论啦</a>、<a href="http://duoshuo.com/" target="_blank">多说</a>、<a href="http://disqus.com/" target="_blank">Disqus</a>等。</p>
      </div>
    </div>
    <div class="clear"></div>
    <div class="field">
      <div class="label"></div>
      <div class="field_body"><input class="button" type="submit" name="save" value="保存设置" /></div>
      <div class="info"></div>
    </div>
    <div class="clear"></div>
  </div>
</form>
