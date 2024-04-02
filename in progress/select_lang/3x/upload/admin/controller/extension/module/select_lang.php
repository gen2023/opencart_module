<?php
class ControllerExtensionModuleSelectLang extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('extension/module/select_lang');

		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_select_lang', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

		if (isset($this->error['module_select_lang_second'])) {
			$data['error_second'] = $this->error['module_select_lang_second'];
		} else {
			$data['error_second'] = '';
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
			'href' => $this->url->link('extension/module/select_lang', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/select_lang', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        if (isset($this->request->post['module_select_lang_status'])) {
			$data['module_select_lang_status'] = $this->request->post['module_select_lang_status'];
		} else {
			$data['module_select_lang_status'] = $this->config->get('module_select_lang_status');
		}
		//статус
        if (isset($this->request->post['module_select_lang_second'])) {
			$data['module_select_lang_second'] = $this->request->post['module_select_lang_second'];
		} else {
			$data['module_select_lang_second'] = $this->config->get('module_select_lang_second');
		}
		//выводить ли картинки
		if (isset($this->request->post['module_select_lang_image'])) {
			$data['module_select_lang_image'] = $this->request->post['module_select_lang_image'];
		} else {
			$data['module_select_lang_image'] = $this->config->get('module_select_lang_image');
		}
		//проверка на кукки
		if (isset($this->request->post['module_select_lang_isCoockie'])) {
			$data['module_select_lang_isCoockie'] = $this->request->post['module_select_lang_isCoockie'];
		} else {
			$data['module_select_lang_isCoockie'] = $this->config->get('module_select_lang_isCoockie');
		}
		//отображать через макет (на определенных страницах или на всех)
		if (isset($this->request->post['module_select_lang_isAllPage'])) {
			$data['module_select_lang_isAllPage'] = $this->request->post['module_select_lang_isAllPage'];
		} else {
			$data['module_select_lang_isAllPage'] = $this->config->get('module_select_lang_isAllPage');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/select_lang', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/select_lang')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['module_select_lang_second']) {
			$this->error['module_select_lang_second'] = $this->language->get('error_second');
		}

		return !$this->error;
	}
}