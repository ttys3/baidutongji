<?php
/**
*
* Baidu Tongji extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbb\baidutongji\tests\event;

require_once __DIR__ . '/../../../../../includes/functions_acp.php';

class listener_test extends \phpbb_test_case
{
	/** @var \phpbb\baidutongji\event\listener */
	protected $listener;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \PHPUnit_Framework_MockObject_MockObject|\phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/**
	* Setup test environment
	*/
	public function setUp()
	{
		parent::setUp();

		global $phpbb_root_path, $phpEx;

		// Load/Mock classes required by the event listener class
		$this->config = new \phpbb\config\config(array(
			'baidutongji_id' => 'UA-000000-01',
			'ga_anonymize_ip' => 0,
		));
		$this->template = $this->getMockBuilder('\phpbb\template\template')
			->getMock();
		$lang_loader = new \phpbb\language\language_file_loader($phpbb_root_path, $phpEx);
		$lang = new \phpbb\language\language($lang_loader);
		$this->user = new \phpbb\user($lang, '\phpbb\datetime');
		$this->user->data['user_id'] = 2;
		$this->user->data['is_registered'] = true;
	}

	/**
	* Create our event listener
	*/
	protected function set_listener()
	{
		$this->listener = new \phpbb\baidutongji\event\listener(
			$this->config,
			$this->template,
			$this->user
		);
	}

	/**
	* Test the event listener is constructed correctly
	*/
	public function test_construct()
	{
		$this->set_listener();
		$this->assertInstanceOf('\Symfony\Component\EventDispatcher\EventSubscriberInterface', $this->listener);
	}

	/**
	* Test the event listener is subscribing events
	*/
	public function test_getSubscribedEvents()
	{
		$this->assertEquals(array(
			'core.acp_board_config_edit_add',
			'core.page_header',
			'core.validate_config_variable',
		), array_keys(\phpbb\baidutongji\event\listener::getSubscribedEvents()));
	}

	/**
	* Test the load_baidu_tongji event
	*/
	public function test_load_baidu_tongji()
	{
		$this->set_listener();

		$this->template->expects($this->once())
			->method('assign_vars')
			->with(array(
				'BAIDUTONGJI_ID'		=> $this->config['baidutongji_id'],
				'BAIDUTONGJI_TAG'		=> $this->config['baidutongji_tag'],
				'BAIDUTONGJI_USER_ID'	=> $this->user->data['user_id'],
				'S_ANONYMIZE_IP'			=> $this->config['ga_anonymize_ip'],
			));

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.page_header', array($this->listener, 'load_baidu_tongji'));
		$dispatcher->dispatch('core.page_header');
	}

	/**
	* Data set for test_add_baidutongji_configs
	*
	* @return array Array of test data
	*/
	public function add_baidutongji_configs_data()
	{
		return array(
			array( // expected config and mode
				'settings',
				array('vars' => array('warnings_expire_days' => array())),
				array('warnings_expire_days', 'legend_baidutongji', 'baidutongji_id', 'ga_anonymize_ip', 'baidutongji_tag'),
			),
			array( // unexpected mode
				'foobar',
				array('vars' => array('warnings_expire_days' => array())),
				array('warnings_expire_days'),
			),
			array( // unexpected config
				'settings',
				array('vars' => array('foobar' => array())),
				array('foobar'),
			),
			array( // unexpected config and mode
				'foobar',
				array('vars' => array('foobar' => array())),
				array('foobar'),
			),
		);
	}

	/**
	* Test the add_baidutongji_configs event
	*
	* @dataProvider add_baidutongji_configs_data
	*/
	public function test_add_baidutongji_configs($mode, $display_vars, $expected_keys)
	{
		$this->set_listener();

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.acp_board_config_edit_add', array($this->listener, 'add_baidutongji_configs'));

		$event_data = array('display_vars', 'mode');
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch('core.acp_board_config_edit_add', $event);

		$event_data_after = $event->get_data_filtered($event_data);
		foreach ($event_data as $expected)
		{
			$this->assertArrayHasKey($expected, $event_data_after);
		}
		extract($event_data_after);

		$keys = array_keys($display_vars['vars']);

		$this->assertEquals($expected_keys, $keys);
	}

	/**
	* Data set for test_validate_baidutongji_id
	*
	* @return array Array of test data
	*/
	public function validate_baidutongji_id_data()
	{
		return array(
			array(
				// valid code, no error
				array('baidutongji_id' => 'UA-0000-00'),
				array(),
			),
			array(
				// no code, no error
				array('baidutongji_id' => ''),
				array(),
			),
			array(
				// invalid code, error
				array('baidutongji_id' => 'UA-00-00'),
				array('ACP_BAIDUTONGJI_ID_INVALID'),
			),
			array(
				// invalid code, error
				array('baidutongji_id' => 'UA-00000-00000'),
				array('ACP_BAIDUTONGJI_ID_INVALID'),
			),
			array(
				// invalid code, error
				array('baidutongji_id' => 'AU-0000-00'),
				array('ACP_BAIDUTONGJI_ID_INVALID'),
			),
			array(
				// invalid code, error
				array('baidutongji_id' => 'foo-bar-foo'),
				array('ACP_BAIDUTONGJI_ID_INVALID'),
			),
			array(
				// no baidutongji_id, no error
				array('foo' => 'bar'),
				array(),
			),
		);
	}

	/**
	* Test the validate_baidutongji_id event
	*
	* @dataProvider validate_baidutongji_id_data
	*/
	public function test_validate_baidutongji_id($cfg_array, $expected_error)
	{
		$this->set_listener();

		$config_name = key($cfg_array);
		$config_definition = array('validate' => 'baidutongji_id');
		$error = array();

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.validate_config_variable', array($this->listener, 'validate_baidutongji_id'));

		$event_data = array('cfg_array', 'config_name', 'config_definition', 'error');
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch('core.validate_config_variable', $event);

		$event_data_after = $event->get_data_filtered($event_data);
		foreach ($event_data as $expected)
		{
			$this->assertArrayHasKey($expected, $event_data_after);
		}
		extract($event_data_after);

		$this->assertEquals($expected_error, $error);
	}
}
