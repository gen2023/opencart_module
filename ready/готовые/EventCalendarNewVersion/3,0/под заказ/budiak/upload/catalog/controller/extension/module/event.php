<?php
class ControllerExtensionModuleEvent extends Controller {
	public function index() {

		$this->load->model('extension/module/event');
        $this->load->language('extension/module/event');
		
		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
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

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 0;
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		
		
		
			$this->document->setTitle($this->language->get('heading_title'));
			$this->document->addLink($this->url->link('blog/blog'),'');

			$data['heading_title'] = $this->language->get('heading_title');
			$data['sun'] = $this->language->get('sun');
			$data['mon'] = $this->language->get('mon');
			$data['tue'] = $this->language->get('tue');
			$data['wed'] = $this->language->get('wed');
			$data['thu'] = $this->language->get('thu');
			$data['fri'] = $this->language->get('fri');
			$data['sat'] = $this->language->get('sat');
			$data['February'] = $this->language->get('February');
			$data['January'] = $this->language->get('January');
			$data['March'] = $this->language->get('March');
			$data['April'] = $this->language->get('April');
			$data['May'] = $this->language->get('May');
			$data['June'] = $this->language->get('June');
			$data['July'] = $this->language->get('July');
			$data['August'] = $this->language->get('August');
			$data['September'] = $this->language->get('September');
			$data['October'] = $this->language->get('October');
			$data['November'] = $this->language->get('November');
			$data['December'] = $this->language->get('December');
			$data['sun1'] = $this->language->get('sun1');
			$data['mon1'] = $this->language->get('mon1');
			$data['tue1'] = $this->language->get('tue1');
			$data['wed1'] = $this->language->get('wed1');
			$data['thu1'] = $this->language->get('thu1');
			$data['fri1'] = $this->language->get('fri1');
			$data['sat1'] = $this->language->get('sat1');
			$data['Feb'] = $this->language->get('Feb');
			$data['Jan'] = $this->language->get('Jan');
			$data['Mar'] = $this->language->get('Mar');
			$data['Apr'] = $this->language->get('Apr');
			$data['May1'] = $this->language->get('May1');
			$data['Jun'] = $this->language->get('Jun');
			$data['Jul'] = $this->language->get('Jul');
			$data['Aug'] = $this->language->get('Aug');
			$data['Sept'] = $this->language->get('Sept');
			$data['Oct'] = $this->language->get('Oct');
			$data['Nov'] = $this->language->get('Nov');
			$data['Dec'] = $this->language->get('Dec');
			$data['tooday'] = $this->language->get('tooday');
			$data['month'] = $this->language->get('month');
			$data['week'] = $this->language->get('week');
			$data['day'] = $this->language->get('day');

			$data['spisok_event'] = $this->language->get('spisok_event');
			
			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['events'] = array();

			$filter_data = array(
				'filter_filter'      => $filter,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

			$event_total = $this->model_extension_module_event->getTotalEvents($filter_data);

			$results = $this->model_extension_module_event->getEvents($filter_data);
			
			foreach ($results as $result) {
				$data['events'][] = array(
					'event_id'    => $result['event_id'],
					'title'       => $result['title'],
					'date_from'   => date($this->language->get('date_format_short'), strtotime($result['date_from'])),
					'date_to'     => date($this->language->get('date_format_short'), strtotime($result['date_to'])),
					'mindescription' => $result['mindescription'],
                    'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
					'href'        => $this->url->link('extension/module/event_detail', 'event_id=' . $result['event_id'] . $url),
					'explode_From'	  => explode('-',date("Y-m-d", strtotime($result['date_from']))),
					'explode_To'	  => explode('-',date("Y-m-d", strtotime($result['date_to'])))
				);
			}
			
			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('extension/module/event', '&sort=p.sort_order&order=ASC' . $url)
			);

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array(20, 50, 75, 100));

			sort($limits);
			
			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('extension/module/event', $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

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
			$pagination->total = $event_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_limit_admin');
			$pagination->url = $this->url->link('extension/module/event', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);
			
			$data['pagination'] = $pagination->render();			
            $data['button_read_more'] = $this->language->get('button_read_more');
            $data['text_empty'] = $this->language->get('text_empty');

			if ($limit=="" || $limit==0)
				$limit=$event_total;
			
			$data['results'] = sprintf($this->language->get('text_pagination'), ($event_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($event_total - $limit)) ? $event_total : ((($page - 1) * $limit) + $limit), $event_total, ceil($event_total / $limit));
			
			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;


			$this->document->setTitle($this->language->get('heading_title'));
			
			$data['heading_title'] = $this->language->get('heading_title');
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/module/event')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/module/event', $data));
			} else {
				$this->response->setOutput($this->load->view('extension/module/event', $data));
			}
	}
	
	public function saveChanges(){
		
		$this->load->model('extension/module/event');
        $this->load->language('extension/module/event');
		$this->load->model('tool/image');
		
		$json = array();
		
		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
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

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = '';
		}
		
		$filter_data = array(
			'filter_filter'    => $filter,
			'sort'             => $sort,
			'order'            => $order,
			'start'            => ($page - 1) * $limit,
			'limit'            => $limit
		);

		$results = $this->model_extension_module_event->getEvents($filter_data);
		
		foreach($results as $result){
			
			if (is_file(DIR_IMAGE . $result['p_image'])) {
				$image = $this->model_tool_image->resize($result['p_image'], 20, 20);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 20, 20);
			}
			
			$json[] = array(
				'event_id'    => $result['event_id'],
				'title'       => $result['title'],
				'date_from'   => date('Y/m/d',strtotime($result['date_from'])),
				'date_to'     => date('Y/m/d',strtotime($result['date_to'])),
				'time'        => $result['time_to'],
				'mindescription' => $result['mindescription'],
				'description' => strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')),
				'href'        => $this->url->link('extension/module/event_detail', 'event_id=' . $result['event_id']),
				'image'       => $image
			);
			
		}
		
		$this->response->setOutput(json_encode($json, true));
		
	}

}