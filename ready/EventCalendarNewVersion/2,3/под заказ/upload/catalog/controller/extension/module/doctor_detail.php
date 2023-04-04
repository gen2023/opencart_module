<?php
class ControllerExtensionModuleDoctorDetail extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/doctor'); 
		
		$this->load->model('extension/module/doctor');
		
		$this->load->model('tool/image');
		
		
		$data['text_more'] = $this->language->get('text_more');
		$data['doctor_list'] = $this->language->get('doctor_list');
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $data['doctor_list'],
			'href' => $this->url->link('extension/module/doctor_list')
		);

		if (isset($this->request->get['search'])) {
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
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

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

		}

		if (isset($this->request->get['doctor_id'])) {
			$doctor_id = (int)$this->request->get['doctor_id'];
		} else {
			$doctor_id = 0;
		}

		//$event_setting = $this->model_extension_module_event->getEventSetting();
		//var_dump($event_setting);
		//$data['event_setting'] = $event_setting;
		
		$doctor_info = $this->model_extension_module_doctor->getDoctor($doctor_id);
		
//var_dump($doctor_event);
		if ($doctor_info) {
	
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
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

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			$data['breadcrumbs'][] = array(
				'text' => $doctor_info['title'],
				'href' => $this->url->link('extension/module/doctor_detail', $url . '&doctor_id=' . $this->request->get['doctor_id'])
			);

			$this->document->addLink($this->url->link('extension/module/event_detail', 'doctor_id=' . $this->request->get['doctor_id']), 'canonical');
			
			$this->document->setTitle($doctor_info['title']);
			$data['heading_title'] = $doctor_info['title'];
			

			if ($doctor_info['image']) {
					$image = $this->model_tool_image->resize($doctor_info['image'], 100, 100);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', 100, 100);
				}
			$data['doctor'][] = array(
				'doctor_id' 	=> $doctor_info['doctor_id'],
				'title'       	=> $doctor_info['title'],
				'description'	=> utf8_substr(strip_tags(html_entity_decode($doctor_info['description'], ENT_QUOTES,
				'UTF-8')), 0, 100),
				'post'       	=> $doctor_info['post'],
				'image'		 	=> $image,
				'href'        	=> $this->url->link('information/doctor', '&doctor_id=' . $doctor_info['doctor_id'])
			);
					
			$doctor_event = $this->model_extension_module_doctor->getDoctorEvent($doctor_id);

			if ($doctor_event){
				foreach ($doctor_event as $result) {

					if($result['image']){
						$image = $this->model_tool_image->resize($result['image'], 100, 100);
					}else{
						$image = false;
					}
				
					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}
				
					$data['events'][] = array(
						'event_id' 	=> $result['event_id'],
						'title'       	=> $result['title'],
						'title1'       	=> $result['title1'],
						'title3'       	=> $result['title3'],
						'description'	=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES,
						'UTF-8')), 0, 100),
						'thumb'		 	=> $image,
						'price'		 	=> $price,
						'date_to'		=> $result['date_to'],
						'date_from'		=> $result['date_from'],
						'time_to'		=> $result['time_to'],
						'time_from'		=> $result['time_from'],
						'mindescription'=> $result['mindescription'],
						'href'        	=> $this->url->link('extension/module/event_detail', '&event_id=' . $result['event_id'])
					);
					
							
				}
			}
		
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/module/doctor_detail.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/module/doctor_detail.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('extension/module/doctor_detail.tpl', $data));
			}
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
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

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/module/doctor_detail.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/module/doctor_detail.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('extension/module/doctor_detail.tpl', $data));
			}
			
			$this->response->setOutput($this->load->view('error/not_found.tpl', $data));
		}
	}

}
