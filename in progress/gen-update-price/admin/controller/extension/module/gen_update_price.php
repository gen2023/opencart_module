<?php
class ControllerExtensionModuleGenUpdatePrice extends Controller {
	private $error = array();
	
	public function install() {
		$this->load->model('extension/module/gen_update_price');
		$this->model_extension_module_gen_update_price->install();
	}
	public function uninstall() {
		$this->load->model('extension/module/gen_update_price');
		$this->model_extension_module_gen_update_price->uninstall();
	}

	public function index() {
		$this->load->language('extension/module/gen_update_price');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/gen_update_price');	
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			//var_dump($this->request->post);
			$this->model_setting_setting->editSetting('module_gen_update_price', $this->request->post);

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
			'href' => $this->url->link('extension/module/gen_update_price', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/gen_update_price', 'user_token=' . $this->session->data['user_token'], true);
		
		$data['user_token'] = $this->session->data['user_token'];

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
//var_dump($this->config->get);
	/*	if (isset($this->request->post['module_gen_update_price_status'])) {
			$data['module_gen_update_price_status'] = $this->request->post['module_gen_update_price_status'];
		} else {
			$data['module_gen_update_price_status'] = $this->config->get('module_gen_update_price_status');
		}*/

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/gen_update_price', $data));
	}
	
	public function apply(){
		
		$this->load->model('extension/module/gen_update_price');
	
		$json = array();

		$result = $this->model_extension_module_gen_update_price->apply($this->request->post);

		$json['result']=$result;
		
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function resetPrice(){
		
		$this->load->model('extension/module/gen_update_price');
	
		$json = array();
		$result = $this->model_extension_module_gen_update_price->resetPrice();

		$json['result']=$result;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/gen_update_price')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}