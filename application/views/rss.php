<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; echo "\n"; ?>
<rss version="2.0">
<channel>
  <title><?php $mb->site_name(); ?></title>
  <link><?php echo base_url(); ?></link>
  <description><![CDATA[<?php $mb->site_desc(); ?>]]></description>
  <language>zh_CN</language>
  <generator>by MiniBlog</generator>
<?php while ($mb->next_post()) { ?>
    <item>
      <title><?php $mb->the_title(); ?></title>
      <link><?php $mb->post_link(); ?></link>
      <pubDate><?php $mb->the_date(); ?> <?php $mb->the_time(); ?></pubDate>
      <description><![CDATA[<?php $mb->the_content();?>]]></description>
      <author><?php $mb->nick_name(); ?></author>
      <guid><?php $mb->post_link(); ?></guid>
      </item>
<?php } ?>
</channel>
</rss>