<?php  
class ControllerExtensionModuleServices extends Controller {
	public function index() {

		$this->load->language('extension/module/services'); //подключаем языковой файл
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('extension/module/services')
		);
		
		$data['heading_title'] = $this->language->get('heading_title'); //объявляем переменную heading_title с данными из языкового файла

		$this->load->model('extension/module/services');

		$data['services']=$this->model_extension_module_services->getServices();
		$data['language']=$this->config->get('config_language_id');

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		$this->response->setOutput($this->load->view('extension/module/services', $data));
		//return $this->load->view('extension/module/services', $data);	
	}
}
