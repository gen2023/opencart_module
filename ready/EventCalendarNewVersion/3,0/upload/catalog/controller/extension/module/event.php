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
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);		
		
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addLink($this->url->link('blog/blog'),'');

		$data['heading_title'] = $this->language->get('heading_title');
		$data['button_read_more'] = $this->language->get('button_read_more');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['spisok_event'] = $this->language->get('spisok_event');
			
		$url = '';

		if (isset($this->request->get['filter'])) {
			$url .= '&filter=' . $this->request->get['filter'];
		}

		$filter_data = array(
			'filter_filter'      => $filter
		);
			
		$event_setting = $this->model_extension_module_event->getEventSetting();
		
		$data['textButton']=$event_setting['rightMenu'][$this->config->get('config_language_id')];
		$data['rightColumnMenu']=$event_setting['rightColumnMenu'];
		$data['initialView']=$event_setting['initialView'];		
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

		$results = $this->model_extension_module_event->getEvents($filter_data);
		$event_total = $this->model_extension_module_event->getTotalEvents();

		$data['events'] = array();
			
		foreach ($results as $result) {
			$data['events'][] = array(
				'event_id'			=> $result['event_id'],
				'title'				=> $result['title'],
				'date_from'			=> date($this->language->get('date_format_short'), strtotime($result['date_from'])),
				'date_to'			=> date($this->language->get('date_format_short'), strtotime($result['date_to'])),
				'time_to'     		=> $result['time_to'],
				'time_from'   		=> $result['time_from'],
				'mindescription'	=> $result['mindescription'],
                'description'		=> html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
				'href'				=> ($result['alternativeLink']) ? $result['alternativeLink'] : $this->url->link('extension/module/event_detail&event_id=' . $result['event_id'] . $url),
				'explode_From'		=> explode('-',date("Y-m-d", strtotime($result['date_from']))),
				'explode_To'		=> explode('-',date("Y-m-d", strtotime($result['date_to']))),
				'color'				=> $result['color'],
				'sort_order'		=> $result['sort_order']
			);
		}
			
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
				'date_from'   => date('d.m.Y',strtotime($result['date_from'])),
				'date_to'     => date('d.m.Y',strtotime($result['date_to'])),
				'time_to'     => $result['time_to'],
				'time_from'   => $result['time_from'],
				'mindescription' => strip_tags(html_entity_decode($result['mindescription'], ENT_QUOTES, 'UTF-8')),
				'description' => strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')),
				'href'        => $this->url->link('extension/module/event_detail', 'event_id=' . $result['event_id']),
				'image'       => $image,
				'color'		  => $result['color']
			);
			
		}
		
		$this->response->setOutput(json_encode($json, true));
		
	}

}