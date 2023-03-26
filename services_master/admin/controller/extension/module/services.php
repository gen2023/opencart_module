<?php
class ControllerExtensionModuleServices extends Controller {
	private $error = array();

	public function index() {	
		
		$this->load->language('extension/module/services'); 

		$this->load->model('setting/setting');   
		$this->load->model('extension/module/services');
		
		$this->model_extension_module_services->createServices();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) { 
			$this->model_setting_setting->editSetting('services', $this->request->post);
			$this->model_extension_module_services->addServices($this->request->post);			
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');		

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_name'] = $this->language->get('entry_name');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_service_add'] = $this->language->get('button_service_add');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/services', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('extension/module/services', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['services_status'])) {
			$data['services_status'] = $this->request->post['services_status'];
		} else {
			$data['services_status'] = $this->config->get('services_status');
		}
		
		$services=$this->model_extension_module_services->getServices();
		
		if (isset($this->request->post['services'])) {
			$data['services'] = $this->request->post['services'];
		} else{
			$data['services'] = $services;
		} 

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/services.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/services')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
}