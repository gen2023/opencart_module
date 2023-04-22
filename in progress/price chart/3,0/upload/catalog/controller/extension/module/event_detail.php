<?php
class ControllerExtensionModuleEventDetail extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/event_detail');
		
		$data['event'] = $this->language->get('event');
		$data['event_list'] = $this->language->get('event_list');
		$data['event_date_to'] = $this->language->get('event_date_to');
		$data['event_date_from'] = $this->language->get('event_date_from');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
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

		if (isset($this->request->get['event_id'])) {
			$event_id = (int)$this->request->get['event_id'];
		} else {
			$event_id = 0;
		}

		$this->load->model('extension/module/event');
		$this->load->model('tool/image');

		$event_info = $this->model_extension_module_event->getEvent($event_id);
	
		if ($event_info) {
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
				'text' => $data['event'],
				'href' => $this->url->link('extension/module/event')
			);
			
			$data['breadcrumbs'][] = array(
				'text' => $data['event_list'],
				'href' => $this->url->link('extension/module/event_list')
			);
			
			$data['breadcrumbs'][] = array(
				'text' => $event_info['title'],
				'href' => $this->url->link('extension/module/event_detail', $url . '&event_id=' . $this->request->get['event_id'])
			);

			$this->document->addLink($this->url->link('extension/module/event_detail', 'event_id=' . $this->request->get['event_id']), 'canonical');
			
			if (is_file(DIR_IMAGE . $event_info['image'])) {
				$data['image'] = $this->model_tool_image->resize($event_info['image'], 200, 200);
			} else {
				$data['image'] = $this->model_tool_image->resize('no_image.png', 200, 200);
			}
			//var_dump($event_info);
			$this->document->setTitle($event_info['title']);
			$data['heading_title'] = $event_info['title'];
            $data['date_To'] = date("d-m-Y", strtotime($event_info['date_to']));
			$data['date_From'] = date("d-m-Y", strtotime($event_info['date_from']));
			$data['time_to'] = date('h:i',strtotime($event_info['time_to']));
			$data['time_from'] = date('h:i',strtotime($event_info['time_from']));
			
			$data['event_id'] = (int)$this->request->get['event_id'];

			$data['description'] = html_entity_decode($event_info['description'], ENT_QUOTES, 'UTF-8');

			$products[]=$event_info["product_id"];
			$this->load->model('catalog/product');

			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], 200, 200);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', 200, 200);
					}
					$data['product'][] = array(
						'product_id' => $product_info['product_id'],
						'name'       => $product_info['name'],
						'image'		 => $image,
						'href'        => $this->url->link('product/product', '&product_id=' . $product_info['product_id'] . $url)
					);
				}
			}

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/module/event_detail')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/module/event_detail', $data));
			} else {
				$this->response->setOutput($this->load->view('extension/module/event_detail', $data));
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

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/module/event_detail')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/module/event_detail', $data));
			} else {
				$this->response->setOutput($this->load->view('extension/module/event_detail', $data));
			}
		}
	}

}
