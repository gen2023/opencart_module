<?php
class ControllerExtensionModuleGentestimonialsList extends Controller {
	private $error = array();
	
	public function index() {

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'nd.user';
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

		$this->load->language('extension/module/gentestimonials');

		$this->load->model('extension/module/gentestimonials');

		$data['heading_title'] = $this->language->get('heading_title');
	
		$data['text_list'] = $this->language->get('text_list');
		/*$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_no_results'] = $this->language->get('text_no_results');
	*/
		$data['column_image'] = $this->language->get('column_image');		
		$data['column_user'] = $this->language->get('column_user');
		$data['column_date'] = $this->language->get('column_date');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');		
	
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
	
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
			'href'      => $this->url->link('extension/module/gentestimonials_list', 'user_token=' . $this->session->data['user_token'], true),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
	
		$data['add'] = $this->url->link('extension/module/gentestimonials_list/add', 'user_token=' . $this->session->data['user_token'], true);
		$data['delete'] = $this->url->link('extension/module/gentestimonials_list/delete', 'user_token=' . $this->session->data['user_token'], true);
		$data['copy'] = $this->url->link('extension/module/gentestimonials_list/copy', 'user_token=' . $this->session->data['user_token'] . $url, true);
	
		$testimonials_total = $this->model_extension_module_gentestimonials->getTotalTestimonial();
	
		$this->load->model('tool/image');
	
		$data['testimonials'] = array();
	
			$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$results = $this->model_extension_module_gentestimonials->getTestimonialList($filter_data);
//var_dump($result);
    	foreach ($results as $result) {
		
			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', 40, 40);
			}
		
			$data['testimonials'][] = array(
				'testimonial_id'     	=> $result['testimonial_id'],
				'user'       	=> $result['user'],
				'image'      	=> $image,
				'date'  	=> date($this->language->get('date_format_short'), strtotime($result['date'])),
				'status'     	=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'    	=> isset($this->request->post['selected']) && in_array($result['testimonial_id'], $this->request->post['selected']),
				'edit'      	=> $this->url->link('extension/module/gentestimonials_list/edit', 'user_token=' . $this->session->data['user_token'] . '&testimonial_id=' . $result['testimonial_id'], true)
			);
		}
//посмотреть что заносится в selected и какая его функция
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

