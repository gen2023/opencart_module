<?php
class ControllerExtensionModuleTypeWork extends Controller {
	
	public function install() {
		$this->load->model('extension/module/typeWork');
		$this->model_extension_module_typeWork->install();
	}
	public function uninstall() {
		$this->load->model('extension/module/typeWork');
		$this->model_extension_module_typeWork->uninstall();
	}
	
	private $error = array();

	public function index() {
		$this->load->language('extension/module/typeWork');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_typeWork', $this->request->post);

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
			'href' => $this->url->link('extension/module/typeWork', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/typeWork', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_typeWork_status'])) {
			$data['module_typeWork_status'] = $this->request->post['module_typeWork_status'];
		} else {
			$data['module_typeWork_status'] = $this->config->get('module_typeWork_status');
		}
		if (isset($this->request->post['module_typeWork_time1'])) {
			$data['module_typeWork_time1'] = $this->request->post['module_typeWork_time1'];
		} else {
			$data['module_typeWork_time1'] = $this->config->get('module_typeWork_time1');
		}
		if (isset($this->request->post['module_typeWork_time2'])) {
			$data['module_typeWork_time2'] = $this->request->post['module_typeWork_time2'];
		} else {
			$data['module_typeWork_time2'] = $this->config->get('module_typeWork_time2');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/typeWork', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/typeWork')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}