<?php
class ControllerExtensionModuleGenpricechart extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/genpricechart');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/genpricechart');	
		$this->load->model('setting/setting');		

		$this->model_extension_module_genpricechart->install();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			//var_dump($this->request->post);
			$this->model_setting_setting->editSetting('module_genpricechart', $this->request->post);

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
			'href' => $this->url->link('extension/module/genpricechart', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/genpricechart', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
//var_dump($this->config->get);
		if (isset($this->request->post['module_genpricechart_status'])) {
			$data['module_genpricechart_status'] = $this->request->post['module_genpricechart_status'];
		} else {
			$data['module_genpricechart_status'] = $this->config->get('module_genpricechart_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/genpricechart', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/genpricechart')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}