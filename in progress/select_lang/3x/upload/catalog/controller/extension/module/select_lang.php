<?php
class ControllerExtensionModuleSelectLang extends Controller
{
	public function index($setting)
	{
		if ($this->config->get('module_select_lang_status')) {
			$data['second']=$this->config->get('module_select_lang_second');
			
			$data['isImage']=$this->config->get('module_select_lang_image');

			$data['isCoockie']=$this->config->get('module_select_lang_isCoockie');
			$data['isAllPage']=$this->config->get('module_select_lang_isAllPage');


			if (!isset($_COOKIE['language']) || $data['isCoockie']) {

				$this->load->language('extension/module/select_lang');
				$data['title'] = $this->language->get('title');
		
				$this->load->language('common/language');
		
		
				$data['action'] = $this->url->link('common/language/language', '', $this->request->server['HTTPS']);
				$data['code'] = $this->session->data['language'];
		
				$this->load->model('localisation/language');
		
				$data['languages'] = array();
		
				$results = $this->model_localisation_language->getLanguages();
		
				foreach ($results as $result) {
					if ($result['status']) {
						$data['languages'][] = array(
							'name' => $result['name'],
							'code' => $result['code']
						);
					}
				}
		
				if (!isset($this->request->get['route'])) {
					$data['redirect'] = $this->url->link('common/home');
				} else {
					$url_data = $this->request->get;
		
					unset($url_data['_route_']);
					$route = $url_data['route'];
					unset($url_data['route']);
		
					$url = '';
		
					if ($url_data) {
						$url = '&' . urldecode(http_build_query($url_data, '', '&'));
					}
		
					$data['redirect'] = $this->url->link($route, $url, $this->request->server['HTTPS']);
				}
		
				return $this->load->view('extension/module/select_lang', $data);
			}
		}
	}


	public function language()
	{
		if (isset($this->request->post['code'])) {
			$this->session->data['language'] = $this->request->post['code'];
		}

		if (isset($this->request->post['redirect'])) {
			$this->response->redirect($this->request->post['redirect']);
		} else {
			$this->response->redirect($this->url->link('common/home'));
		}
	}
}
