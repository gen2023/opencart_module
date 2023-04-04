<?php
class ControllerExtensionModuleGenGraphProduct extends Controller {
	
	public function install() {
		$this->load->model('extension/module/gen_graph_product');
		$this->model_extension_module_gen_graph_product->install();
	}
	public function uninstall() {
		$this->load->model('extension/module/gen_graph_product');
		$this->model_extension_module_gen_graph_product->uninstall();
	}
	
	private $error = array();

	public function index() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		$this->load->language('extension/module/gen_graph_product');

		$this->load->model('extension/module/gen_graph_product');

		$data['heading_title'] = $this->language->get('heading_title');
	
		$data['text_list'] = $this->language->get('text_list');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_no_results'] = $this->language->get('text_no_results');
	
		$data['column_name'] = $this->language->get('column_name');		
		$data['column_product'] = $this->language->get('column_product');
		$data['column_srok'] = $this->language->get('column_srok');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');		
	
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_setting'] = $this->language->get('button_setting');
		$data['button_copy'] = $this->language->get('button_copy');
	
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
	
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
	
		$data['breadcrumbs'] = array();
	
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
	
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/module/gen_graph_product', 'user_token=' . $this->session->data['user_token'], true),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$data['add'] = $this->url->link('extension/module/gen_graph_product/add', 'user_token=' . $this->session->data['user_token'], true);
		$data['delete'] = $this->url->link('extension/module/gen_graph_product/delete', 'user_token=' . $this->session->data['user_token'], true);
		$data['copy'] = $this->url->link('extension/module/gen_graph_product/copy', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['setting'] = $this->url->link('extension/module/gen_graph_product/setting', 'user_token=' . $this->session->data['user_token'], true);
	
		$work_total = $this->model_extension_module_gen_graph_product->getTotalWorks();
	
		$this->load->model('catalog/product');
	
		$data['works'] = array();
	
		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$results = $this->model_extension_module_gen_graph_product->getWorksList($filter_data);
//var_dump($results);
    	foreach ($results as $result) {
			$idProduct=explode(',',$result['product_id']);
			$listNameProduct = array();

			foreach ($idProduct as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					$listNameProduct[] = array(
						'name'       => $product_info['name']
					);
				}
			}
			//var_dump($listNameProduct);
		
			$data['works'][] = array(
				'work_id'     	=> $result['work_id'],
				'name'       	=> $result['name'],
				'products'      => $listNameProduct,
				'srok'       	=> $result['month_start'] .' - '. $result['month_end'],
				'status'     	=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'    	=> isset($this->request->post['selected']) && in_array($result['work_id'], $this->request->post['selected']),
				'edit'      	=> $this->url->link('extension/module/gen_graph_product/edit', 'user_token=' . $this->session->data['user_token'] . '&work_id=' . $result['work_id'], true)
			);
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('extension/module/gen_graph_product', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $work_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/gen_graph_product', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($work_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($work_total - $this->config->get('config_limit_admin'))) ? $work_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $work_total, ceil($work_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;
	
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/gen_graph_product_list', $data));

	}
	
	public function add() {
		$this->load->language('extension/module/gen_graph_product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/gen_graph_product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_module_gen_graph_product->addWork($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('extension/module/gen_graph_product', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}
	
	public function copy() {
		$this->load->language('extension/module/gen_graph_product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/gen_graph_product');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $work_id) {
				$this->model_extension_module_gen_graph_product->copyWork($work_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';

			$this->response->redirect($this->url->link('extension/module/gen_graph_product', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->index();
	}

	public function edit() {
		$this->load->language('extension/module/gen_graph_product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/gen_graph_product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_module_gen_graph_product->editWork($this->request->get['work_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('extension/module/gen_graph_product', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('extension/module/gen_graph_product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/gen_graph_product');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $work_id) {
				$this->model_extension_module_gen_graph_product->deleteWork($work_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('extension/module/gen_graph_product', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->index();
	}
	
	private function getForm() { 

		$this->load->language('extension/module/gen_graph_product');
	
		$this->load->model('extension/module/gen_graph_product');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['heading_title'] = $this->language->get('heading_title');
	
		$data['text_form'] = !isset($this->request->get['work_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_color'] = $this->language->get('entry_color');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_month_start'] = $this->language->get('entry_month_start');
		$data['entry_month_end'] = $this->language->get('entry_month_end');
		
		$data['help_product'] = $this->language->get('help_product');
		$data['help_color'] = $this->language->get('help_color');	
	
		$data['user_token'] = $this->session->data['user_token'];
		
		$data['breadcrumbs'] = array();
	
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
	
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/module/gen_graph_product', 'user_token=' . $this->session->data['user_token'], true),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}
	

		if (!isset($this->request->get['work_id'])) {
			$data['action'] = $this->url->link('extension/module/gen_graph_product/add', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/gen_graph_product/edit', 'user_token=' . $this->session->data['user_token'] . '&work_id=' . $this->request->get['work_id'], true);
		}
	
		$data['cancel'] = $this->url->link('extension/module/gen_graph_product', 'user_token=' . $this->session->data['user_token'], true);
	
		if ((isset($this->request->get['work_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$work_info = $this->model_extension_module_gen_graph_product->getWorkInfo($this->request->get['work_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (isset($work_info)) {
			$data['name'] = $work_info['name'];
		} else {
			$data['name'] = '';
		}
		
		if (isset($this->request->post['work_description'])) {
			$data['work_description'] = $this->request->post['work_description'];
		} elseif (isset($this->request->get['work_id'])) {
			$data['work_description'] = $this->model_extension_module_gen_graph_product->getWorkDescriptions($this->request->get['work_id']);
		} else {
			$data['work_description'] = array();
		}
		
		if (isset($this->request->post['month_start'])) {
			$data['month_start'] = $this->request->post['month_start'];
		} elseif (isset($work_info)) {
			$data['month_start'] = $work_info['month_start'];
		} else {
			$data['month_start'] = '0';
		}
		
		if (isset($this->request->post['month_end'])) {
			$data['month_end'] = $this->request->post['month_end'];
		} elseif (isset($work_info)) {
			$data['month_end'] = $work_info['month_end'];
		} else {
			$data['month_end'] = '1';
		}
		
		if (isset($this->request->post['color'])) {
			$data['color'] = $this->request->post['color'];
		} elseif (!empty($work_info)) {
			$data['color'] = $work_info['color'];
		} else {
			$data['color'] = '';
		}
		
		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($work_info)) {
			$data['sort_order'] = $work_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		$this->load->model('catalog/product');

		$data['products'] = array();

		if (!empty($this->request->post['product'])) {
			$products = $this->request->post['product'];
		} elseif (!empty($work_info['product_id'])) {			
			$products = explode(',',$work_info['product_id']);
		} else {
			$products = array();
		}
//var_dump($products);
		$data['products'] = array();

		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}
		
		$this->load->model('setting/store');
	
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (isset($work_info)) {
			$data['status'] = $work_info['status'];
		} else {
			$data['status'] = '';
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/gen_graph_product_form', $data));

	}

	public function setting() {	
		
		$this->load->language('extension/module/gen_graph_product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/gen_graph_product');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateSetting()) {
			
			$this->model_setting_setting->editSetting('module_gen_graph_product', $this->request->post['']);
			
			$this->model_extension_module_gen_graph_product->setSetting($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module/gen_graph_product', 'user_token=' . $this->session->data['user_token'], true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}		

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['entry_titleModule'] = $this->language->get('entry_titleModule');
		
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_graph'] = $this->language->get('tab_graph');
		
		$data['help_titleModule'] = $this->language->get('help_titleModule');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		$data['action'] = $this->url->link('extension/module/gen_graph_product/setting', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('extension/module/gen_graph_product', 'user_token=' . $this->session->data['user_token'], true);
	
		$data['breadcrumbs'] = array();
	
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
	
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/module/gen_graph_product', 'user_token=' . $this->session->data['user_token'], true),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/module/gen_graph_product/setting', 'user_token=' . $this->session->data['user_token'], true),
			'text'      => $this->language->get('text_setting'),
			'separator' => ' :: '
		);	

		$setting = $this->model_extension_module_gen_graph_product->getSetting();
		
		if($setting['title']){
			$data['title'] = $setting['title'];
		}else{
			$data['title'] = '';
		}
		
		if($setting['moreOption']){
			$data['moreOption'] = $setting['moreOption'];
		}else{
			$data['moreOption'] = 0;
		}
		
		if (isset($this->request->post['module_gen_graph_product_status'])) {
			$data['module_gen_graph_product_status'] = $this->request->post['module_gen_graph_product_status'];
		} else {
			$data['module_gen_graph_product_status'] = $this->config->get('module_gen_graph_product_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/gen_graph_product_setting', $data));
	}

	public function autocomplete() {
		$json = array();
		
		$this->load->model('tool/image');
		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/product');
			$this->load->model('catalog/option');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/module/gen_graph_product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		foreach ($this->request->post['work_description'] as $language_id => $value) {
			if ((strlen($value['title']) < 3) || (strlen($value['title']) > 255)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		}
		
		if (!($this->request->post['name'])){
			$this->error['name']=$this->language->get('error_name');
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
	
		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'extension/module/gen_graph_product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/module/gen_graph_product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	
		return !$this->error;
	}

	protected function validateSetting() {
		if (!$this->user->hasPermission('modify', 'extension/module/gen_graph_product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
		
	}

}