<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Maxee Library
 *
 * @package	Maxee
 * @author	Caddis
 * @link	http://www.caddis.co
 */

require_once PATH_THIRD . 'maxee/vendor/autoload.php';

class Maxee_lib {

	protected $site_id;
	protected $settings;
	protected $api;

	public function __construct()
	{
		ee()->load->model('maxee_settings_model', 'maxee_settings');

		$this->site_id = ee()->config->item('site_id');
		$this->settings = ee()->maxee_settings->get_settings($this->site_id);

		$this->api = new NetDNA($this->settings->alias, $this->settings->consumer_key, $this->settings->consumer_secret);
	}
	
	public function get_settings()
	{
		if (! isset(ee()->session->cache['maxee']['settings']))
		{
			ee()->session->cache['maxee']['settings'] = ee()->maxee_settings->get_settings($this->site_id);
		}

		return ee()->session->cache['maxee']['settings'];
	}

	public function update_settings($data = array())
	{
		return ee()->maxee_settings->update_settings($this->site_id, $data);
	}

	public function get_zones()
	{
		return $this->_call_api('/zones.json');
	}

	public function get_stats($reportType = '')
	{
		return $this->_call_api('/reports/stats.json/' . $reportType);
	}

	public function get_zone_stats($zone, $reportType = '')
	{
		return $this->_call_api('/reports/' . $zone . '/stats.json' . $reportType);
	}

	public function purge_files($zone, $files)
	{
		$files = ($files != '') ? explode('|', $files) : false;

		$params = array();

		foreach ($files as $i => $file)
		{
			$params['file' . $i] = $file;
		}

		return $this->_call_api('/zones/pull.json/' . $zone . '/cache', 'delete', $params);
	}

	private function _call_api($endpoint, $method = 'get', $params = false)
	{
		$response = false;

		try
		{
			switch ($method)
			{
				case 'delete':
					$response = $this->api->delete($endpoint);
					break;
				default:
					$response = $this->api->get($endpoint);
			}

			$response = json_decode($response);

			if ($response->code != 200)
			{
				ee()->load->library('logger');

				ee()->logger->developer('There was a problem calling the ' . $endpoint . ' endpoint: ' . $response->error->message);

				$response = false;
			}
		}
		catch (Exception $e)
		{
			ee()->load->library('logger');

			ee()->logger->developer('There was an exception calling the ' . $endpoint . ' endpoint: ' . $e);
		}

		return $response;
	}
}