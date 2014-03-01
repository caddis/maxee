<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Maxee Settings Model
 *
 * @package Maxee
 * @author  Caddis
 * @link    http://www.caddis.co
 */

class Maxee_settings_model extends CI_Model {

	private $table = 'maxee_settings';

	public $defaults = array(
		'site_id' => '1',
		'alias' => '',
		'consumer_key' => '',
		'consumer_secret' => ''
	);

	public function __construct()
	{
		parent::__construct();
	}

	public function get_settings($site_id)
	{	
		$settings = new stdClass();

		$results = $this->db->select('site_id, alias, consumer_key, consumer_secret')
			->from($this->table)
			->where('site_id', $site_id)
			->get();

		if ($results->num_rows() > 0)
		{
			$settings = $results->row();
		}

		foreach ($this->defaults as $key => $value)
		{			
			if (! property_exists($settings, $key))
			{
				$settings->$key = $value;
			}
		}

		return $settings;
	}

	public function settings_exists($site_id)
	{
		$this->db->select('id')
			->from($this->table)
			->where('site_id', $site_id);

		return ($this->db->count_all_results() > 0) ? true : false;
	}

	public function update_settings($site_id, $data = array())
	{
		$settings = array(
			'site_id' => $site_id,
			'alias' => $data['alias'],
			'consumer_key' => $data['consumer_key'],
			'consumer_secret' => $data['consumer_secret']
		);

		if (! $this->settings_exists($site_id))
		{
			return $this->db->insert($this->table, $settings);
		}

		return $this->db->update($this->table, $settings, array('site_id' => $settings['site_id']));
	}
}