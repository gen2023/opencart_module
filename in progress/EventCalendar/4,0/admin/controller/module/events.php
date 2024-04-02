<?php
namespace Opencart\Admin\Controller\Extension\GenModule\Module;
/**
 * Class Template Module
 *
 * @package Opencart\Admin\Controller\Extension\GenModule\Module
 */
class Events extends \Opencart\System\Engine\Controller {

	/**
	 * @return void
	 */
	public function install(): void {
		if ($this->user->hasPermission('modify', 'extension/gen_module/module/events')) {
			$this->load->model('extension/gen_module/module/events');

			$this->model_extension_gen_module_module_events->install();
		}
	}

	/**
	 * @return void
	 */
	public function uninstall(): void {
		if ($this->user->hasPermission('modify', 'extension/gen_module/module/events')) {
			$this->load->model('extension/gen_module/module/events');

			$this->model_extension_gen_module_module_events->uninstall();
		}
	}

	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->model('extension/gen_module/module/events');
		$this->getList();
	}

	/**
	 * @return void
	 */
	public function getList(): void {

		$this->load->language('extension/gen_module/module/events');
		
		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'nd.title';
		}

		if (isset($this->request->get['order'])) {
			$order = (string)$this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/gen_module/module/events', 'user_token=' . $this->session->data['user_token'] . $url),
			'separator' => ' :: '
		];

		$events_setting = $this->model_extension_gen_module_module_events->getValue2();
		if ($events_setting){
			$data['add'] = $this->url->link('extension/gen_module/module/events.add', 'user_token=' . $this->session->data['user_token']);
			$data['delete'] = $this->url->link('extension/gen_module/module/events.delete', 'user_token=' . $this->session->data['user_token']);
			$data['copy'] = $this->url->link('extension/gen_module/module/events.copy', 'user_token=' . $this->session->data['user_token'] . $url);
			$data['hideEvent'] = $this->url->link('extension/gen_module/module/events.hideEvent', 'user_token=' . $this->session->data['user_token'] . $url);
		}else{
			$data['add'] = $this->url->link('extension/gen_module/module.events', 'user_token=' . $this->session->data['user_token']);
			$data['delete'] = $this->url->link('extension/gen_module/module.events', 'user_token=' . $this->session->data['user_token']);
			$data['copy'] = $this->url->link('extension/gen_module/module.events', 'user_token=' . $this->session->data['user_token'] . $url);
			$data['hideEvent'] = $this->url->link('extension/gen_module/module.events', 'user_token=' . $this->session->data['user_token'] . $url);
			$data['error_licence'] = $this->language->get('error_license_setting');
		}


		$data['action'] = $this->url->link('extension/gen_module/module/events.index', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['setting'] = $this->url->link('extension/gen_module/module/events.setting', 'user_token=' . $this->session->data['user_token']);
	
		$events_total = $this->model_extension_gen_module_module_events->getTotalEvents();
	
		$this->load->model('tool/image');
	
		$data['events'] = array();

		// var_dump($this->config->get('config_limit_admin'));die;
	
			$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * 10,
			'limit' => 10
		);

		$results = $this->model_extension_gen_module_module_events->getEventsList($filter_data);

    	foreach ($results as $result) {
		
			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', 40, 40);
			}
		
			$data['events'][] = array(
				'event_id'     	=> $result['event_id'],
				'title'       	=> $result['title'],
				'image'      	=> $image,
				'date_from'  	=> date($this->language->get('date_format_short'), strtotime($result['date_from'])),
				'date_to'  	=> date($this->language->get('date_format_short'), strtotime($result['date_to'])),
				'status'     	=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'    	=> isset($this->request->post['selected']) && in_array($result['event_id'], $this->request->post['selected']),
				'edit'      	=> $this->url->link('extension/module/events/edit', 'user_token=' . $this->session->data['user_token'] . '&event_id=' . $result['event_id'], true)
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

		$data['sort_title'] = $this->url->link('extension/module/events', 'user_token=' . $this->session->data['user_token'] . '&sort=nd.title' . $url);
		$data['sort_date_to'] = $this->url->link('extension/module/events', 'user_token=' . $this->session->data['user_token'] . '&sort=n.date_to' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $events_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('extension/gen_module/module/events.index', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($events_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($events_total - $this->config->get('config_pagination_admin'))) ? $events_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $events_total, ceil($events_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
	
		$this->response->setOutput($this->load->view('extension/gen_module/module/events_list', $data));

	}

	/**
	 * @return void
	 */
	public function setting() {	

		$this->load->language('extension/gen_module/module/events');

		$this->document->setTitle($this->language->get('text_events_setting'));

		$this->load->model('extension/gen_module/module/events');
		$this->load->model('localisation/language');
		$val=$_SERVER['HTTP_HOST'];
		$val=strlen($val);

		$data['heading_title'] = $this->language->get('text_events_setting');

		$data['breadcrumbs'] = array();
	
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']),
			'text'      => $this->language->get('text_home'),
		);
	
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/gen_module/module/events', 'user_token=' . $this->session->data['user_token']),
			'text'      => $this->language->get('heading_title'),
		);

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/gen_module/module/events/setting', 'user_token=' . $this->session->data['user_token']),
			'text'      => $this->language->get('text_events_setting'),
		);

		$data['save'] = $this->url->link('extension/gen_module/module/events.save_setting', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('extension/gen_module/module/events', 'user_token=' . $this->session->data['user_token']);

		$setting = $this->model_extension_gen_module_module_events->getEventsSetting();
		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$data['rightMenu']=$setting['rightMenu'];	
		$data['Monday'] = $this->language->get('Monday');
		$data['Tuesday'] = $this->language->get('Tuesday');
		$data['Wednesday'] = $this->language->get('Wednesday');
		$data['Thursday'] = $this->language->get('Thursday');
		$data['Friday'] = $this->language->get('Friday');
		$data['Saturday'] = $this->language->get('Saturday');
		$data['Sunday'] = $this->language->get('Sunday');	
		$data['dayGridMonth'] = $this->language->get('dayGridMonth');
		$data['timeGridWeek'] = $this->language->get('timeGridWeek');
		$data['timeGridDay'] = $this->language->get('timeGridDay');
		$data['listYear'] = $this->language->get('listYear');
		$data['listMonth'] = $this->language->get('listMonth');
		$data['listDay'] = $this->language->get('listDay');
		$data['listWeek'] = $this->language->get('listWeek');
		$data['text_editNameRightMenu'] = $this->language->get('text_editNameRightMenu');
		$data['title_form'] = $this->language->get('text_events_setting_update');

		$data['days']=[
			1 => $data['Monday'],
			2 => $data['Tuesday'],
			3 => $data['Wednesday'],
			4 => $data['Thursday'],
			5 => $data['Friday'],
			6 => $data['Saturday'],
			7 => $data['Sunday']
		];
		
		$data['listOptions']=[
			'dayGridMonth'=>$data['dayGridMonth'],
			'timeGridWeek'=>$data['timeGridWeek'],
			'timeGridDay'=>$data['timeGridDay'],
			'listYear'=>$data['listYear'],
			'listMonth'=>$data['listMonth'],
			'listDay'=>$data['listDay'],
			'listWeek'=>$data['listWeek']
		];

		$data['stores'] = [];
		
		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		];

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = [
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			];
		}
		
		if(isset($setting['status'])){
			$data['status'] = $setting['status'];
		}else{
			$data['status'] = 0;
		}
		
		if($setting['event_share']){
			$data['event_share'] = $setting['event_share'];
		}else{
			$data['event_share'] = '';
		}
		
		if(isset($setting['events_url'])){
			$data['events_url'] = $setting['events_url'];
		}else{
			$data['events_url'] = '';
		}
	
		if(isset($setting['eventslist_url'])){
			$data['eventslist_url'] = $setting['eventslist_url'];
		}else{
			$data['eventslist_url'] = '';
		}
		
		if(isset($setting['eventsDetail_url'])){
			$data['eventsDetail_url'] = $setting['eventsDetail_url'];
		}else{
			$data['eventsDetail_url'] = '';
		}
		
		if(isset($setting['firstDay'])){
			$data['event_firstDays'] = $setting['firstDay'];
		}else{
			$data['event_firstDays'] = 1;
		}
		
		if(isset($setting['dayMaxEvents'])){
			$data['dayMaxEvents'] = $setting["dayMaxEvents"];
		}else{
			$data['dayMaxEvents'] = 0;
		}
		
		if(isset($setting['initialView'])){
			$data['initialView'] = $setting["initialView"];
		}else{
			$data['initialView'] = 'dayGridMonth';
		}
		
		if(isset($setting['rightColumnMenu'])){			
			$data['rightColumnMenu'] = explode(",", $setting['rightColumnMenu']);
		}else{
			$data['rightColumnMenu'] = array();
		}
		
		if(isset($setting['value'])){
			$data['value'] = $setting["value"];
		}else{
			$data['value'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/gen_module/module/event_setting', $data));
	}

	/**
	 * @return void
	 */

	 public function add() {
		$this->load->language('extension/gen_module/module/events');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/gen_module/module/events');

		$data['save'] = $this->url->link('extension/gen_module/module/events.saveForm', 'user_token=' . $this->session->data['user_token']);

		$this->getForm();
	}

	/**
	 * @return void
	 */
	private function getForm() { 

		$this->load->language('extension/gen_module/module/events');
	
		$this->load->model('extension/gen_module/module/events');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['heading_title'] = $this->language->get('heading_title');
	
		// $data['text_form'] = !isset($this->request->get['event_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		// $data['text_default'] = $this->language->get('text_default');
		// $data['text_enabled'] = $this->language->get('text_enabled');
		// $data['text_disabled'] = $this->language->get('text_disabled');
    // 	$data['text_image_manager'] = $this->language->get('text_image_manager');
		// $data['text_browse'] = $this->language->get('text_browse');
		// $data['text_clear'] = $this->language->get('text_clear');
	
		// $data['text_select_all'] = $this->language->get('text_select_all');
		// $data['text_unselect_all'] = $this->language->get('text_unselect_all');
		// $data['column_date_added'] = $this->language->get('column_date_added');
	
		// $data['entry_title'] = $this->language->get('entry_title');
		// $data['entry_meta_title'] = $this->language->get('entry_meta_title');
		// $data['entry_meta_h1'] = $this->language->get('entry_meta_h1');
		// $data['entry_meta_description'] = $this->language->get('entry_meta_description');
		// $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		// $data['entry_mindescription'] = $this->language->get('entry_mindescription');
		// $data['entry_description'] = $this->language->get('entry_description');
		// $data['entry_date_from'] = $this->language->get('entry_date_from');
		// $data['entry_date_to'] = $this->language->get('entry_date_to');
		// $data['entry_store'] = $this->language->get('entry_store');
		// $data['entry_keyword'] = $this->language->get('entry_keyword');
		// $data['entry_alternativeLink'] = $this->language->get('entry_alternativeLink');
		// $data['entry_image'] = $this->language->get('entry_image');
		// $data['entry_status'] = $this->language->get('entry_status');
		// $data['entry_product'] = $this->language->get('entry_product');
		// $data['entry_event_color'] = $this->language->get('entry_event_color');
		// $data['entry_sort_order'] = $this->language->get('entry_sort_order');
		// $data['entry_time_to'] = $this->language->get('entry_time_to');
		// $data['entry_time_from'] = $this->language->get('entry_time_from');
	
		// $data['button_save'] = $this->language->get('button_save');
		// $data['button_cancel'] = $this->language->get('button_cancel');
	
		// $data['tab_general'] = $this->language->get('tab_general');
		// $data['tab_data'] = $this->language->get('tab_data');

		// $data['help_keyword'] = $this->language->get('help_keyword');
		// $data['help_product'] = $this->language->get('help_product');
		// $data['help_event_color'] = $this->language->get('help_event_color');		
	
		// $data['user_token'] = $this->session->data['user_token'];
		
		$data['breadcrumbs'] = array();
	
		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/gen_module/module/events', 'user_token=' . $this->session->data['user_token']),
			'text'      => $this->language->get('heading_title'),
		);

		$event_value = $this->model_extension_gen_module_module_events->getValue2();
		if (!$event_value){
			$data['error_warning'] = $this->language->get('error_license_setting');
			$data['action'] = $this->url->link('extension/gen_module/module/events', 'user_token=' . $this->session->data['user_token']);
		} else {
			if (!isset($this->request->get['event_id'])) {
				$data['action'] = $this->url->link('extension/gen_module/module/events.add', 'user_token=' . $this->session->data['user_token']);
			} else {
				$data['action'] = $this->url->link('extension/gen_module/module/events.edit', 'user_token=' . $this->session->data['user_token'] . '&event_id=' . $this->request->get['event_id']);
			}
		}
	
		$data['cancel'] = $this->url->link('extension/gen_module/module/events', 'user_token=' . $this->session->data['user_token']);
	
		if ((isset($this->request->get['event_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$events_info = $this->model_extension_gen_module_module_events->getEventsStory($this->request->get['event_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
	
		if (isset($this->request->post['events_description'])) {
			$data['events_description'] = $this->request->post['events_description'];
		} elseif (isset($this->request->get['event_id'])) {
			$data['events_description'] = $this->model_extension_gen_module_module_events->getEventsDescriptions($this->request->get['event_id']);
		} else {
			$data['events_description'] = array();
		}
		
		if (isset($this->request->post['meta_keyword'])) {
			$data['meta_keyword'] = $this->request->post['meta_keyword'];
		} elseif (isset($this->request->get['event_id'])) {
			$data['meta_keyword'] = $this->model_extension_gen_module_module_events->getEventsDescriptions($this->request->get['event_id']);
		} else {
			$data['meta_keyword'] = array();
		}

		if (isset($this->request->post['alternativeLink'])) {
       		$data['alternativeLink'] = $this->request->post['alternativeLink'];
		} elseif (isset($events_info['alternativeLink'])) {
			$data['alternativeLink'] = $events_info['alternativeLink'];
		} else {
			$data['alternativeLink'] = '';
		}
		
		if (isset($this->request->post['date_from'])) {
       		$data['date_from'] = $this->request->post['date_from'];
		} elseif (isset($events_info['date_from'])) {
			$data['date_from'] = $events_info['date_from'];
		} else {
			$data['date_from'] = date('Y-m-d');
		}

		if (isset($this->request->post['date_to'])) {
       		$data['date_to'] = $this->request->post['date_to'];
		} elseif (isset($events_info['date_to'])) {
			$data['date_to'] = $events_info['date_to'];
		} else {
			$data['date_to'] = date('Y-m-d');
		}
		
		if (isset($this->request->post['time_to'])) {
       		$data['time_to'] = $this->request->post['time_to'];
		} elseif (isset($events_info['time_to'])) {
			$data['time_to'] = $events_info['time_to'];
		} else {
			$data['time_to'] = date('00:00');
		}
		
		if (isset($this->request->post['time_from'])) {
       		$data['time_from'] = $this->request->post['time_from'];
		} elseif (isset($events_info['time_from'])) {
			$data['time_from'] = $events_info['time_from'];
		} else {
			$data['time_from'] = date('00:00');
		}
		
		if (isset($this->request->post['event_color'])) {
			$data['event_color'] = $this->request->post['event_color'];
		} elseif (!empty($events_info)) {
			$data['event_color'] = $events_info['color'];
		} else {
			$data['event_color'] = '';
		}
		
		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($events_info)) {
			$data['sort_order'] = $events_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$data['products'] = array();

		if (!empty($this->request->post['product'])) {
			$products = $this->request->post['product'];
		} elseif (!empty($events_info['product_id'])) {			
			$products = $events_info['product_id'];
		} else {
			$products = array();
		}
		
		if (!empty($events_info['product_id'])){
			$product_info = $this->model_catalog_product->getProduct($events_info['product_id']);
				
			if ($product_info) {
				$data['products'] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name'],
					'image'		 =>	$this->model_tool_image->resize($product_info['image'], 100, 100)
				);
			}
		}
		
		$data['stores'] = [];
		
		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		];

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = [
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			];
		}
	
		if (isset($this->request->post['events_store'])) {
			$data['events_store'] = $this->request->post['events_store'];
		} elseif (isset($events_info)) {
			$data['events_store'] = $this->model_extension_gen_module_module_events->getEventsStores($this->request->get['event_id']);
		} else {
			$data['events_store'] = array(0);
		}
	
		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($events_info)) {
			$data['keyword'] = $events_info['keyword'];
		} else {
			$data['keyword'] = '';
		}
	
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (isset($events_info)) {
			$data['status'] = $events_info['status'];
		} else {
			$data['status'] = '';
		}
		
		if (isset($this->request->post['repeatEvent'])) {
			$data['repeatEvent'] = $this->request->post['repeatEvent'];
		} elseif (isset($events_info)) {
			$data['repeatEvent'] = $events_info['repeatEvent'];
		} else {
			$data['repeatEvent'] = '';
		}
	
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($events_info)) {
			$data['image'] = $events_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($events_info) && is_file(DIR_IMAGE . $events_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($events_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
	
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/gen_module/module/events_form', $data));

	}

	/**
	 * @return void
	 */
	public function saveForm(): void {
		$this->load->language('extension/gen_module/module/events');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/gen_module/module/events')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('extension/gen_module/module/events');

			$this->model_extension_gen_module_module_events->addEvents($this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

		/**
	 * @return void
	 */
	public function save_setting(): void {
		
		$this->load->language('extension/gen_module/module/events');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/gen_module/module/events')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('extension/gen_module/module/events');

			$this->model_extension_gen_module_module_events->setEventsSetting($this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}


/*

	
	public function copy() {
		$this->load->language('extension/gen_module/module/events');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/gen_module/module/events');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $event_id) {
				$this->model_extension_gen_module_module_events->copyEvent($event_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';

			$this->response->redirect($this->url->link('extension/module/events', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	public function edit() {
		$this->load->language('extension/gen_module/module/events');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/gen_module/module/events');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_gen_module_module_events->editEvents($this->request->get['event_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('extension/module/events', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('extension/gen_module/module/events');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/gen_module/module/events');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $event_id) {
				$this->model_extension_gen_module_module_events->deleteEvents($event_id);
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

			$this->response->redirect($this->url->link('extension/module/events', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	public function hideEvent() {
		$this->load->model('extension/gen_module/module/events');
		$this->load->language('extension/gen_module/module/events');
		
		$result=$this->model_extension_gen_module_module_events->setHideOldEvent();		

		$this->session->data['success'] = sprintf($this->language->get('text_success_view'),$result);
		
		$url='';
		
		$this->response->redirect($this->url->link('extension/module/events', 'user_token=' . $this->session->data['user_token'] . $url, true));
	}


*/