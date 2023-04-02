<?php
	class ControllerCatalogAjaxTestimonial extends Controller { 
		private $error = array();
		
		public function index() {
			$this->load->language('catalog/ajaxtestimonial');
			
			$this->document->SetTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/ajaxtestimonial');
			
			$this->getList();
			
			$this->model_catalog_ajaxtestimonial->createDatabaseTables();
		}
		
		public function insert() {
			$this->load->language('catalog/ajaxtestimonial');
			
			$this->document->SetTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/ajaxtestimonial');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_ajaxtestimonial->addajaxtestimonial($this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				$this->redirect($this->url->link('catalog/ajaxtestimonial', 'token=' . $this->session->data['token']. $url, 'SSL'));
			}
			
			$this->getForm(true);
		}
		
		public function update() {
			$this->load->language('catalog/ajaxtestimonial');
			
			$this->document->SetTitle( $this->language->get('heading_title') );
			
			$this->load->model('catalog/ajaxtestimonial');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_ajaxtestimonial->editajaxtestimonial($this->request->get['ajaxtestimonial_id'], $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				$this->redirect($this->url->link('catalog/ajaxtestimonial', 'token=' . $this->session->data['token']. $url, 'SSL'));
				
			}
			
			$this->getForm(false);
		}
		
		public function delete() {
			$this->load->language('catalog/ajaxtestimonial');
			
			$this->document->SetTitle( $this->language->get('heading_title'));
			
			$this->load->model('catalog/ajaxtestimonial');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $ajaxtestimonial_id) {
					$this->model_catalog_ajaxtestimonial->deleteajaxtestimonial($ajaxtestimonial_id);
				}
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				$this->redirect($this->url->link('catalog/ajaxtestimonial', 'token=' . $this->session->data['token']. $url, 'SSL'));
				
			}
			
			$this->getList();
		}
		
		private function getList() {
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 't.date_added';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = 'DESC';
			}
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/ajaxtestimonial', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
			);
			
			$this->data['insert'] = $this->url->link('catalog/ajaxtestimonial/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['delete'] = $this->url->link('catalog/ajaxtestimonial/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	
			$this->data['module_settings_path'] = $this->url->link('module/ajaxtestimonial', 'token=' . $this->session->data['token'] . $url, 'SSL');	
			$this->data['text_module_settings'] = $this->language->get('text_module_settings');
			
			$this->data['ajaxtestimonials'] = array();
			
			$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * 10,
			'limit' => 10
			);
			
			$ajaxtestimonial_total = $this->model_catalog_ajaxtestimonial->getTotalajaxtestimonials();
			$this->data['ajaxtestimonial_total'] = $ajaxtestimonial_total;
			
			if ($ajaxtestimonial_total != -1) {
				$results = $this->model_catalog_ajaxtestimonial->getajaxtestimonials($data);
				}else{
				$results = array();
				$ajaxtestimonial_total = 0;
			}
			
    		foreach ($results as $result) {
				$action = array();
				
				$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/ajaxtestimonial/update', 'token=' . $this->session->data['token']. '&ajaxtestimonial_id=' . $result['ajaxtestimonial_id'] , 'SSL')
				);
				
				$result['description'] = strip_tags($result['description']);
				
				$my_replys = array();
				$reply = array();
				$my_replys = $this->model_catalog_ajaxtestimonial->getajaxtestimonialsReply($result['ajaxtestimonial_id']);
				
				if ($my_replys){
					foreach ($my_replys as $my_reply) {			
						$actions = array();
						
						$actions[] = array(
						'text' => $this->language->get('text_edit'),
						'href' => $this->url->link('catalog/ajaxtestimonial/update', 'token=' . $this->session->data['token']. '&ajaxtestimonial_id=' . $my_reply['ajaxtestimonial_id'] , 'SSL')
						);
						
						$reply[] = array(
						'id'		=> $my_reply['ajaxtestimonial_id'],
						'status'		=> ($my_reply['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
						'action'     	=> $actions,
						'name'		=> $my_reply['name'],
						'description'	=> $my_reply['description'],
						'date_added'	=> date($this->config->get('ajaxtestimonial_all_page_date_format'), strtotime($result['date_added']))
						);
					}
				}
				
				$this->data['ajaxtestimonials'][] = array(
				'reply' => $reply,
				'ajaxtestimonial_id' => $result['ajaxtestimonial_id'],
				'parent_testimonial_id' => $result['parent_testimonial_id'],
				'name'		=> $result['name'],
				'description'	=> $result['description'],
				'phone'		=> $result['phone'],
				'date_added' 	=> $result['date_added'],
				'status' 		=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   	=> isset($this->request->post['selected']) && in_array($result['ajaxtestimonial_id'], $this->request->post['selected']),
				'action'     	=> $action
				);
			}	
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['column_title'] = $this->language->get('column_title');
			$this->data['column_description'] = $this->language->get('column_description');
			
			$this->data['column_date_added'] = $this->language->get('column_date_added');
			$this->data['column_status'] = $this->language->get('column_status');
			$this->data['column_action'] = $this->language->get('column_action');		
			$this->data['column_name'] = $this->language->get('column_name');		
			$this->data['column_phone'] = $this->language->get('column_phone');
			$this->data['button_insert'] = $this->language->get('button_insert');
			$this->data['button_delete'] = $this->language->get('button_delete');
			$this->data['entry_install_first'] = $this->language->get('entry_install_first');;
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
				} else {
				$this->data['success'] = '';
			}
			
			$url = '';
			
			if ($order == 'ASC') {
				$url .= '&order=' .  'DESC';
				} else {
				$url .= '&order=' .  'ASC';
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['sort_title'] = $this->url->link('catalog/ajaxtestimonial', 'token=' . $this->session->data['token'] . '&sort=td.title' . $url, 'SSL');
			$this->data['sort_date_added'] = $this->url->link('catalog/ajaxtestimonial', 'token=' . $this->session->data['token'] . '&sort=t.date_added' . $url, 'SSL');		
			$this->data['sort_status'] = $this->url->link('catalog/ajaxtestimonial', 'token=' . $this->session->data['token'] . '&sort=t.status' . $url, 'SSL');		
			$this->data['sort_name'] = $this->url->link('catalog/ajaxtestimonial', 'token=' . $this->session->data['token'] . '&sort=t.name' . $url, 'SSL');		
			$this->data['sort_description'] = $this->url->link('catalog/ajaxtestimonial', 'token=' . $this->session->data['token'] . '&sort=td.description' . $url, 'SSL');		
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $ajaxtestimonial_total;
			$pagination->page = $page;
			$pagination->limit = 10; 
			$pagination->text = $this->language->get('text_pagination');
			
			$pagination->url = $this->url->link('catalog/ajaxtestimonial', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');		
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			$this->template = 'catalog/ajaxtestimonial_list.tpl';
			$this->children = array(
			'common/header',	
			'common/footer'	
			);
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		}
		
		private function getForm($is_edit) {
			
			$this->data['is_edit'] = $is_edit;
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['entry_title'] = $this->language->get('entry_title');
			$this->data['entry_status'] = $this->language->get('entry_status');
			$this->data['entry_description'] = $this->language->get('entry_description');
			$this->data['entry_date_added'] = $this->language->get('entry_date_added');
			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_phone'] = $this->language->get('entry_phone');
			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			$this->data['entry_email'] = $this->language->get('entry_email');
			$this->data['entry_page'] = $this->language->get('entry_page');
			
			$this->data['entry_rating'] = $this->language->get('entry_rating');
			$this->data['entry_bad'] = $this->language->get('entry_bad');
			$this->data['entry_good'] = $this->language->get('entry_good');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			
			$this->data['entry_select_file'] = $this->language->get('entry_select_file');
			$this->data['entry_delete_file'] = $this->language->get('entry_delete_file');
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->error['name'])) {
				$this->data['error_name'] = $this->error['name'];
				} else {
				$this->data['error_name'] = '';
			}
			
			if (isset($this->error['description'])) {
				$this->data['error_description'] = $this->error['description'];
				} else {
				$this->data['error_description'] = '';
			}
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/ajaxtestimonial', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
			);
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (!isset($this->request->get['ajaxtestimonial_id'])) {
				$this->data['action'] = $this->url->link('catalog/ajaxtestimonial/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');		
				} else {
				$this->data['action'] = $this->url->link('catalog/ajaxtestimonial/update', 'token=' . $this->session->data['token'] . '&ajaxtestimonial_id=' . $this->request->get['ajaxtestimonial_id'] . $url, 'SSL');
			}
			
			$this->data['cancel'] = $this->url->link('catalog/ajaxtestimonial', 'token=' . $this->session->data['token'] . $url, 'SSL');
			
			$this->data['token'] = $this->session->data['token'];
			
			if (isset($this->request->get['ajaxtestimonial_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$ajaxtestimonial_info = $this->model_catalog_ajaxtestimonial->getajaxtestimonial($this->request->get['ajaxtestimonial_id']);
			}
			
			if (isset($ajaxtestimonial_info['reply'])) {
				$this->data['reply'] = $ajaxtestimonial_info['reply'];
			}else{
				$this->data['reply'] = '';
			}
			
			$this->load->model('localisation/language');
			
			$this->data['languages'] = $this->model_localisation_language->getLanguages();
			
			if (isset($this->request->post['ajaxtestimonial_description'])) {
				$this->data['ajaxtestimonial_description'] = $this->request->post['ajaxtestimonial_description'];
				} elseif (isset($this->request->get['ajaxtestimonial_id'])) {
				$this->data['ajaxtestimonial_description'] = $this->model_catalog_ajaxtestimonial->getajaxtestimonialDescriptions($ajaxtestimonial_info['ajaxtestimonial_id']);
				} else {
				$this->data['ajaxtestimonial_description'] = array();
			}
			
			if (isset($this->request->post['status'])) {
				$this->data['status'] = $this->request->post['status'];
				} elseif (isset($ajaxtestimonial_info)) {
				$this->data['status'] = $ajaxtestimonial_info['status'];
				} else {
				$this->data['status'] = 1;
			}
			
			if (isset($this->request->post['name'])) {
				$this->data['name'] = $this->request->post['name'];
				} elseif (isset($ajaxtestimonial_info)) {
				$this->data['name'] = $ajaxtestimonial_info['name'];
				} else {
				$this->data['name'] = '';
				}
			
			
			if (isset($this->request->post['phone'])) {
				$this->data['phone'] = $this->request->post['phone'];
				} elseif (isset($ajaxtestimonial_info)) {
				$this->data['phone'] = $ajaxtestimonial_info['phone'];
				} else {
				$this->data['phone'] = '';
			}
			
			if (isset($this->request->post['email'])) {
				$this->data['email'] = $this->request->post['email'];
				} elseif (isset($ajaxtestimonial_info)) {
				$this->data['email'] = $ajaxtestimonial_info['email'];
				} else {
				$this->data['email'] = '';
			}
			
			if (isset($this->request->post['date_added'])) {
				$this->data['date_added'] = $this->request->post['date_added'];
				} elseif (isset($ajaxtestimonial_info)) {
				$this->data['date_added'] = $ajaxtestimonial_info['date_added'];
				} else {
				$this->data['date_added'] = $this->model_catalog_ajaxtestimonial->getCurrentDateTime();
			}
			
			if (!$this->data['reply']) {
				
				if (isset($this->request->post['rating'])) {
					$this->data['rating'] = $this->request->post['rating'];
					} elseif (isset($ajaxtestimonial_info)) {
					$this->data['rating'] = $ajaxtestimonial_info['rating'];
					
					} else {
					
					if ($this->config->get('ajaxtestimonial_default_rating') == '') {
						$this->data['rating'] = '3';
						} else {
						$this->data['rating'] = $this->config->get('ajaxtestimonial_default_rating');
					}
				}	
				
				} else {
				$this->data['rating'] = '1';
			}
			
			
			$this->template = 'catalog/ajaxtestimonial_form.tpl';
			$this->children = array(
			'common/header',	
			'common/footer'	
			);
			
			$this->response->setOutput($this->render());
		}
		
		private function validateForm() {
			if (!$this->user->hasPermission('modify', 'catalog/ajaxtestimonial')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			foreach ($this->request->post['ajaxtestimonial_description'] as $language_id => $value) {
				if (strlen(utf8_decode($value['description'])) != 0) {
					$this->request->post['ajaxtestimonial_description'][$language_id]['description'] = (html_entity_decode($value['description']));
				}		
			}
			
			$desc = '';
			foreach ($this->request->post['ajaxtestimonial_description'] as $language_id => $value) {
				if (strlen(utf8_decode($value['description'])) != 0) {
					$desc = $value['description'];
					break;
				}		
			}
			
			if ($desc == '') {
				foreach ($this->request->post['ajaxtestimonial_description'] as $language_id => $value) {
					$this->error['description'][$language_id] = $this->language->get('error_description');
				}
				} else {
				foreach ($this->request->post['ajaxtestimonial_description'] as $language_id => $value) {
					if (strlen(utf8_decode($value['description'])) == 0) $this->request->post['ajaxtestimonial_description'][$language_id]['description'] = $desc;
				}
			}
			
			if (!$this->error) {
				return TRUE;
				} else {
				return FALSE;
			}
		}
		
		private function validateDelete() {
			if (!$this->user->hasPermission('modify', 'catalog/ajaxtestimonial')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if (!$this->error) {
				return TRUE;
				} else {
				return FALSE;
			}
		}
	}				