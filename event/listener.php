<?php
/**
*
* Announcements extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace forumhulp\announcement\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\config\db_text */
	protected $config_text;

	/** @var \phpbb\controller\helper */
	protected $controller_helper;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/**
	* Constructor
	*
	* @param \forumhulp\config\config                 $config             Config object
	* @param \forumhulp\config\db_text                $config_text        DB text object
	* @param \forumhulp\controller\helper             $controller_helper  Controller helper object
	* @param \forumhulp\request\request               $request            Request object
	* @param \forumhulp\template\template             $template           Template object
	* @param \forumhulp\user                          $user               User object
	* @param \forumhulp\event\dispatcher_interface    $phpbb_dispatcher   Event dispatcher
	* @return \forumhulp\announcement\event\listener
	* @access public
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\config\db_text $config_text, \phpbb\controller\helper $controller_helper, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->config = $config;
		$this->config_text = $config_text;
		$this->controller_helper = $controller_helper;
		$this->request = $request;
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
	static public function getSubscribedEvents()
	{
		return array(
			'core.page_header_after'	=> 'display_announcements',
		);
	}

	/**
	* Display announcements
	*
	* @return null
	* @access public
	*/
	public function display_announcements()
	{
		// Get announcement data from the config_text object
		$announcement_data = $this->config_text->get_array(array(
			'announcement_text',
			'announcement_timestamp',
		));

		// Get announcement cookie if one exists
		$cookie = $this->request->variable($this->config['cookie_name'] . '_aid', '', true, \phpbb\request\request_interface::COOKIE);

		// Do not continue if announcement has been disabled or dismissed
		if (!$this->config['announcement_enable'] || !$this->user->data['announcement_status'] || $cookie == $announcement_data['announcement_timestamp'])
		{
			return;
		}

		// Add announcements language file
		$this->user->add_lang_ext('forumhulp/announcement', 'announcement');

		// Output announcement to the template
		$this->template->assign_vars(array(
			'S_ANNOUNCEMENT'		=> true,
			'ANNOUNCEMENT'			=> htmlspecialchars_decode($announcement_data['announcement_text'], ENT_COMPAT),
			'U_ANNOUNCEMENT_CLOSE'	=> $this->controller_helper->route('forumhulp_announcement_controller', array(
				'hash' => generate_link_hash('close_announcement')
			)),
		));
	}
}
