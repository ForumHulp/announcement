<?php
/**
*
* Announcements extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace forumhulp\announcement\migrations\v10x;

/**
* Migration stage 3: Initial module
*/
class m3_initial_module extends \phpbb\db\migration\migration
{
	/**
	* Add or update data in the database
	*
	* @return array Array of table data
	* @access public
	*/
	public function update_data()
	{
		return array(
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_ANNOUNCEMENT')),
			array('module.add', array(
				'acp', 'ACP_ANNOUNCEMENT', array(
					'module_basename'	=> '\forumhulp\announcement\acp\announcement_module',
					'modes'				=> array('settings'),
				),
			)),
		);
	}
}
