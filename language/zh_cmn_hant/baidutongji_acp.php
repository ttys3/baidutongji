<?php
/**
*
* Baidu Tongji extension for the phpBB Forum Software package.
* Translate by: rexkyo (http://phpbb-tw.net/)
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'ACP_BAIDUTONGJI'				=> 'Baidu 统计',
	'ACP_BAIDUTONGJI_ID'			=> 'Baidu 统计 ID',
	'ACP_BAIDUTONGJI_ID_EXPLAIN'	=> '輸入你的 Baidu Tongji（统计） ID code, 例如: <samp>98a8cd7fe51fbd4b4043e92c5ed295ea</samp>. <a href="https://tongji.baidu.com/" target="_blank" rel="noreferrer"> 單擊獲取更多信息。 <i class="icon fa-external-link fa-fw" aria-hidden="true"></i></a>',
	'ACP_BAIDUTONGJI_ID_INVALID'	=> '“%s” 不是有效的 Baidu Tongji（分析） ID code.<br />它的格式應該會是32位hex字符串.',
));
