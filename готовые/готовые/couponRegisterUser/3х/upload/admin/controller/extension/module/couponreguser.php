<?php
class ControllerExtensionModuleCouponreguser extends Controller {
	public function install() {
		$this->load->model('setting/event');
		$this->model_setting_event->addEvent('mail_customer_coupon_register', 'catalog/model/account/customer/addCustomer/after', 'mail/couponreguser',1);
	}
	public function uninstall() {
		$this->load->model('setting/event');
		$this->model_setting_event->deleteEvent('mail_customer_coupon_register');
	}
	
	private $error = array();

	public function index() {
		$this->load->language('extension/module/couponreguser'); 

		//$this->load->model('extension/module/couponreguser');
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) { 
			
			$this->model_setting_setting->editSetting('module_couponreguser', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

         // ваши переменные
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');		

		$data['entry_status'] = $this->language->get('entry_status');

        // если метод validate вернул warning, передадим его представлению
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

        // далее идет формирование массива breadcrumbs (хлебные крошки)
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/couponreguser', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/couponreguser', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		$module_setting=$this->model_setting_setting->getSetting('module_couponreguser');
		
		if (isset($this->request->post['module_couponreguser_mail'])) {
			$data['module_couponreguser_mail'] = $this->request->post['module_couponreguser_mail'];
		} elseif ($module_setting){
			$data['module_couponreguser_mail'] = $module_setting['module_couponreguser_mail'];
		} else {
			$data['module_couponreguser_mail']='';
		}
		
        if (isset($this->request->post['module_couponreguser_type'])) {
			$data['module_couponreguser_type'] = $this->request->post['module_couponreguser_type'];
		} elseif ($module_setting){
			$data['module_couponreguser_type'] = $module_setting['module_couponreguser_type'];
		} else{
			$data['module_couponreguser_type']='';
		}
		
		if (isset($this->request->post['module_couponreguser_status'])) {
			$data['module_couponreguser_status'] = $this->request->post['module_couponreguser_status'];
		} elseif ($module_setting){
			$data['module_couponreguser_status'] = $module_setting['module_couponreguser_status'];
		} else {
			$data['module_couponreguser_status']='';
		}

		if (isset($this->request->post['module_couponreguser_discount'])) {
			$data['module_couponreguser_discount'] = $this->request->post['module_couponreguser_discount'];
		} elseif ($module_setting){
				$data['module_couponreguser_discount'] = $module_setting['module_couponreguser_discount'];
		} else{
			$data['module_couponreguser_discount']='';
		}
		
		if (isset($this->request->post['module_couponreguser_total'])) {
			$data['module_couponreguser_total'] = $this->request->post['module_couponreguser_total'];
		} elseif ($module_setting){
			$data['module_couponreguser_total'] = $module_setting['module_couponreguser_total'];
		} else{
			$data['module_couponreguser_total']='';
		}
		
		if (isset($this->request->post['module_couponreguser_uses_count_day'])) {
			$data['module_couponreguser_uses_count_day'] = $this->request->post['module_couponreguser_uses_count_day'];
		} elseif ($module_setting){
			$data['module_couponreguser_uses_count_day'] = $module_setting['module_couponreguser_uses_count_day'];
		} else{
			$data['module_couponreguser_uses_count_day']=14;
		}
		
		if (isset($this->request->post['module_couponreguser_uses_total'])) {
			$data['module_couponreguser_uses_total'] = $this->request->post['module_couponreguser_uses_total'];
		} elseif ($module_setting){
			$data['module_couponreguser_uses_total'] = $module_setting['module_couponreguser_uses_total'];
		} else{
			$data['module_couponreguser_uses_total']=1;
		}
		
		if (isset($this->request->post['module_couponreguser_logged'])) {
			$data['module_couponreguser_logged'] = $this->request->post['module_couponreguser_logged'];
		} elseif ($module_setting){
			$data['module_couponreguser_logged'] = $module_setting['module_couponreguser_logged'];
		} else{
			$data['module_couponreguser_logged']='';
		}
		
		if (isset($this->request->post['module_couponreguser_shipping'])) {
			$data['module_couponreguser_shipping'] = $this->request->post['module_couponreguser_shipping'];
		} elseif ($module_setting){
			$data['module_couponreguser_shipping'] = $module_setting['module_couponreguser_shipping'];
		} else{
			$data['module_couponreguser_shipping']='';
		}
		
		if (isset($this->request->post['module_couponreguser_uses_customer'])) {
			$data['module_couponreguser_uses_customer'] = $this->request->post['module_couponreguser_uses_customer'];
		} elseif ($module_setting){
			$data['module_couponreguser_uses_customer'] = $module_setting['module_couponreguser_uses_customer'];
		} else{
			$data['module_couponreguser_uses_customer']=1;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/couponreguser', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/couponreguser')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
}