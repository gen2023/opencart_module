<?php
class ControllerExtensionModuleNewsletters extends Controller {
	public function index() {
		$this->load->language('extension/module/newsletter');
		$this->load->model('extension/module/newsletters');
		

		$data['heading_title'] = $this->language->get('heading_title');
		$data['name'] = $this->language->get('name');
		$data['town'] = $this->language->get('town');
		$data['error_email'] = $this->language->get('error_email');
		$data['error_town'] = $this->language->get('error_town');
		$data['error_name'] = $this->language->get('error_name');
		$data['error_email2'] = $this->language->get('error_email2');
		$data['but_newsletter'] = $this->language->get('but_newsletter');
		
		return $this->load->view('extension/module/newsletters', $data);
	}
	public function setSubscribe()
	{
		$this->load->language('extension/module/newsletter');
		$this->load->model('extension/module/newsletters');
		
		$data['text_success'] = $this->language->get('text_success');
		$data['text_dublicate'] = $this->language->get('text_dublicate');
		$data['text_error'] = $this->language->get('text_error');
		
		$json = array();
		$result = $this->model_extension_module_newsletters->subscribes($this->request->post);

		switch ($result) {
			case 0:
				$json['message']=$data['text_error'];
				break;
			case 1:
				$json['message']=$data['text_success'];
				break;
			case 2:
				$json['message']=$data['text_dublicate'];
				break;
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}
	
}
