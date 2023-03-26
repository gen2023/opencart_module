<?php
class ControllerExtensionModuleGalleryDetail extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/gallery_detail');
		
		$data['gallery'] = $this->language->get('gallery');
		$data['gallery_list'] = $this->language->get('gallery_list');

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

		if (isset($this->request->get['gallery_id'])) {
			$gallery_id = (int)$this->request->get['gallery_id'];
		} else {
			$gallery_id = 0;
		}

		$this->load->model('extension/module/gallery');
		$this->load->model('tool/image');
		
		$gallery_setting = $this->model_extension_module_gallery->getGallerySetting();
		//var_dump($gallery_setting);
		$data['gallery_setting'] = $gallery_setting;
		
		$gallery_info = $this->model_extension_module_gallery->getGallery($gallery_id);
	//var_dump($gallery_info);
		if ($gallery_info) {
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
				'text' => $data['gallery'],
				'href' => $this->url->link('extension/module/gallery')
			);
			
			$data['breadcrumbs'][] = array(
				'text' => $data['gallery_list'],
				'href' => $this->url->link('extension/module/gallery_list')
			);
			
			$data['breadcrumbs'][] = array(
				'text' => $gallery_info['title'],
				'href' => $this->url->link('extension/module/gallery_detail', $url . '&gallery_id=' . $this->request->get['gallery_id'])
			);

			$this->document->addLink($this->url->link('extension/module/gallery_detail', 'gallery_id=' . $this->request->get['gallery_id']), 'canonical');
			
			if (is_file(DIR_IMAGE . $gallery_info['image'])) {
				$data['image'] = $this->model_tool_image->resize($gallery_info['image'], 100, 100);
			} else {
				$data['image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
			}
			
			$this->document->setTitle($gallery_info['title']);
			$data['heading_title'] = $gallery_info['title'];
            $data['date'] = date("d-m-Y", strtotime($gallery_info['date_from']));
			
			$data['gallery_id'] = (int)$this->request->get['gallery_id'];

			$data['description'] = html_entity_decode($gallery_info['description'], ENT_QUOTES, 'UTF-8');


			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/module/gallery_detail.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/module/gallery_detail.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('extension/module/gallery_detail.tpl', $data));
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

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/module/gallery_detail.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/module/gallery_detail.tpl.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('extension/module/gallery_detail.tpl', $data));
			}
			
			$this->response->setOutput($this->load->view('error/not_found.tpl', $data));
		}
	}

}
