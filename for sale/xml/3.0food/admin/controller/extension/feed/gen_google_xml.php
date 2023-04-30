<?php
class ControllerExtensionFeedGenGoogleXml extends Controller {
	private $error = array();
	
	public function install() {
		$this->load->model('extension/feed/gen_google_xml');

		$this->model_extension_feed_gen_google_xml->install();
	}

	public function uninstall() {
		$this->load->model('extension/feed/gen_google_xml');

		$this->model_extension_feed_gen_google_xml->uninstall();
	}
	
	public function index() {
		$this->load->language('extension/feed/gen_google_xml');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->model_setting_setting->editSetting('feed_gen_google_xml', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			
			if (isset($this->request->post['apply'])) {
				$this->response->redirect($this->url->link('extension/feed/gen_google_xml', 'user_token=' . $this->session->data['user_token'], true));
			} else {
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true));
			}
		}
		
		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_feed'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/feed/gen_google_xml', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/feed/gen_google_xml', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true);

		if (isset($this->request->post['feed_gen_google_xml_status'])) {
			$data['feed_gen_google_xml_status'] = $this->request->post['feed_gen_google_xml_status'];
		} else {
			$data['feed_gen_google_xml_status'] = $this->config->get('feed_gen_google_xml_status');
		}
		
		
		$this->load->model('extension/feed/gen_google_xml');

		
		
		$this->load->model('catalog/category');

		$results=$this->model_catalog_category->getCategories();
		
		foreach ($results as $result) {
			$id_googleCat=$this->model_extension_feed_gen_google_xml->getGoogleCategory($result['category_id']);

			$data['categorys'][] = array(
				'category_id'             => $result['category_id'],
				'category_name'           => $result['name'],
				'google_category'         => $id_googleCat ? $id_googleCat['google_category_id'] : 0
			);
		}

		$data['feed_linkGoogle'] = HTTPS_CATALOG . 'index.php?route=extension/feed/gen_google_xml';
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/feed/gen_google_xml', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/feed/gen_google_xml')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function updateGoogleCategory() {

		$this->load->language('extension/feed/gen_google_xml');

		$json = array();
		//$json['success']=$this->request->post['categoryGoogle'];

		if (!$this->user->hasPermission('modify', 'extension/feed/gen_google_xml')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('extension/feed/gen_google_xml');

			$this->model_extension_feed_gen_google_xml->updateGoogleCategory($this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}