		$data['sort_user'] = $this->url->link('extension/module/gentestimonials_list', 'user_token=' . $this->session->data['user_token'] . '&sort=nd.user' . $url, true);
		$data['sort_date'] = $this->url->link('extension/module/gentestimonials_list', 'user_token=' . $this->session->data['user_token'] . '&sort=n.date' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $testimonials_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/gentestimonials_list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($testimonials_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($testimonials_total - $this->config->get('config_limit_admin'))) ? $testimonials_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $testimonials_total, ceil($testimonials_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;
	
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/gentestimonials_list', $data));

	}

	
	public function add() {
		$this->load->language('extension/module/gentestimonials');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/gentestimonials');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_module_gentestimonials->addTestimonial($this->request->post);

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

			$this->response->redirect($this->url->link('extension/module/gentestimonials_list', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}
	
	public function copy() {
		$this->load->language('extension/module/gentestimonials');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/gentestimonials');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $testimonial_id) {
				$this->model_extension_module_gentestimonials->copyTestimonial($testimonial_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';

			$this->response->redirect($this->url->link('extension/module/gentestimonials_list', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->index();
	}

	public function edit() {
		$this->load->language('extension/module/gentestimonials');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/gentestimonials');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_module_gentestimonials->editTestimonial($this->request->get['testimonial_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('extension/module/gentestimonials_list', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('extension/module/gentestimonials');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/gentestimonials');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $testimonial_id) {
				$this->model_extension_module_gentestimonials->deleteTestimonial($testimonial_id);
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

			$this->response->redirect($this->url->link('extension/module/gentestimonials_list', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->index();
	}

	private function getForm() { 

		$this->load->language('extension/module/gentestimonials');
	
		$this->load->model('extension/module/gentestimonials');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['heading_title'] = $this->language->get('heading_title');
	
		$data['text_form'] = !isset($this->request->get['testimonial_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		
		$data['entry_userLink'] = $this->language->get('entry_userLink');
		$data['entry_job_title'] = $this->language->get('entry_job_title');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_rating'] = $this->language->get('entry_rating');
		$data['entry_testimonial_title'] = $this->language->get('entry_testimonial_title');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_user'] = $this->language->get('entry_user');
		$data['entry_date'] = $this->language->get('entry_date');
		$data['entry_license'] = $this->language->get('entry_license');
		$data['entry_urlCompany'] = $this->language->get('entry_urlCompany');
		$data['text_license'] = $this->language->get('text_license');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_license'] = $this->language->get('tab_license');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['help_keyword'] = $this->language->get('help_keyword');	
		
		$testimonial_value = $this->model_extension_module_gentestimonials->getValue();
		$data['value2']=$testimonial_value['value2'];
	
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

		if (isset($this->error['user'])) {
			$data['error_user'] = $this->error['user'];
		} else {
			$data['error_user'] = array();
		}

		if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = array();
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
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
	
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/module/gentestimonials_list', 'user_token=' . $this->session->data['user_token'], true),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
	
		if (!isset($this->request->get['testimonial_id'])) {
			$data['action'] = $this->url->link('extension/module/gentestimonials_list/add', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/gentestimonials_list/edit', 'user_token=' . $this->session->data['user_token'] . '&testimonial_id=' . $this->request->get['testimonial_id'], true);
		}
	
		$data['cancel'] = $this->url->link('extension/module/gentestimonials_list', 'user_token=' . $this->session->data['user_token'], true);
	
		if ((isset($this->request->get['testimonial_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$testimonial_info = $this->model_extension_module_gentestimonials->getTestimonialsStory($this->request->get['testimonial_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
	
		if (isset($this->request->post['testimonial_description'])) {
			$data['testimonial_description'] = $this->request->post['testimonial_description'];
		} elseif (isset($this->request->get['testimonial_id'])) {
			$data['testimonial_description'] = $this->model_extension_module_gentestimonials->getTestimonialDescriptions($this->request->get['testimonial_id']);
		} else {
			$data['testimonial_description'] = array();
		}
		if (isset($this->request->post['testimonial_title'])) {
			$data['testimonial_title'] = $this->request->post['testimonial_title'];
		} elseif (isset($this->request->get['testimonial_id'])) {
			$data['testimonial_title'] = $this->model_extension_module_gentestimonials->getTestimonialDescriptions($this->request->get['testimonial_id']);
		} else {
			$data['testimonial_title'] = array();
		}
		
		if (isset($this->request->post['meta_keyword'])) {
			$data['meta_keyword'] = $this->request->post['meta_keyword'];
		} elseif (isset($this->request->get['testimonial_id'])) {
			$data['meta_keyword'] = $this->model_extension_module_gentestimonials->getTestimonialDescriptions($this->request->get['testimonial_id']);
		} else {
			$data['meta_keyword'] = array();
		}

		if (isset($this->request->post['userLink'])) {
       		$data['userLink'] = $this->request->post['userLink'];
		} elseif (isset($testimonial_info['userLink'])) {
			$data['userLink'] = $testimonial_info['userLink'];
		} else {
			$data['userLink'] = '';
		}
		
		if (isset($this->request->post['date'])) {
       		$data['date'] = $this->request->post['date'];
		} elseif (isset($testimonial_info['date'])) {
			$data['date'] = $testimonial_info['date'];
		} else {
			$data['date'] = date('Y-m-d');
		}
		
		if (isset($this->request->post['urlCompany'])) {
			$data['urlCompany'] = $this->request->post['urlCompany'];
		} elseif (!empty($testimonial_info)) {
			$data['urlCompany'] = $testimonial_info['urlCompany'];
		} else {
			$data['urlCompany'] = '';
		}
		
		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($testimonial_info)) {
			$data['sort_order'] = $testimonial_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}
		
		if (isset($this->request->post['rating'])) {
			$data['rating'] = $this->request->post['rating'];
		} elseif (!empty($testimonial_info)) {
			$data['rating'] = $testimonial_info['rating'];
		} else {
			$data['rating'] = '';
		}

		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($testimonial_info)) {
			$data['keyword'] = $testimonial_info['keyword'];
		} else {
			$data['keyword'] = '';
		}
	
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (isset($testimonial_info)) {
			$data['status'] = $testimonial_info['status'];
		} else {
			$data['status'] = '';
		}
	
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($testimonial_info)) {
			$data['image'] = $testimonial_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($testimonial_info) && is_file(DIR_IMAGE . $testimonial_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($testimonial_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
	
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/gentestimonials_form', $data));

	}	
	
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/module/gentestimonials')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		foreach ($this->request->post['testimonial_description'] as $language_id => $value) {
			if ((strlen($value['user']) < 3) || (strlen($value['user']) > 20)) {
				$this->error['user'][$language_id] = $this->language->get('error_user');
			}
		
			if (strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}			
		}
		
		$testimonial_value = $this->model_extension_module_gentestimonials->getValue();
		if ($this->request->post['license'] != $testimonial_value['value1']) {
				$this->error['license'][$language_id] = $this->language->get('error_license');
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
	
		return !$this->error;
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
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'image'      => $this->model_tool_image->resize($result['image'], 100, 100)
				);
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/module/gentestimonials')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	
		return !$this->error;
	}
	
	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'extension/module/gentestimonials')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
?>