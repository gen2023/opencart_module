<?php
class ControllerExtensionModuleGalleryList extends Controller {
	private $error = array();

	public function index(){
		$this->load->language('extension/module/gallery_list');

		$this->load->model('extension/module/gallery');

		$this->load->model('tool/image');

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'n.date_from';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['continue'] = $this->url->link('common/home');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

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

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/gallery_list', $url)
		);
		
		$data['gallery'] = array();

		$filter_data = array(
			'sort' => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);

		$gallery_total = $this->model_extension_module_gallery->getTotalGallery();
		$gallery_list = $this->model_extension_module_gallery->getGallery($filter_data);
				
		if ($gallery_list) {

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');
			$data['text_empty'] = $this->language->get('text_empty');

			$data['button_grid'] = $this->language->get('button_grid');
			$data['button_list'] = $this->language->get('button_list');

            /*mmr*/
            $data['moneymaker2_catalog_default_view'] = $this->config->get('moneymaker2_catalog_layout_default');
            $data['moneymaker2_catalog_layout_switcher_hide'] = $this->config->get('moneymaker2_catalog_layout_switcher_hide');
            /*mmr*/

			$data['text_sort'] = $this->language->get('text_sort');
			$data['text_limit'] = $this->language->get('text_limit');

			$data['text_more'] = $this->language->get('text_more');

			$gallery_setting = array();

			if ($this->config->get('gallery_setting')) {
				$gallery_setting = $this->config->get('gallery_setting');
			}else{
				$gallery_setting['description_limit'] = '300';
				$gallery_setting['gallery_thumb_width'] = '220';
				$gallery_setting['gallery_thumb_height'] = '220';
			}

			foreach ($gallery_list as $result) {

				if($result['image']){
					$image = $this->model_tool_image->resize($result['image'], $gallery_setting['gallery_thumb_width'], $gallery_setting['gallery_thumb_height']);
				}else{
					$image = false;
				}
			
				$data['gallery_list'][] = array(
					'title' => $result['title'],
					'thumb' => $image,
					//'viewed' => sprintf($this->language->get('text_viewed'), $result['viewed']),
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES,
						'UTF-8')), 0, $gallery_setting['description_limit']),
					'href' => $this->url->link('extension/module/gallery_detail', 'gallery_id=' . $result['gallery_id']),
					'posted' => date($this->language->get('date_format_short'), strtotime($result['date_from']))
				);
			}

		}

		$url = '';

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['sorts'] = array();

		$data['sorts'][] = array(
			'text' => $this->language->get('text_title_asc'),
			'value' => 'nd.title-ASC',
			'href' => $this->url->link('extension/module/gallery_list', 'sort=nd.title&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text' => $this->language->get('text_title_desc'),
			'value' => 'nd.title-DESC',
			'href' => $this->url->link('extension/module/gallery_list', 'sort=nd.title&order=DESC' . $url)
		);

		$data['sorts'][] = array(
			'text' => $this->language->get('text_date_asc'),
			'value' => 'n.date_from-ASC',
			'href' => $this->url->link('extension/module/gallery_list', 'sort=n.date_from&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text' => $this->language->get('text_date_desc'),
			'value' => 'n.date_from-DESC',
			'href' => $this->url->link('extension/module/gallery_list', 'sort=n.date_from&order=DESC' . $url)
		);		

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$data['limits'] = array();

		$limits = array_unique(array($this->config->get($this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

		sort($limits);

		foreach ($limits as $value) {
			$data['limits'][] = array(
				'text' => $value,
				'value' => $value,
				'href' => $this->url->link('extension/module/gallery_detail', $url . '&limit=' . $value)
			);
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}		

		$pagination = new Pagination();
		$pagination->total = $gallery_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('extension/module/gallery_list', $url . '&page={page}');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($gallery_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($gallery_total - $limit)) ? $gallery_total : ((($page - 1) * $limit) + $limit), $gallery_total, ceil($gallery_total / $limit));

		// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
		if ($page == 1) {
			$this->document->addLink($this->url->link('extension/module/gallery_list', '', true), 'canonical');
		} elseif ($page == 2) {
			$this->document->addLink($this->url->link('extension/module/gallery_list', '', true), 'prev');
		} else {
			$this->document->addLink($this->url->link('extension/module/gallery_list', '&page=' . ($page - 1), true), 'prev');
		}

		if ($limit && ceil($gallery_total / $limit) > $page) {
			$this->document->addLink($this->url->link('extension/module/gallery_list', '&page=' . ($page + 1), true), 'next');
		}

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/module/gallery_list', $data));
	}

}
