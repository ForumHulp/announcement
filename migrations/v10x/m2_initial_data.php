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
* Migration stage 2: Initial data changes to the database
*/
class m2_initial_data extends \phpbb\db\migration\migration
{
	/**
	* Add announcement data to the database.
	*
	* @return array Array of table data
	* @access public
	*/
	public function update_data()
	{
		return array(
			// Add our config table settings
			array('config.add', array('announcement_enable', 0)),
			array('config.add', array('announcement_guests', 0)),

			// Add our config_text table settings
			array('config_text.add', array('announcement_text', '')),
			array('config_text.add', array('announcement_timestamp', '')),
		);
	}
}
