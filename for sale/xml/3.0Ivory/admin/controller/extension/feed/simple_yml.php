<?php
class ControllerExtensionFeedSimpleYML extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('extension/feed/simple_yml');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->model_setting_setting->editSetting('feed_simple_yml', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			
			if (isset($this->request->post['apply'])) {
				$this->response->redirect($this->url->link('extension/feed/simple_yml', 'user_token=' . $this->session->data['user_token'], true));
			} else {
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true));
			}
		}

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
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/feed/simple_yml', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/feed/simple_yml', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true);

		if (isset($this->request->post['feed_simple_yml_status'])) {
			$data['feed_simple_yml_status'] = $this->request->post['feed_simple_yml_status'];
		} else {
			$data['feed_simple_yml_status'] = $this->config->get('feed_simple_yml_status');
		}

		$data['feed_linkGoogle'] = HTTPS_CATALOG . 'index.php?route=extension/feed/simple_yml_google';
		$data['feed_linkFacebook'] = HTTPS_CATALOG . 'index.php?route=extension/feed/simple_yml_facebook';
		$data['feed_linkInstagram'] = HTTPS_CATALOG . 'index.php?route=extension/feed/simple_yml_instagram';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/feed/simple_yml', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/feed/google_sitemap')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
}