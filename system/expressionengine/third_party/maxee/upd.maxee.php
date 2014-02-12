<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Maxee Module Install/Update File
 *
 * @package Maxee
 * @author  Caddis
 * @link    http://www.caddis.co
 */

include(PATH_THIRD . 'maxee/config.php');

class Maxee_upd {

	public $name = MAXEE_NAME;
	public $version = MAXEE_VER;

	/**
	 * Install
	 *
	 * @return boolean true
	 */
	public function install()
	{
		ee()->load->dbforge();

		// Module record

		ee()->db->insert('modules', array(
			'module_name' => $this->name,
			'module_version' => $this->version,
			'has_cp_backend' => 'y',
			'has_publish_fields' => 'n'
		));

		// Settings table

		ee()->dbforge->add_field(array(
			'id' => array('type' => 'INT', 'unsigned' => true, 'auto_increment' => true),
			'site_id' => array('type' => 'TINYINT', 'unsigned' => true, 'default' => 1),
			'alias' => array('type' => 'VARCHAR', 'constraint' => 32, 'default' => ''),
			'consumer_key' => array('type' => 'VARCHAR', 'constraint' => 64, 'default' => ''),
			'consumer_secret' => array('type' => 'VARCHAR', 'constraint' => 64, 'default' => '')
		));

		ee()->dbforge->add_key('id', true);
		ee()->dbforge->add_key('site_id');

		ee()->dbforge->create_table('maxee_settings', true);

		return true;
	}

	/**
	 * Uninstall
	 *
	 * @return boolean true
	 */
	public function uninstall()
	{
		ee()->load->dbforge();
		
		$query = ee()->db->get_where('modules', array(
			'module_name' => $this->name
		));

		// Remove module permissions

		if ($query->row('module_id'))
		{
			ee()->db->delete('module_member_groups', array(
				'module_id' => $query->row('module_id')
			));
		}

		// Remove module

		ee()->db->delete('modules', array(
			'module_name' => $this->name
		));

		// Remove settings table

		ee()->dbforge->drop_table('maxee_settings');

		return true;
	}

	/**
	 * Update
	 *
	 * @return boolean
	 */
	public function update($current = '')
	{
		// Version comparison

		if ($current == $this->version)
		{
			return false;
		}

		return true;
	}
}