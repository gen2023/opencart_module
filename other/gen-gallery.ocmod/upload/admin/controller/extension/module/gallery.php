<?php
class ControllerExtensionModuleGallery extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/gallery');

		$this->load->model('extension/gallery');
		
		$this->document->setTitle($this->language->get('heading_title'));
	
		$this->load->model('setting/setting');
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting(gallery, $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module/gallery', 'token=' . $this->session->data['token'], true));
	
		}
	
		$this->getList();
	}

	public function add() {
		$this->load->language('extension/module/gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/gallery');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_gallery->addGallery($this->request->post);

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

			$this->response->redirect($this->url->link('extension/module/gallery', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/module/gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/gallery');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_gallery->editGallery($this->request->get['gallery_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('extension/module/gallery', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('extension/module/gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/gallery');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $gallery_id) {
				$this->model_extension_gallery->deleteGallery($gallery_id);
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

			$this->response->redirect($this->url->link('extension/module/gallery', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}
	
	public function copy() {
		$this->load->language('extension/module/gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/gallery');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $gallery_id) {
				$this->model_extension_gallery->copyGallery($gallery_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

      if (isset($this->request->get['filter_category'])) {
        $url .= '&filter_category=' . $this->request->get['filter_category'];
      }

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/module/gallery', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	private function getList() {

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'nd.title';
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

		$this->load->language('extension/module/gallery');

		$this->load->model('extension/gallery');

		$data['heading_title'] = $this->language->get('heading_title');
	
		$data['text_list'] = $this->language->get('text_list');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_no_results'] = $this->language->get('text_no_results');
	
		$data['column_image'] = $this->language->get('column_image');		
		$data['column_title'] = $this->language->get('column_title');
		$data['column_date_from'] = $this->language->get('column_date_from');
		$data['column_date_to'] = $this->language->get('column_date_to');
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
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
	
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/module/gallery', 'token=' . $this->session->data['token'], true),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
	
		$data['add'] = $this->url->link('extension/module/gallery/add', 'token=' . $this->session->data['token'], true);
		$data['delete'] = $this->url->link('extension/module/gallery/delete', 'token=' . $this->session->data['token'], true);
		$data['setting'] = $this->url->link('extension/module/gallery/setting', 'token=' . $this->session->data['token'], true);
		$data['copy'] = $this->url->link('extension/module/gallery/copy', 'token=' . $this->session->data['token'] . $url, true);
	
		$gallery_total = $this->model_extension_gallery->getTotalGallery();
	
		$this->load->model('tool/image');
	
		$data['gallery'] = array();
	
			$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$results = $this->model_extension_gallery->getGalleryList($filter_data);
	
    	foreach ($results as $result) {
		
			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', 40, 40);
			}
		
			$data['gallery'][] = array(
				'gallery_id'     	=> $result['gallery_id'],
				'title'       	=> $result['title'],
				'image'      	=> $image,
				'date_from'  	=> date($this->language->get('date_format_short'), strtotime($result['date_from'])),
				'date_to'  	=> date($this->language->get('date_format_short'), strtotime($result['date_to'])),
				'status'     	=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'    	=> isset($this->request->post['selected']) && in_array($result['gallery_id'], $this->request->post['selected']),
				'edit'      	=> $this->url->link('extension/module/gallery/edit', 'token=' . $this->session->data['token'] . '&gallery_id=' . $result['gallery_id'], true)
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

		$data['sort_title'] = $this->url->link('extension/module/gallery', 'token=' . $this->session->data['token'] . '&sort=nd.title' . $url, true);
		$data['sort_date_from'] = $this->url->link('extension/module/gallery', 'token=' . $this->session->data['token'] . '&sort=n.date_from' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $gallery_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/gallery', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($gallery_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($gallery_total - $this->config->get('config_limit_admin'))) ? $gallery_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $gallery_total, ceil($gallery_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;
	
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/gallery_list', $data));

	}

	private function getForm() { 

		$this->load->language('extension/module/gallery');
	
		$this->load->model('extension/gallery');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['heading_title'] = $this->language->get('heading_title');
	
		$data['text_form'] = !isset($this->request->get['gallery_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
    	$data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');
		$data['text_license'] = $this->language->get('text_license');
	
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['column_date_added'] = $this->language->get('column_date_added');
	
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_h1'] = $this->language->get('entry_meta_h1');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_mindescription'] = $this->language->get('entry_mindescription');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_date_from'] = $this->language->get('entry_date_from');
		$data['entry_date_to'] = $this->language->get('entry_date_to');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_license'] = $this->language->get('entry_license');
	
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
	
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_license'] = $this->language->get('tab_license');

		$data['help_keyword'] = $this->language->get('help_keyword');		
	
		$data['token'] = $this->session->data['token'];
		
		$gallery_value = $this->model_extension_gallery->getValue();
		$data['value2']=$gallery_value['value2'];
	
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['license'])) {
			$data['error_license'] = $this->error['license'];
		} else {
			$data['error_license'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}

		if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = array();
		}
		
		if (isset($this->error['mindescription'])) {
			$data['error_mindescription'] = $this->error['mindescription'];
		} else {
			$data['error_mindescription'] = array();
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}
	
		$data['breadcrumbs'] = array();
	
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
	
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/module/gallery', 'token=' . $this->session->data['token'], true),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
	
		if (!isset($this->request->get['gallery_id'])) {
			$data['action'] = $this->url->link('extension/module/gallery/add', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/gallery/edit', 'token=' . $this->session->data['token'] . '&gallery_id=' . $this->request->get['gallery_id'], true);
		}
	
		$data['cancel'] = $this->url->link('extension/module/gallery', 'token=' . $this->session->data['token'], true);
	
		if ((isset($this->request->get['gallery_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$gallery_info = $this->model_extension_gallery->getGalleryStory($this->request->get['gallery_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
	
		if (isset($this->request->post['gallery_description'])) {
			$data['gallery_description'] = $this->request->post['gallery_description'];
		} elseif (isset($this->request->get['gallery_id'])) {
			$data['gallery_description'] = $this->model_extension_gallery->getGalleryDescriptions($this->request->get['gallery_id']);
		} else {
			$data['gallery_description'] = array();
		}
		
		if (isset($this->request->post['meta_keyword'])) {
			$data['meta_keyword'] = $this->request->post['meta_keyword'];
		} elseif (isset($this->request->get['gallery_id'])) {
			$data['meta_keyword'] = $this->model_extension_gallery->getGalleryDescriptions($this->request->get['gallery_id']);
		} else {
			$data['meta_keyword'] = array();
		}

		if (isset($this->request->post['date_from'])) {
       		$data['date_from'] = $this->request->post['date_from'];
		} elseif (isset($gallery_info['date_from'])) {
			$data['date_from'] = $gallery_info['date_from'];
		} else {
			$data['date_from'] = date('Y-m-d');
		}

		if (isset($this->request->post['date_to'])) {
       		$data['date_to'] = $this->request->post['date_to'];
		} elseif (isset($gallery_info['date_to'])) {
			$data['date_to'] = $gallery_info['date_to'];
		} else {
			$data['date_to'] = date('Y-m-d');
		}
	
		$this->load->model('setting/store');
	
		$data['stores'] = $this->model_setting_store->getStores();
	
		if (isset($this->request->post['gallery_store'])) {
			$data['gallery_store'] = $this->request->post['gallery_store'];
		} elseif (isset($gallery_info)) {
			$data['gallery_store'] = $this->model_extension_gallery->getGalleryStores($this->request->get['gallery_id']);
		} else {
			$data['gallery_store'] = array(0);
		}
	
		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($gallery_info)) {
			$data['keyword'] = $gallery_info['keyword'];
		} else {
			$data['keyword'] = '';
		}
	
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (isset($gallery_info)) {
			$data['status'] = $gallery_info['status'];
		} else {
			$data['status'] = '';
		}
	
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($gallery_info)) {
			$data['image'] = $gallery_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($gallery_info) && is_file(DIR_IMAGE . $gallery_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($gallery_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
	
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/gallery_form', $data));

	}
	
	public function setting() {	
		
		$this->load->language('extension/module/gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('extension/gallery');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->model_setting_setting->editSetting('gallery_setting', $this->request->post);
				if (isset($this->request->post['gallery_url'])) {
					$this->model_extension_gallery->setGalleryListUrl($this->request->post);
				}	
			$this->session->data['success'] = $this->language->get('text_success');

			$this->cache->delete('gallery_setting');

			$this->response->redirect($this->url->link('extension/module/gallery', 'token=' . $this->session->data['token'], true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}		

		$data['heading_title'] = $this->language->get('heading_title');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['entry_gallery_url'] = $this->language->get('entry_gallery_url');
		$data['entry_gallerylist_url'] = $this->language->get('entry_gallerylist_url');
		$data['entry_share'] = $this->language->get('entry_share');
				
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['action'] = $this->url->link('extension/module/gallery/setting', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/module/gallery', 'token=' . $this->session->data['token'], true);
	
		$data['breadcrumbs'] = array();
	
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
	
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/module/gallery', 'token=' . $this->session->data['token'], true),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/module/gallery/setting', 'token=' . $this->session->data['token'], true),
			'text'      => $this->language->get('text_gallery_setting'),
			'separator' => ' :: '
		);	

		if (isset($this->request->post['gallery_setting'])) {
			$gallery_setting = $this->request->post['gallery_setting'];
		} elseif ($this->config->get('gallery_setting')) {
			$gallery_setting = $this->config->get('gallery_setting');
		} else {
			$gallery_setting = array();
		}

		$setting = $this->model_extension_gallery->getGalleryListUrl();
		
		$seo_gallery=$setting[0];
		$seo_gallerylist=$setting[1];
		$gallery_share=$setting[2];
		
		if($gallery_share['gallery_share']){
			$data['gallery_share'] = $gallery_share['gallery_share'];
		}else{
			$data['gallery_share'] = '';
		}
		
		if($seo_gallery['keyword']){
			$data['gallery_url'] = $seo_gallery['keyword'];
		}else{
			$data['gallery_url'] = '';
		}
	
		if($seo_gallerylist['keyword']){
			$data['gallerylist_url'] = $seo_gallerylist['keyword'];
		}else{
			$data['gallerylist_url'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/gallery_setting', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/module/gallery')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		foreach ($this->request->post['gallery_description'] as $language_id => $value) {
			if ((strlen($value['title']) < 3) || (strlen($value['title']) > 255)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		
			if (strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}
			
			if (strlen($value['mindescription']) < 3) {
				$this->error['mindescription'][$language_id] = $this->language->get('error_mindescription');
			}
		}
		
		$gallery_value = $this->model_extension_gallery->getValue();
		if ($this->request->post['license'] != $gallery_value['value1']) {
				$this->error['license'][$language_id] = $this->language->get('error_license');
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
	
		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/module/gallery')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	
		return !$this->error;
	}
	
	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'extension/module/gallery')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
?>