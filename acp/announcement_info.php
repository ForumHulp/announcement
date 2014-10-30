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

class announcement_info
{
	function module()
	{
		return array(
			'filename'	=> '\forumhulp\announcement\acp\announcement_module',
			'title'		=> 'ACP_ANNOUNCEMENT',
			'modes'		=> array(
				'settings'	=> array(
					'title' => 'ACP_ANNOUNCEMENT_SETTINGS',
					'auth' => 'ext_forumhulp/announcement && acl_a_board',
					'cat' => array('ACP_ANNOUNCEMENT')
				),
			),
		);
	}
}
