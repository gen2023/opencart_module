<?php
class ControllerExtensionModuleCouponreguser extends Controller {
	public function install() {
		$this->load->model('extension/event');
		$this->model_extension_event->addEvent('mail_customer_coupon_register', 'catalog/model/account/customer/addCustomer/after', 'mail/couponreguser',1);
	}
	public function uninstall() {
		$this->load->model('setting/event');
		$this->model_setting_event->deleteEvent('mail_customer_coupon_register');
	}
	
	private $error = array();

	public function index() {
		$this->load->language('extension/module/couponreguser'); 

		$this->load->model('setting/setting');
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) { 
			
			$this->model_setting_setting->editSetting('module_couponreguser', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		//CKEditor
		if ($this->config->get('config_editor_default')) {
			$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
			$this->document->addScript('view/javascript/ckeditor/ckeditor_init.js');
		} else {
			$this->document->addScript('view/javascript/summernote/summernote.js');
			$this->document->addScript('view/javascript/summernote/lang/summernote-' . $this->language->get('lang') . '.js');
			$this->document->addScript('view/javascript/summernote/opencart.js');
			$this->document->addStyle('view/javascript/summernote/summernote.css');
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_amount'] = $this->language->get('text_amount');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_shipping'] = $this->language->get('entry_shipping');
		$data['entry_mail'] = $this->language->get('entry_mail');
		$data['entry_count_day'] = $this->language->get('entry_count_day');
		$data['entry_uses_total'] = $this->language->get('entry_uses_total');
		$data['entry_uses_customer'] = $this->language->get('entry_uses_customer');
		$data['entry_discount'] = $this->language->get('entry_discount');
		$data['entry_logged'] = $this->language->get('entry_logged');
		$data['entry_total'] = $this->language->get('entry_total');
		
		$data['help_total'] = $this->language->get('help_total');	
		$data['help_uses_total'] = $this->language->get('help_uses_total');
		$data['help_uses_customer'] = $this->language->get('help_uses_customer');
		$data['help_mail'] = $this->language->get('help_mail');
		$data['help_type'] = $this->language->get('help_type');
		$data['help_logged'] = $this->language->get('help_logged');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_setting_mail'] = $this->language->get('tab_setting_mail');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/couponreguser', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('extension/module/couponreguser', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');
		
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