<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "welcome";
$route['404_override'] = '';

/*
 * 管理后台路由
 */
$route[config_item('admin_url')] = 'admin';
$route[config_item('admin_url').'(/.*)?'] = 'admin$1';

///*
// * 文章路由(如需加前缀如post)
// *
// */
//
//$route['post/(:any)'] = 'welcome/index/$1';

/*
 * 归档路由
 */
$route['archive'] = 'welcome/archive/$1';

/*
 * 标签路由
 */
$route['tag/(:any)'] = 'welcome/tag/$1';

/*
 * 归档路由
 */
$route['date/(:any)'] = 'welcome/date/$1';

/*
 * 搜索路由
 */
$route['search/(:any)'] = 'welcome/search/$1';

/*
 * rss路由
 */
$route['rss'] = 'welcome/rss';

/*
 * test路由,测试使用
 */
$route['test'] = 'test';
$route['test/(:any)'] = 'test/$1';

/*
 * 默认路由,以上路由不匹配的情况下使用此路由匹配，包括文章和页面读取
 */
$route['(.*)'] = $route['default_controller'].'/index/$1';


/* End of file routes.php */
/* Location: ./application/config/routes.php */