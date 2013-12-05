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
 * MiniBlog URL Helpers
 *
 * @package		MiniBlog
 * @subpackage	Helpers
 * @category	Helpers
 * @author		微笑的鱼
 *
 */



// ------------------------------------------------------------------------

/**
 * 组装管理后台的URL
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('admin_url'))
{
	function admin_url($url = '')
	{
		$CI =& get_instance();
		
		if ($url)
		{
			return $CI->config->slash_item('admin_url').$url;
			
		}
		
		return $CI->config->slash_item('admin_url');
	}
}

// ------------------------------------------------------------------------

/**
 * 文章、页面短网址（ID生成）
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('short_url'))
{
	function short_url($input) {
		$base32 = array (
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
			'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
			'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
			'y', 'z', '0', '1', '2', '3', '4', '5'
		);
		$hex = md5('prefix'.$input.'surfix'.time());
		$hexLen = strlen($hex);
		$subHexLen = $hexLen / 8;
		$output = array();
		for ($i = 0; $i < $subHexLen; $i++) {
			$subHex = substr ($hex, $i * 8, 8);
			$int = 0x3FFFFFFF & (1 * ('0x'.$subHex));
			$out = '';
			for ($j = 0; $j < 6; $j++) {
				$val = 0x0000001F & $int;
				$out .= $base32[$val];
				$int = $int >> 5;
			}
			$output[] = $out;
		}
		return $output;
	}
}

