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
		
		$event_setting = $this->model_extension_module_event->getEventSetting();
	
		$data['firstDay']=$event_setting['firstDay'];
		$data['dayMaxEvents']=$event_setting['dayMaxEvents'];
		if ($this->session->data['language']==='ru-ru'){
			$data['eventLocale']='ru';
		}
		elseif($this->session->data['language']==='uk-ua'){
			$data['eventLocale']='uk';
		}
		else {
			$data['eventLocale']='en';
		}
			
		$event_total = $this->model_extension_module_event->getTotalEvents($filter_data);

		$results = $this->model_extension_module_event->getEvents($filter_data);
//var_dump($results);
		foreach ($results as $result) {
			$data['events'][] = array(
				'event_id'    => $result['event_id'],
				'title'       => $result['event_title'],
				'date_from'   => date($this->language->get('d.m.Y'), strtotime($result['date_from'].'+1 days')),
				'date_to'     => date($this->language->get('d.m.Y'), strtotime($result['date_to'])),
				'mindescription' => html_entity_decode($result['mindescription'], ENT_QUOTES, 'UTF-8'),
				'description' => html_entity_decode($result['event_description'], ENT_QUOTES, 'UTF-8'),
				'href'        => $this->url->link('extension/module/event_detail', 'event_id=' . $result['event_id'] . $url),
				'color'		  =>$result['color'],
				'sort_order'  =>$result['sort_order']
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
		$pagination->url = $this->url->link('extension/module/event', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
		
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

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/module/event.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/module/event.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('extension/module/event.tpl', $data));
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
				'date_from'   => date('d.m.Y',strtotime($result['date_from'])),
				'date_to'     => date('d.m.Y',strtotime($result['date_to'])),
				'time'        => $result['time_to'],
				'mindescription' => strip_tags(html_entity_decode($result['mindescription'], ENT_QUOTES, 'UTF-8')),
				'description' => strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')),
				'href'        => $this->url->link('extension/module/event_detail', 'event_id=' . $result['event_id']),
				'image'       => $image,
				'color'		  => $result['color'] 
			);
			
		}
		//var_dump($data['pagination']);
		$this->response->setOutput(json_encode($json, true));
		
	}

}