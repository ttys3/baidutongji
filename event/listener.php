<?php
/**
*
* Baidu Tongji extension for the phpBB Forum Software package.
*
* @copyright (c) 2019 荒野無燈 <https://ttys3.net>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace ttys3\baidutongji\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/**
	* Constructor
	*
	* @param \phpbb\config\config        $config             Config object
	* @param \phpbb\template\template    $template           Template object
	* @param \phpbb\user                 $user               User object
	* @access public
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	public static function getSubscribedEvents()
	{
		return array(
			'core.acp_board_config_edit_add'	=> 'add_baidutongji_configs',
			'core.page_header'					=> 'load_baidu_tongji',
			'core.validate_config_variable'		=> 'validate_baidutongji_id',
		);
	}

	/**
	* Load Baidu Tongji js code
	*
	* @return void
	* @access public
	*/
	public function load_baidu_tongji()
	{
		$this->template->assign_vars(array(
			'BAIDUTONGJI_ID'		=> $this->config['baidutongji_id'],
		));
	}

	/**
	* Add config vars to ACP Board Settings
	*
	* @param \phpbb\event\data $event The event object
	* @return void
	* @access public
	*/
	public function add_baidutongji_configs($event)
	{
		// Add a config to the settings mode, after warnings_expire_days
		if ($event['mode'] === 'settings' && isset($event['display_vars']['vars']['board_timezone']))
		{
			// Load language file
			$this->user->add_lang_ext('ttys3/baidutongji', 'baidutongji_acp');

			// Store display_vars event in a local variable
			$display_vars = $event['display_vars'];

			// Define the new config vars
			$ga_config_vars = array(
				'baidutongji_id' => array(
					'lang'		=> 'ACP_BAIDUTONGJI_ID',
					'validate'	=> 'baidutongji_id',
					'type'		=> 'text:40:20',
					'explain'	=> true,
				),
			);

			// Add the new config vars after warnings_expire_days in the display_vars config array
			$insert_after = array('after' => 'board_timezone');
			$display_vars['vars'] = phpbb_insert_config_array($display_vars['vars'], $ga_config_vars, $insert_after);

			// Update the display_vars event with the new array
			$event['display_vars'] = $display_vars;
		}
	}

	/**
	* Validate the Baidu Tongji ID
	*
	* @param \phpbb\event\data $event The event object
	* @return void
	* @access public
	*/
	public function validate_baidutongji_id($event)
	{
		$input = isset($event['cfg_array']['baidutongji_id']) ? $event['cfg_array']['baidutongji_id'] : '';

		// Check if the validate test is for baidu_tongji
		if ($input !== '' && $event['config_definition']['validate'] === 'baidutongji_id')
		{
			// Store the error and input event data
			$error = $event['error'];

			// Add error message if the input is not a valid Baidu Tongji ID
			if (!preg_match('/^[a-z0-9]{32}$/', $input))
			{
				$error[] = $this->user->lang('ACP_BAIDUTONGJI_ID_INVALID', $input);
			}

			// Update error event data
			$event['error'] = $error;
		}
	}
}
