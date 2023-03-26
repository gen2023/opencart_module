<?php
class ControllerExtensionModuleGenVoting extends Controller {
	private $error = array();
//добавить - пользоваели могут видеть результаты или нет
//добавить вопрос сохранять куки или нет
	public function index() {
		$this->load->language('extension/module/genVoting');

		$this->load->model('extension/module');
		$this->load->model('extension/module/genVoting');		

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			//var_dump($this->request->post);

			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('genVoting', $this->request->post);
				$this->model_extension_module_genVoting->setLastId();
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');
			//var_dump($this->request->post);
			if (isset($this->request->post['apply'])) {
				$this->response->redirect($this->url->link('extension/module/genVoting', 'token=' . $this->session->data['token'] . '&module_id='.$this->request->get['module_id'], true));
			}else{
				$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');		

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_attribute'] = $this->language->get('entry_attribute');
		$data['entry_title_module'] = $this->language->get('entry_title_module');
		
		$data['button_apply'] = $this->language->get('button_apply');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_attribute_add'] = $this->language->get('button_attribute_add');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		
		if (isset($this->session->data['success'])) {
			$data['text_success'] = $this->session->data['success'];
		} else {
			$data['text_success'] = '';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/genVoting', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}
//var_dump($module_info);
		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/genVoting', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/genVoting', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
			$data['apply'] = $this->url->link('extension/module/genVoting', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		
		if (isset($this->request->post['module_id'])) {
			$data['module_id'] = $this->request->post['module_id'];
		} elseif (!empty($module_info)) {
			$data['module_id'] = $module_info['module_id'];
		} else {
			$data['module_id'] = array();
		}
		
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
		
		if (isset($this->request->post['type_module'])) {
			$data['type_module'] = $this->request->post['type_module'];
		} elseif (!empty($module_info)) {
			$data['type_module'] = $module_info['type_module'];
		} else {
			$data['type_module'] = '';
		}
		
		if (isset($this->request->post['title_module'])) {
			$data['title_module'] = $this->request->post['title_module'];
		} elseif (!empty($module_info)) {
			$data['title_module'] = $module_info['title_module'];
		} else {
			$data['title_module'] = array();
		}
		
		if (isset($this->request->post['voting_attributes'])) {
			$data['voting_attributes'] = $this->request->post['voting_attributes'];
		} elseif (!empty($module_info)) {
			$data['voting_attributes'] = $module_info['voting_attributes'];
		} else {
			$data['voting_attributes'] = array();
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/genVoting', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/genVoting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		return !$this->error;
	}
}

/*

["module_id"]=> string(2) "39" 
["name"]=> string(5) "test2" 
["title_module"]=> array(2) { [1]=> array(1) { ["name"]=> string(0) "" } [2]=> array(1) { ["name"]=> string(0) "" } } 
["type_module"]=> string(1) "0" 
["status"]=> string(1) "1" 
["voting_attributes"]=> array(3) {
	[0]=> array(3) {
		[1]=> array(1) { ["text"]=> string(1) "1" } 
		[2]=> array(1) { ["text"]=> string(1) "2" } 
		["value"]=> string(1) "0" } 
	[1]=> array(2) {
		[1]=> array(1) { ["text"]=> string(1) "3" } 
		[2]=> array(1) { ["text"]=> string(1) "4" } } 
	[2]=> array(2) { [1]=> array(1) { ["text"]=> string(1) "5" } [2]=> array(1) { ["text"]=> string(1) "6" } } } }

*/