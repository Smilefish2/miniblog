<title><?php
if ($mb->is_post() || $mb->is_page()):
	$mb->the_title();
elseif($mb->is_archive()):
	echo '日期和标签存档';
elseif($mb->is_tag()):
	echo '标签\''.$mb->get_name.'\'存档';
elseif($mb->is_date()):
	echo '月份\''.$mb->get_name.'\'存档';
elseif($mb->is_search()):
	echo '关键字\''.$mb->get_name.'\'搜索结果';
else://首页
	$mb->site_name();
	echo ' - ';
	$mb->site_desc();
endif;
//非首页情况下输出网站名称
if(!$mb->is_home()):
	echo ' - ';
	$mb->site_name();
endif;
?></title>