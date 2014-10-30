<?php
/**
*
* Announcements extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace forumhulp\announcement\acp;

class announcement_module
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\config\db_text */
	protected $config_text;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var ContainerInterface */
	protected $phpbb_container;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $php_ext;

	/** @var string */
	public $u_action;

	public function main($id, $mode)
	{
		global $config, $db, $request, $template, $user, $phpbb_root_path, $phpEx, $phpbb_container;

		$this->config = $config;
		$this->config_text = $phpbb_container->get('config_text');
		$this->db = $db;
		$this->log = $phpbb_container->get('log');
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $phpEx;

		// Add the announcements ACP lang file
		$this->user->add_lang_ext('forumhulp/announcement', 'announcement_acp');

		// Load a template from adm/style for our ACP page
		$this->tpl_name = 'announcement';

		// Set the page title for our ACP page
		$this->page_title = 'ACP_ANNOUNCEMENT_SETTINGS';

		// Define the name of the form for use as a form key
		$form_name = 'acp_announcement';
		add_form_key($form_name);

		// Set an empty error string
		$error = '';

		// Get announcement data from the config_text table in the database
		$data = $this->config_text->get_array(array(
			'announcement_text',
			'announcement_timestamp'
		));

		// If form is submitted
		if ($this->request->is_set_post('submit'))
		{
			// Test if form key is valid
			if (!check_form_key($form_name))
			{
				$error = $this->user->lang('FORM_INVALID');
			}

			// Get config options from the form
			$enable_announcements = $this->request->variable('announcement_enable', false);
			$allow_guests = $this->request->variable('announcement_guests', false);

			// Store the announcement text and settings if submitted with no errors
			if (empty($error) && $this->request->is_set_post('submit'))
			{
				// Store the config enable/disable and allow guests state
				$this->config->set('announcement_enable', $enable_announcements);
				$this->config->set('announcement_guests', $allow_guests);

				// Store the announcement to the config_table in the database
				$this->config_text->set_array(array(
					'announcement_text'			=> $this->request->variable('announcement_text', '', true),
					'announcement_timestamp'	=> time(),
				));

				// Set the announcements_status for all normal users
				// to 1 when an announcement is created, or 0 when announcement is empty
				$announcement_status = ($this->request->variable('announcement_text', '', true) != '') ? 1 : 0;
				$sql = 'UPDATE ' . USERS_TABLE . '
					SET announcement_status = ' . $announcement_status . '
					WHERE user_type <> ' . USER_IGNORE;
				$this->db->sql_query($sql);

				// Set the announcement status for guests if they are allowed
				// We do this separately for guests to make sure it is always set to
				// the correct value every time.
				$sql = 'UPDATE ' . USERS_TABLE . '
					SET announcement_status = ' . (($allow_guests && $announcement_status ) ? 1 : 0) . '
					WHERE user_id = ' . ANONYMOUS;
				$this->db->sql_query($sql);

				// Log the announcement update
				$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'ANNOUNCEMENT_UPDATED_LOG');

				// Output message to admin for the announcement update
				trigger_error($this->user->lang('ANNOUNCEMENT_UPDATED') . adm_back_link($this->u_action));
			}
		}

		// Output data to the template
		$this->template->assign_vars(array(
			'ERRORS'				=> $error,
			'ANNOUNCEMENT_ENABLED'	=> (isset($enable_announcement)) ? $enable_announcement : $this->config['announcement_enable'],
			'ANNOUNCEMENT_GUESTS'	=> (isset($allow_guests)) ? $allow_guests: $this->config['announcement_guests'],
			'ANNOUNCEMENT_TEXT'		=> $data['announcement_text'],
			'ANNOUNCEMENT_TIME'		=> ($data['announcement_timestamp']) ? $user->format_date($data['announcement_timestamp']) : '',
			'U_ACTION'				=> $this->u_action,
		));
	}
}
