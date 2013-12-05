<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MiniBlog
 *
 * @package		MiniBlog
 * @author		微笑的鱼
 * @license		http://www.apache.org/licenses/LICENSE-2.0
 * @link		http://blog.weixiaodeyu.com/miniblog
 * @since		0.1.2 - 2013.11.29
 */


// ------------------------------------------------------------------------


/**
 * MiniBlog Array Helpers
 *
 * @package		MiniBlog
 * @subpackage	Helpers
 * @category	Helpers
 * @author		微笑的鱼
 *
 */



// ------------------------------------------------------------------------

/**
 * 文章排序
 *
 * @access	public
 * @return	array
 */
if ( ! function_exists('post_sort'))
{
	function post_sort($a, $b)
	{
		$a_date = $a['date'];
		$b_date = $b['date'];
		if ($a_date != $b_date)
			return $a_date > $b_date ? -1 : 1;
		
		return $a['time'] > $b['time'] ? -1 : 1;
	}
}


