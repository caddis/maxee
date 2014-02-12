<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Maxee Module Front End File
 *
 * @package Maxee
 * @author  Caddis
 * @link    http://www.caddis.co
 */

class Maxee {

	protected $site_id;
	protected $settings;

	public function __construct()
	{
		ee()->load->model('maxee_settings_model', 'maxee_settings');

		$this->site_id = ee()->config->item('site_id');
		$this->settings = ee()->maxee_settings->get_settings($this->site_id);
	}

	function purge()
	{
		
	}
}