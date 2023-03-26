<?php
class ControllerExtensionModuleEventList extends Controller {
	private $error = array();

	public function index(){
		$this->load->language('extension/module/event_list');

		$this->load->model('extension/module/event');

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
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('event'),
			'href' => $this->url->link('extension/module/event')
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
			'href' => $this->url->link('extension/module/event_list', $url)
		);
		
		$data['events'] = array();

		$filter_data = array(
			'sort' => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);

		$events_total = $this->model_extension_module_event->getTotalEvents();
		$events_list = $this->model_extension_module_event->getEvents($filter_data);
				
		if ($events_list) {

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');
			$data['text_empty'] = $this->language->get('text_empty');

			$data['button_grid'] = $this->language->get('button_grid');
			$data['button_list'] = $this->language->get('button_list');
			
			$data['event_date_to'] = $this->language->get('event_date_to');
			$data['event_date_from'] = $this->language->get('event_date_from');

            /*mmr*/
            $data['moneymaker2_catalog_default_view'] = $this->config->get('moneymaker2_catalog_layout_default');
            $data['moneymaker2_catalog_layout_switcher_hide'] = $this->config->get('moneymaker2_catalog_layout_switcher_hide');
            /*mmr*/

			$data['text_sort'] = $this->language->get('text_sort');
			$data['text_limit'] = $this->language->get('text_limit');

			$data['text_more'] = $this->language->get('text_more');

			$event_setting = array();

			if ($this->config->get('event_setting')) {
				$event_setting = $this->config->get('event_setting');
			}else{
				$event_setting['description_limit'] = '300';
				$event_setting['event_thumb_width'] = '220';
				$event_setting['event_thumb_height'] = '220';
			}

			foreach ($events_list as $result) {

				if($result['image']){
					$image = $this->model_tool_image->resize($result['image'], $event_setting['event_thumb_width'], $event_setting['event_thumb_height']);
				}else{
					$image = false;
				}
			
				$data['events_list'][] = array(
					'title' => $result['title'],
					'thumb' => $image,
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $event_setting['description_limit']),
					'href' => $this->url->link('extension/module/event_detail', 'event_id=' . $result['event_id']),
					'date_to' => date($this->language->get('date_format_short'), strtotime($result['date_to'])),
					'date_from' => date($this->language->get('date_format_short'), strtotime($result['date_from'])),
					'time_to'     => date('h:i',strtotime($result['time_to'])),
					'time_from'   => date('h:i',strtotime($result['time_from']))
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
			'href' => $this->url->link('extension/module/event_list', 'sort=nd.title&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text' => $this->language->get('text_title_desc'),
			'value' => 'nd.title-DESC',
			'href' => $this->url->link('extension/module/event_list', 'sort=nd.title&order=DESC' . $url)
		);

		$data['sorts'][] = array(
			'text' => $this->language->get('text_date_to_asc'),
			'value' => 'i.date_to-ASC',
			'href' => $this->url->link('extension/module/event_list', 'sort=i.date_to&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text' => $this->language->get('text_date_to_desc'),
			'value' => 'i.date_to-DESC',
			'href' => $this->url->link('extension/module/event_list', 'sort=i.date_to&order=DESC' . $url)
		);	
		
		$data['sorts'][] = array(
			'text' => $this->language->get('text_date_from_asc'),
			'value' => 'i.date_from-ASC',
			'href' => $this->url->link('extension/module/event_list', 'sort=i.date_from&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text' => $this->language->get('text_date_from_desc'),
			'value' => 'i.date_from-DESC',
			'href' => $this->url->link('extension/module/event_list', 'sort=i.date_from&order=DESC' . $url)
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
				'href' => $this->url->link('extension/module/event_detail', $url . '&limit=' . $value)
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
		$pagination->total = $events_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('extension/module/event_list', $url . '&page={page}');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($events_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($events_total - $limit)) ? $events_total : ((($page - 1) * $limit) + $limit), $events_total, ceil($events_total / $limit));

		// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
		if ($page == 1) {
			$this->document->addLink($this->url->link('extension/module/event_list', '', true), 'canonical');
		} elseif ($page == 2) {
			$this->document->addLink($this->url->link('extension/module/event_list', '', true), 'prev');
		} else {
			$this->document->addLink($this->url->link('extension/module/event_list', '&page=' . ($page - 1), true), 'prev');
		}

		if ($limit && ceil($events_total / $limit) > $page) {
			$this->document->addLink($this->url->link('extension/module/event_list', '&page=' . ($page + 1), true), 'next');
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

		$this->response->setOutput($this->load->view('extension/module/event_list', $data));
	}

}
