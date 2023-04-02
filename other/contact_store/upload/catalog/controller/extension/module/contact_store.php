<?php
class ControllerExtensionModuleContactStore extends Controller {
	public function index() {
		$this->load->language('extension/module/contact_store');

		$data['heading_title'] = $this->language->get('heading_title');
		$data['telephone'] = $this->config->get('config_telephone');
		$data['address'] = nl2br($this->config->get('config_address'));
		$data['email'] = $this->config->get('config_email');
		$data['open'] = nl2br($this->config->get('config_open'));
		$data['text_open'] = $this->language->get('text_open');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['store'] = $this->language->get('store');

		$this->load->model('tool/image');

		if ($this->config->get('config_image')) {
			$data['image'] = $this->model_tool_image->resize($this->config->get('config_image'), $this->config->get($this->config->get('config_theme') . '_image_location_width'), $this->config->get($this->config->get('config_theme') . '_image_location_height'));
		} else {
			$data['image'] = false;
		}
		
		return $this->load->view('extension/module/contact_store', $data);
	}
	
}
