<?php
class ControllerExtensionModuleGenProductStatistics extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('extension/module/gen_product_statistics'); 

		$this->load->model('setting/setting');
		$this->load->model('extension/module/gen_product_statistics');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->request->post['module_gen_product_statistics_field']=$this->request->post['statistic_field'];
			
			$this->model_setting_setting->editSetting('module_gen_product_statistics', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			if (isset($this->request->post['apply'])) {
				$this->response->redirect($this->url->link('extension/module/gen_product_statistics', 'user_token=' . $this->session->data['user_token'], true));
			} else {
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true));
			}

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');

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
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/gen_product_statistics', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/gen_product_statistics', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		$product_fields=$this->model_extension_module_gen_product_statistics->getProductFields();		
		
		$data['fields']=array();
		foreach ($product_fields as $key => $value) {
			$data['fields'][]=array(
				'index' => $key
			);
		}

		if (isset($this->request->post['module_gen_product_statistics_status'])) {
			$data['module_gen_product_statistics_status'] = $this->request->post['module_gen_product_statistics_status'];
		} else {
			$data['module_gen_product_statistics_status'] = $this->config->get('module_gen_product_statistics_status');
		}

		if (isset($this->request->post['module_gen_product_statistics_field'])) {
			$data['statistic_fields'] = $this->request->post['module_gen_product_statistics_field'];
		} else {
			$data['statistic_fields'] = $this->config->get('module_gen_product_statistics_field');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/gen_product_statistics', $data));
	}

	protected function validate()
	{
		if (!$this->user->hasPermission('modify', 'extension/module/gen_product_statistics')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
}