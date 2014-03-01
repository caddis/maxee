<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Maxee Module Control Panel
 *
 * @package Maxee
 * @author  Caddis
 * @link    http://www.caddis.co
 */

class Maxee_mcp {
	
	public $page_limit = 20;

	private $base_url;
	private $root_url;
	private $method_url;
	private $post_url;

	public function __construct()
	{
		ee()->load->library('javascript');
		ee()->load->library('maxee_lib');

		ee()->load->model('maxee_settings_model', 'maxee_settings');

		$this->base_url = 'C=addons_modules' . AMP . 'M=show_module_cp' . AMP . 'module=maxee';
		$this->root_url = BASE . AMP . $this->base_url;
		$this->method_url = $this->root_url . AMP . 'method=';
		$this->post_url = $this->base_url . AMP . 'method=';

		ee()->cp->set_right_nav(array(
			'Stats' => $this->root_url,
			'Manage Cache' => $this->method_url . 'cache',
			'Settings' => $this->method_url . 'settings'
		));
	}

	public function index()
	{
		ee()->view->cp_page_title = 'Stats';

		$this->view_data = array();

		// Zone stats

		$stats = array();

		$result = ee()->maxee_lib->get_zones();

		if ($result)
		{
			$view_stats = array();

			$zones = $result->data->zones;

			foreach ($zones as $zone)
			{
				$zone_stats = ee()->maxee_lib->get_zone_stats($zone->id);

				if ($zone_stats !== false)
				{
					$view_stats[$zone->name] = array(
						'cache_hits' => number_format($zone_stats->data->stats->cache_hit),
						'hits' => number_format($zone_stats->data->stats->cache_hit),
						'noncache_hits' => number_format($zone_stats->data->stats->noncache_hit),
						'size' => $this->_convert_size($zone_stats->data->stats->size)
					);
				}
			}

			// View data

			$this->view_data = array(
				'view_stats' => $view_stats
			);
		}
		
		return ee()->load->view('maxee_index', $this->view_data, true);
	}

	public function cache()
	{
		ee()->view->cp_page_title = 'Manage Cache';

		$this->view_data = array();

		// Zones

		$result = ee()->maxee_lib->get_zones();

		if ($result)
		{
			$zones = $result->data->zones;

			// View data

			$this->view_data = array(
				'post_url' => $this->post_url . 'purge_files',
				'zones' => $zones
			);
		}
		
		return ee()->load->view('maxee_cache', $this->view_data, true);
	}

	public function purge_files()
	{
		$zone = $_POST['zone'];
		$files = $_POST['files'];

		$response = ee()->maxee_lib->purge_files($zone, $files);

		if ($response !== false and $response->code == 200)
		{
			ee()->session->set_flashdata('message_success', 'Files successfully purged.');

			ee()->functions->redirect($this->root_url);
		}

		ee()->session->set_flashdata('message_failure', 'Unable to purge selected files.');

		ee()->functions->redirect($this->method_url . 'cache');
	}

	public function settings()
	{
		ee()->view->cp_page_title = 'Settings';

		$site_id = ee()->config->item('site_id');

		$this->view_data = array(
			'post_url' => $this->post_url . 'update_settings',
			'settings' => ee()->maxee_settings->get_settings($site_id)
		);

		return ee()->load->view('maxee_settings', $this->view_data, true);
	}

	public function update_settings()
	{
		$data = array();

		foreach ($_POST as $key => $value)
		{
			$data[$key] = ee()->input->post($key);
		}
		
		if (ee()->maxee_lib->update_settings($data))
		{
			ee()->session->set_flashdata('message_success', 'Maxee settings successfully updated.');

			ee()->functions->redirect($this->root_url);
		}

		ee()->session->set_flashdata('message_failure', 'Unable to update Maxee settings.');

		ee()->functions->redirect($this->method_url . 'settings');
	}

	private function _convert_size($size, $unit = '')
	{
		if ((! $unit and $size >= 1<<30) or $unit == 'GB')
		{
			return number_format($size / (1<<30), 2).'GB';
		}

		if ((! $unit and $size >= 1<<20) or $unit == 'MB')
		{
			return number_format($size / (1<<20), 2).'MB';
		}

		if ((! $unit and $size >= 1<<10) or $unit == 'KB')
		{
			return number_format($size / (1<<10), 2) . 'KB';
		}

		return number_format($size) . ' bytes';
	}
}