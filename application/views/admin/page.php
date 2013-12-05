<?php
$page_ids = array_keys($pages);
$page_count = count($page_ids);
$date_array = array();
for ($i = $page_count - 1; $i >= 0; $i --) {
  $page_id = $page_ids[$i];
  $page = $pages[$page_id];
  $date_array[] = substr($page['date'], 0, 7);
}
$date_array = array_unique($date_array);
if (isset($_GET['date']))
  $filter_date = $_GET['date'];
else
  $filter_date = '';
  $pages2 = array();
for ($i = 0; $i < $page_count; $i ++) {
  $page_id = $page_ids[$i];
  $page = $pages[$page_id];
  if ($filter_date != '' && strpos($page['date'], $filter_date) !== 0)
    continue;
  $pages2[$page_id] = $page;
}
$pages = $pages2;
$page_ids = array_keys($pages);
$page_count = count($page_ids);
$last_page = ceil($page_count / 10);
if (!$page_num)
  $page_num = 1;
else
  $page_num = 1;
if ($page_num > 1)
  $prev_page = $page_num - 1;
else
  $prev_page = 1;
if ($page_num < $last_page)
  $next_page = $page_num + 1;
else
  $next_page = $last_page;
if ($page_num < $last_page)
  $next_page = $page_num + 1;
else
  $next_page = $last_page;
if ($page_num < 0)
  $page_num = 1;
else if ($page_num > $last_page)
  $page_num = $last_page;

?>
<script type="text/javascript">
function check_all(name)
{
  var el = document.getElementsByTagName('input');
  var len = el.length;
  for(var i=0; i<len; i++) {
    if((el[i].type=="checkbox") && (el[i].name==name)) {
      el[i].checked = true;
    }
  }
}
function clear_all(name)
{
  var el = document.getElementsByTagName('input');
  var len = el.length;
  
  for(var i=0; i<len; i++) {
    if((el[i].type=="checkbox") && (el[i].name==name)) {
    el[i].checked = false;
    }
  }
}
function apply_all(opid, name)
{
  var el = document.getElementsByTagName('input');
  var len = el.length;
  var ids = '';
  
  for(var i=0; i<len; i++) {
    if((el[i].type=="checkbox") &&
       (el[i].name==name) &&
       el[i].checked == true &&
       el[i].value != '') {
      ids += el[i].value + ',';
    }
  }
  var op = document.getElementById(opid);
  if (ids != '')
    location.href = '?state=<?php echo $state; ?>&apply=' + op.value + '&ids=' + ids;
}
function do_filter()
{
  var date = document.getElementById('date');
  location.href = '?state=<?php echo $state; ?>&date=' + date.value;
}
function goto_page(e)
{
  var evt = e || window.event;
  var eventSrc = evt.target||evt.srcElement;
  if ((e.keyCode || e.which) == 13) {
    location.href = '?state=<?php echo $state; ?>&date=<?php echo $filter_date;?>&page=' + eventSrc.value;
  }
}
</script>
<?php if ( $message  != '') { ?>
<div class="updated"><?php echo $message; ?></div>
<?php } ?>
<div class="admin_page_name">管理页面<a class="link_button" href="<?php echo site_url(admin_url('page_edit'));?>">创建页面</a></div>
<div class="post_mode_link">
<a href="?state=publish" class="link_button <?php if ($state == 'publish') echo 'current'; ?>">已发布</a>
<a href="?state=draft" class="link_button <?php if ($state == 'draft') echo 'current'; ?>">草稿箱</a>
<a href="?state=delete" class="link_button <?php if ($state == 'delete') echo 'current'; ?>">回收站</a>
</div>
<div class="table_list_tool">
  <span>
    <select id="op1">
      <option value="">批量操作</option>
      <?php if ($state == 'delete') { ?>
      <option value="revert">还原</option>
      <option value="delete">删除</option>
      <?php } else { ?>
      <option value="delete">回收</option>
      <?php } ?>
    </select>
    <input type="button" value="应用" onclick="apply_all('op1','ids');"/>
  </span>
  <span>
    <select id="date">
      <option value="">显示所有日期</option>
      <?php foreach ($date_array as $date_name) { ?>
      <option value="<?php echo $date_name; ?>" <?php if ($filter_date == $date_name) echo ' selected="selected"'; ?>><?php echo $date_name; ?></option>
      <?php } ?>
    </select>
    <input type="submit" value="筛选" onclick="do_filter();"/>
  </span>
  <span class="pager">
    共 <?php echo $page_count; ?> 项&nbsp;&nbsp;
    <a class="link_button" href="?state=<?php echo $state; ?>&date=<?php echo $filter_date;?>">&laquo;</a>
    <a class="link_button" href="?state=<?php echo $state; ?>&date=<?php echo $filter_date;?>&page=<?php echo $prev_page; ?>">&lsaquo;</a>
    第 <input type="text" value="<?php echo $page_num; ?>" id="page_input_1"/> / <?php echo $last_page; ?> 页
    <a class="link_button" href="?state=<?php echo $state; ?>&date=<?php echo $filter_date;?>&page=<?php echo $next_page; ?>">&rsaquo;</a>
    <a class="link_button" href="?state=<?php echo $state; ?>&date=<?php echo $filter_date;?>&page=<?php echo $last_page; ?>">&raquo;</a>
  </span>
  <script type="text/javascript">
  document.getElementById('page_input_1').onkeydown = goto_page;
  </script>
