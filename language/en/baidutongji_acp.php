<?php
/**
*
* Baidu Tongji extension for the phpBB Forum Software package.
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
	'ACP_BAIDUTONGJI'				=> 'Baidu Tongji',
	'ACP_BAIDUTONGJI_ID'			=> 'Baidu Tongji ID',
	'ACP_BAIDUTONGJI_ID_EXPLAIN'	=> 'Enter your Baidu Tongji ID code, e.g.: <samp>UA-0000000-00</samp>.<br /><br />Baidu Tongji can track your registered users across multiple devices and sessions, for a more accurate user count in your reports. To enable this enhanced functionality User ID tracking must be configured in your Baidu Tongji account. <a href="https://support.google.com/analytics/answer/3123666">Click for more information <i class="icon fa-external-link fa-fw" aria-hidden="true"></i></a>.',
	'ACP_BAIDUTONGJI_ID_INVALID'	=> '“%s” is not a valid Baidu Tongji ID code.<br />It should be in the form “UA-0000000-00”.',
	'ACP_GA_ANONYMIZE_IP'				=> 'Turn on IP Anonymization',
	'ACP_GA_ANONYMIZE_IP_EXPLAIN'		=> 'Enable this option if you want the data collected by Baidu Tongji to be compliant with the EU‘s General Data Protection Regulation (GDPR). Note that enabling this option may slightly reduce the accuracy of geographic reporting.',
	'ACP_BAIDUTONGJI_TAG'			=> 'Baidu Tongji Script Tag',
	'ACP_BAIDUTONGJI_TAG_EXPLAIN'	=> 'Choose your preferred Baidu Tongji code snippet. Global site tag (gtag.js) is the current snippet recommended by Google. Baidu Tongji tag (analytics.js) is the legacy code snippet. <a href="https://developers.google.com/analytics/devguides/collection/gtagjs/migration">Click for more information <i class="icon fa-external-link fa-fw" aria-hidden="true"></i></a>.',
	'ACP_GA_ANALYTICS_TAG'				=> 'Baidu Tongji Tag (analytics.js)',
	'ACP_GA_GTAGS_TAG'					=> 'Global Site Tag (gtag.js)',
));
