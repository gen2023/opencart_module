<?php
class ControllerExtensionModuleSmartsearch extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/smartsearch');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_smartsearch', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/smartsearch', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/smartsearch', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_smartsearch_status'])) {
			$data['module_smartsearch_status'] = $this->request->post['module_smartsearch_status'];
		} else {
			$data['module_smartsearch_status'] = $this->config->get('module_smartsearch_status');
		}

		if (isset($this->request->post['module_smartsearch_field'])) {
			$data['module_smartsearch_field'] = $this->request->post['module_smartsearch_field'];
		} else {
			$data['module_smartsearch_field'] = $this->config->get('module_smartsearch_field');
		}

		if (isset($this->request->post['module_smartsearch_image'])) {
			$data['module_smartsearch_image'] = $this->request->post['module_smartsearch_image'];
		} else {
			$data['module_smartsearch_image'] = $this->config->get('module_smartsearch_image');
		}

		if (isset($this->request->post['module_smartsearch_image_width'])) {
			$data['module_smartsearch_image_width'] = $this->request->post['module_smartsearch_image_width'];
		} else {
			$data['module_smartsearch_image_width'] = $this->config->get('module_smartsearch_image_width');
		}

		if (isset($this->request->post['module_smartsearch_image_height'])) {
			$data['module_smartsearch_image_height'] = $this->request->post['module_smartsearch_image_height'];
		} else {
			$data['module_smartsearch_image_height'] = $this->config->get('module_smartsearch_image_height');
		}

		if (isset($this->request->post['module_smartsearch_model'])) {
			$data['module_smartsearch_model'] = $this->request->post['module_smartsearch_model'];
		} else {
			$data['module_smartsearch_model'] = $this->config->get('module_smartsearch_model');
		}

		if (isset($this->request->post['module_smartsearch_price'])) {
			$data['module_smartsearch_price'] = $this->request->post['module_smartsearch_price'];
		} else {
			$data['module_smartsearch_price'] = $this->config->get('module_smartsearch_price');
		}

		if (isset($this->request->post['module_smartsearch_oldprice'])) {
			$data['module_smartsearch_oldprice'] = $this->request->post['module_smartsearch_oldprice'];
		} else {
			$data['module_smartsearch_oldprice'] = $this->config->get('module_smartsearch_oldprice');
		}

		if (isset($this->request->post['module_smartsearch_button_all'])) {
			$data['module_smartsearch_button_all'] = $this->request->post['module_smartsearch_button_all'];
		} else {
			$data['module_smartsearch_button_all'] = $this->config->get('module_smartsearch_button_all');
		}

		if (isset($this->request->post['module_smartsearch_limit'])) {
			$data['module_smartsearch_limit'] = $this->request->post['module_smartsearch_limit'];
		} else {
			$data['module_smartsearch_limit'] = $this->config->get('module_smartsearch_limit');
		}

		if (isset($this->request->post['module_smartsearch_search_engine'])) {
			$data['module_smartsearch_search_engine'] = $this->request->post['module_smartsearch_search_engine'];
		} else {
			$data['module_smartsearch_search_engine'] = $this->config->get('module_smartsearch_search_engine');
		}

		if (isset($this->request->post['module_smartsearch_search_model'])) {
			$data['module_smartsearch_search_model'] = $this->request->post['module_smartsearch_search_model'];
		} else {
			$data['module_smartsearch_search_model'] = $this->config->get('module_smartsearch_search_model');
		}

		if ($this->config->get('config_secure')) {
			$data['http_catalog'] = HTTPS_CATALOG;
		} else {
			$data['http_catalog'] = HTTP_CATALOG;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/smartsearch', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/smartsearch')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {

		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "search_index` (
				`product_id` INT(11) NOT NULL,
				`metaphone` varchar(128) NOT NULL,
				`weight` tinyint(4) NOT NULL,
				PRIMARY KEY (`product_id`,`metaphone`,`weight`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");


	}
	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "search_index`");
	}
}