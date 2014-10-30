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
* Migration stage 1: Initial schema changes to the database
*/
class m1_initial_schema extends \phpbb\db\migration\migration
{
	/**
	* Add the announcement column to the users table.
	*
	* @return array Array of table schema
	* @access public
	*/
	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'users'	=> array(
					'announcement_status'	=> array('BOOL', 0),
				),
			),
		);
	}

	/**
	* Drop the announcement column from the users table.
	*
	* @return array Array of table schema
	* @access public
	*/
	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'users'	=> array(
					'announcement_status',
				),
			),
		);
	}
}
