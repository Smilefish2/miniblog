项目介绍
-----------

MiniBlog是一个超轻量级、微型、开源的博客、轻博客程序，核心思想继承自MiniCMS，MiniCMS是一个针对个人网站设计的微型内容管理系统，详细介绍可查看Github项目地址：https://github.com/bg5sbk/MiniCMS/，功能和模板上继承自BlogMi，实现了BlogMi的所有功能并有自己的一些扩展，BlogMi项目地址：http://haow.in/blogmi/，实际上，MiniBlog是一个用PHP框架CodeIgniter重写版的BlogMi，相比BlogMi，增加了以下功能：

1.实现了URL伪静态化，URL后辍可自由定制，增强SEO。
2.增加了搜索功能，实现标题和内容双重搜索。
3.实现管理后台地址自定义功能，由默认的'admin'可换成任意名称，增加安全性。
4.实现自动获取站点URL功能，可以安装在站点任意目录，不需要在后台配置。
5.实现了轻博客功能，可以不输入文章标题和标签。
6.利用CodeIgniter增强了站点的整体安全性，同时集成了所有CodeIgniter支持的特性。
7.使用CodeIgniter缓存特性，可支持APC、Memcached等缓存，提升MiniBlog的运行速度。

演示地址：http://www.weixiaodeyu.com/miniblog/

安装使用
-----------

将本程序解压上传到你的网站任意目录,如果安装的不是网站根目录，需要修改.htaccess文件中 RewriteRule ^(.*)$ /index.php/$1 [L] 为 RewriteRule ^(.*)$ /程序目录名如(miniblog)/index.php/$1 [L]
进入程序管理后台修改密码和配置其它选项，后台地址：http://网站域名/程序所在目录/admin/，默认帐号：admin，默认密码：admin。
OK,开始使用吧

MiniBlog特性
------------------

1.管理后台URL自定义，方法是：打开application/config/config.php，修改$config['admin_url']='admin';中'admin'为你想要的目录名称，注意只能是英文哈,中文偶没有测试过~

2.URL后辍URL自定义，方法是：打开application/config/config.php，修改$config['url_suffix'] = '.html';中'.html'为你想要的页面后辍，注意不能是'.exe'哈,搜索引擎百分百不收录的~

3.理论上支持BlogMi所有模板，因为实现方法从函数变成类，所以除去本身自带的模板，其它的BlogMi模板必须修改代码才可以在MiniBlog中使用，方法是搜索模板文件index.php中的mc_，替换成$mb->即可。

4.实现多模板切换功能，需要在application/config/views下建立模板文件，在application/config/views/admin/setting.php中的模板选择下拉框中增加模板选项，即可一键切换前台主题

MiniBlog升级指导
---------------------------

更新miniblog最安全的方法是备份老版本整站文件，在新版上传到网站完成后，从老版网站文件中覆盖上传以下文件：application/config/miniblog.php配置文件、application/data文件夹、/static/uploads文件夹。

或按以下步骤进行更新:

1.备份application/config/miniblog.php配置文件
2.备份application/data文件夹
3.备份/static/uploads文件夹
4.删除MiniBlog所有文件或所在目录，上传新版本的MiniBlog到网站目录
5.覆盖上传application/config/miniblog.php配置文件
6.覆盖上传application/data文件夹
7.覆盖上传/static/uploads文件夹
8.删除application/cache下的所有.cache文件，刷新网站首页，OK

MiniBlog下载和更新日志
---------------------------------

最新更新日志请前往：http://blog.weixiaodeyu.com/miniblog
