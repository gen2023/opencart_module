<?php
class ControllerExtensionDashboardCountProduCtcategory extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/dashboard/count_product_category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			//var_dump($this->request->post);
			$this->model_setting_setting->editSetting('dashboard_count_product_category', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['category'])) {
			$data['error_warning'] = $this->error['category'];
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
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/dashboard/count_product_category', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/dashboard/count_product_category', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true);

		if (isset($this->request->post['dashboard_count_product_category_width'])) {
			$data['dashboard_count_product_category_width'] = $this->request->post['dashboard_count_product_category_width'];
		} else {
			$data['dashboard_count_product_category_width'] = $this->config->get('dashboard_count_product_category_width');
		}
	
		$data['columns'] = array();
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}
				
		if (isset($this->request->post['dashboard_count_product_category_status'])) {
			$data['dashboard_count_product_category_status'] = $this->request->post['dashboard_count_product_category_status'];
		} else {
			$data['dashboard_count_product_category_status'] = $this->config->get('dashboard_count_product_category_status');
		}

		if (isset($this->request->post['dashboard_count_product_category_sort_order'])) {
			$data['dashboard_count_product_category_sort_order'] = $this->request->post['dashboard_count_product_category_sort_order'];
		} else {
			$data['dashboard_count_product_category_sort_order'] = $this->config->get('dashboard_count_product_category_sort_order');
		}
		
		if (isset($this->request->post['dashboard_count_product_category_category_id'])) {
			$data['dashboard_count_product_category_category_id'] = $this->request->post['dashboard_count_product_category_category_id'];
		} else {
			$data['dashboard_count_product_category_category_id'] = $this->config->get('dashboard_count_product_category_category_id');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/dashboard/count_product_category_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/dashboard/count_product_category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ($this->request->post['dashboard_count_product_category_category_id']=="") {
			$this->error['category'] = $this->language->get('error_category_id');
		}

		return !$this->error;
	}
	
	public function dashboard() {
		$this->load->language('extension/dashboard/count_product_category');

		//$data['user_token'] = $this->session->data['user_token'];
		$category_id=$this->config->get('dashboard_count_product_category_category_id');
		// Total Orders
		$this->load->model('extension/dashboard/count_product_category');

		// Customers count_product_category
		$data['total'] = $this->model_extension_dashboard_count_product_category->getTotal($category_id);

		//$data['online'] = $this->url->link('extension/module/count_product_category', 'user_token=' . $this->session->data['user_token'], true);

		return $this->load->view('extension/dashboard/count_product_category_info', $data);
	}
}
