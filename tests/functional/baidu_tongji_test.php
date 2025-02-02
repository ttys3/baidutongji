<?php
/**
*
* Baidu Tongji extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace ttys3\baidutongji\tests\functional;

/**
* @group functional
*/
class baidu_tongji_test extends \phpbb_functional_test_case
{
	/** @var string */
	protected $sample_ga_code = 'UA-000000-00';

	/**
	* Define the extensions to be tested
	*
	* @return array vendor/name of extension(s) to test
	*/
	protected static function setup_extensions()
	{
		return array('ttys3/baidutongji');
	}

	/**
	* Test Baidu Tongji ACP page and save settings
	*/
	public function test_set_acp_settings()
	{
		$this->login();
		$this->admin_login();

		// Add language files
		$this->add_lang('acp/board');
		$this->add_lang_ext('ttys3/baidutongji', 'baidutongji_acp');

		$found = false;

		// Load ACP board settings page
		$crawler = self::request('GET', 'adm/index.php?i=acp_board&mode=settings&sid=' . $this->sid);

		// Test that GA settings field is found in the correct position (after WARNINGS_EXPIRE)
		$nodes = $crawler->filter('#acp_board > fieldset > dl > dt > label')->extract(array('_text'));
		foreach ($nodes as $key => $config_name)
		{
			if (strpos($config_name, $this->lang('WARNINGS_EXPIRE')) !== 0)
			{
				continue;
			}

			$found = true;

			$this->assertContainsLang('ACP_BAIDUTONGJI_ID', $nodes[$key + 1]);
		}

		// If GA settings not found where expected, test if they exist on page at all
		if (!$found)
		{
			$this->assertContainsLang('ACP_BAIDUTONGJI_ID', $crawler->text());
		}

		// Set GA form values
		$form = $crawler->selectButton($this->lang('SUBMIT'))->form();
		$values = $form->getValues();
		$values['config[baidutongji_id]'] = $this->sample_ga_code;
		$form->setValues($values);

		// Submit form and test success
		$crawler = self::submit($form);
		$this->assertContainsLang('CONFIG_UPDATED', $crawler->filter('.successbox')->text());
	}

	/**
	* Test Baidu Tongji code appears as expected
	*/
	public function test_baidu_tongji_code()
	{
		$crawler = self::request('GET', 'index.php');
		$this->assertContains($this->sample_ga_code, $crawler->filter('head > script')->eq(1)->text());
	}
}