</div>
<div class="table_list post_list">
<table colspan="0" rowspan="0" cellpadding="0" cellspacing="0" id="list">
  <thead>
    <tr>
    <td style="width:20px"><input type="checkbox" name="ids" onclick="if(this.checked==true) { check_all('ids'); } else { clear_all('ids'); }" value=""/></td>
    <td>标题</td><td style="width:25%">路径</td><td style="width:15%">日期</td>
    </tr>
  </thead>
  <tbody>
  <?php for ($i = 0; $i < $page_count; $i ++) { if ($i < ($page_num - 1) * 10 || $i >= ($page_num * 10)) continue; $page_id = $page_ids[$i]; $page = $pages[$page_id]; ?>
    <tr<?php if ($i % 2 == 0) echo ' class="alt"'; ?>>
      <td><input type="checkbox" name="ids" value="<?php echo $page_id; ?>"/></td>
      <td>
        <a class="row_name" href="<?php echo site_url(admin_url('page_edit'));?>?file=<?php echo $page['file']; ?>"><?php echo htmlspecialchars($page['title']);?></a>
        <div class="row_tool">
         <?php if ($state != 'delete') { ?>
          <a class="link_button" href="<?php echo site_url(admin_url('page_edit'));?>?file=<?php echo $page['file']; ?>">编辑</a>
         <?php } ?>
          <?php if ($state == 'delete') { ?>
          <a class="link_button" href="?revert=<?php echo $page_id; ?>&state=<?php echo $state; ?>&date=<?php echo $filter_date;?>">还原</a>
          <a class="link_button" href="?delete=<?php echo $page_id; ?>&state=<?php echo $state; ?>&date=<?php echo $filter_date;?>">删除</a>
          <?php } else { ?>
          <a class="link_button" href="?delete=<?php echo $page_id; ?>&state=<?php echo $state; ?>&date=<?php echo $filter_date;?>">回收</a>
          <?php } ?>
          <a class="link_button" href="<?php echo site_url($page_id);?>" target="_blank">查看</a>
        </div>
      </td>
      <td><?php
        $paths = explode('/', $page_id);
        $paths_count = count($paths);
        for ($j = 0; $j < $paths_count - 1; $j ++) {
          echo '－';
        }
        echo $paths[$paths_count - 1]; ?></td>
      <td><?php echo htmlspecialchars($page['date']);?></td>
    </tr>
  <?php } ?>
  </tbody>
  <tfoot>
    <tr>
    <td><input type="checkbox" name="ids" onclick="if(this.checked==true) { check_all('ids'); } else { clear_all('ids'); }" value=""/></td><td>标题</td><td>路径</td><td>日期</td>
    </tr>
  </tfoot>
</table>
</div>
<div class="table_list_tool">
  <span>
    <select id="op2">
      <option>批量操作</option>
      <option value="delete">删除</option>
    </select>
    <input type="button" name="apply" value="应用" onclick="apply_all('op2','ids');"/>
  </span>
  <span class="pager">
    共 <?php echo $page_count; ?> 项&nbsp;&nbsp;
    <a class="link_button" href="?state=<?php echo $state; ?>&date=<?php echo $filter_date;?>">&laquo;</a>
    <a class="link_button" href="?state=<?php echo $state; ?>&date=<?php echo $filter_date;?>&page=<?php echo $prev_page; ?>">&lsaquo;</a>
    第 <input type="text" value="<?php echo $page_num; ?>" id="page_input_2"/> / <?php echo $last_page; ?> 页
    <a class="link_button" href="?state=<?php echo $state; ?>&date=<?php echo $filter_date;?>&page=<?php echo $next_page; ?>">&rsaquo;</a>
    <a class="link_button" href="?state=<?php echo $state; ?>&date=<?php echo $filter_date;?>&page=<?php echo $last_page; ?>">&raquo;</a>
  </span>
  <script type="text/javascript">
  document.getElementById('page_input_2').onkeydown = goto_page;
  </script>
</div>
