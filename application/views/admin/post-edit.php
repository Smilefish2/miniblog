<script type="text/javascript">
function empty_textbox_focus(target){
  if (target.temp_value != undefined && target.value != target.temp_value)
    return;
  target.temp_value = target.value;
  target.value='';
  target.style.color='#000';
}
function empty_textbox_blur(target) {
  if (target.value == '') {
    target.style.color='#888';
    target.value = target.temp_value;
  }
}
</script>
<form action="" method="post">
  <input type="hidden" name="_IS_POST_BACK_" value="ture"/>
  <?php if ($succeed) { ?>
  <?php if ($post_state == 'publish') { ?>
  <div class="updated">文章已发布。 <a href="<?php echo site_url($post_id);?>" target="_blank">查看文章</a></div>
  <?php } else { ?>
  <div class="updated">文章已保存到"草稿箱"。 <a href="<?php echo site_url(admin_url('post'));?>?state=draft">打开草稿箱</a></div>
  <?php } ?>
  <?php } ?>
  <div class="admin_page_name">
  <?php if ($post_id == '') echo "撰写文章"; else echo "编辑文章"; ?>
  </div>
  <div style="margin-bottom:20px;">
    <input name="title" type="text" class="edit_textbox" value="<?php
    if ($post_title == "") {
      echo '在此输入标题" " style="color:#888;" onfocus="empty_textbox_focus(this)" onblur="empty_textbox_blur(this)';
    }
    else {
      echo htmlspecialchars($post_title);
    }
    ?>"/>
  </div>
  <div style="margin-bottom:20px;">
    <script language="javascript" src="<?php echo base_url('static/xheditor/jquery.js');?>"></script>
	<script language="javascript" src="<?php echo base_url('static/xheditor/xheditor.js');?>"></script>
	<script language="javascript">
		$(pageInit);
		function pageInit(){
			$('#elm1').xheditor({urlType:'abs',internalScript:true,inlineScript:true,emotPath:'<?php echo base_url('static/xheditor/xheditor_emot/');?>',html5Upload:false,upImgUrl:"<?php echo base_url(config_item('admin_url') . '/upload');?>",upImgExt:"jpg,jpeg,gif,png",onUpload:insertUpload});
		}
		function insertUpload(arrMsg){
			var i,msg;
			for(i=0;i<arrMsg.length;i++){
				msg=arrMsg[i];
				$("#uploadList").append('<option value="'+msg.id+'">'+msg.localname+'</option>');
			}
		}
	</script>
    <textarea id="elm1" name="content" style="width: 860px; height: 250px; display: none; "><?php echo htmlspecialchars($post_content); ?></textarea>
  </div>
  <div style="margin-bottom:20px;">
    <input name="tags" type="text" class="edit_textbox" value="<?php
    if (count($post_tags) == 0) {
      echo '在此输入标签，多个标签用英语逗号(,)分隔" " style="color:#888;" onfocus="empty_textbox_focus(this)" onblur="empty_textbox_blur(this)';
    }
    else {
      echo htmlspecialchars(implode(',', $post_tags));
    }
    ?>"/>
  </div>
  <div style="margin-bottom:20px;text-align:right">
    <div style="float:left">
    时间：
    <select name="year">
      <option value=""></option>
<?php $year = substr($post_date, 0, 4); for ($i = 1990; $i <= 2030; $i ++) { ?>
      <option value="<?php echo $i; ?>" <?php if ($year == $i) echo 'selected="selected";' ?>><?php echo $i; ?></option>
<?php } ?>
    </select> -
    <select name="month">
      <option value=""></option>
<?php $month = substr($post_date, 5, 2); for ($i = 1; $i <= 12; $i ++) { $m = sprintf("%02d", $i); ?>
      <option value="<?php echo $m; ?>" <?php if ($month == $m) echo 'selected="selected";' ?>><?php echo $m; ?></option>
<?php } ?>
    </select> -
    <select name="day">
      <option value=""></option>
<?php $day = substr($post_date, 8, 2); for ($i = 1; $i <= 31; $i ++) { $m = sprintf("%02d", $i); ?>
      <option value="<?php echo $m; ?>" <?php if ($day == $m) echo 'selected="selected";' ?>><?php echo $m; ?></option>
<?php } ?>
    </select>&nbsp;
    <select name="hourse">
      <option value=""></option>
<?php $hourse = substr($post_time, 0, 2); for ($i = 0; $i <= 23; $i ++) { $m = sprintf("%02d", $i); ?>
      <option value="<?php echo $m; ?>" <?php if ($hourse == $m) echo 'selected="selected";' ?>><?php echo $m; ?></option>
<?php } ?>
    </select> :
    <select name="minute">
      <option value=""></option>
<?php $minute = substr($post_time, 3, 2); for ($i = 0; $i <= 59; $i ++) { $m = sprintf("%02d", $i); ?>
      <option value="<?php echo $m; ?>" <?php if ($minute == $m) echo 'selected="selected";' ?>><?php echo $m; ?></option>
<?php } ?>
    </select> :
    <select name="second">
      <option value=""></option>
<?php $second = substr($post_time, 6, 2); for ($i = 0; $i <= 59; $i ++) { $m = sprintf("%02d", $i); ?>
      <option value="<?php echo $m; ?>" <?php if ($second == $m) echo 'selected="selected";' ?>><?php echo $m; ?></option>
<?php } ?>
    </select> (留空为当前时间)
    </div>
    评论：
    <select name="can_comment" style="margin-right:16px;">
      <option value="1" <?php if ($post_can_comment == '1') echo 'selected="selected";'; ?>>允许</option>
      <option value="0" <?php if ($post_can_comment == '0') echo 'selected="selected";'; ?>>禁用</option>
    </select>
    状态：
    <select name="state" style="width:100px;">
      <option value="publish" <?php if ($post_state == 'publish') echo 'selected="selected"'; ?>>发布</option>
      <option value="draft" <?php if ($post_state == 'draft') echo 'selected="selected"'; ?>>草稿</option>
    </select>
    <div style="clear:both;"></div>
  </div>
  <div style="text-align:right">
    <input type="hidden" name="id" value="<?php echo $post_id; ?>"/>
    <input type="submit" name="save" value="保存" style="padding:6px 20px;"/>
  </div>
</form>
