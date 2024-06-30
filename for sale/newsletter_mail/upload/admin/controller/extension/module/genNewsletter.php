<?php
class ControllerExtensionModuleGenNewsletter extends Controller
{
	private $error = array();

	public function install()
	{
		$this->load->model('extension/module/genNewsletter');
		$this->model_extension_module_genNewsletter->install();
	}
	public function uninstall()
	{
		$this->load->model('extension/module/genNewsletter');
		$this->model_extension_module_genNewsletter->uninstall();
	}

	public function index()
	{

		$this->load->language('extension/module/genNewsletter');
		$this->load->model('setting/setting');
		$this->load->model('extension/module/genNewsletter');
		$this->load->model('localisation/language');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {


			$this->request->post['module_genNewsletter_settings'] = json_encode($this->request->post);

			$this->model_setting_setting->editSetting('module_genNewsletter', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

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
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/genNewsletter', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/genNewsletter', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true);
		$data['list'] = $this->url->link('extension/module/genNewsletter/list', 'user_token=' . $this->session->data['user_token'], true);

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['reqEmail'])) {
			$data['reqEmail'] = $this->request->post['reqEmail'];
		} elseif ($this->config->get('module_genNewsletter_settings')) {
			$data['reqEmail'] = json_decode($this->config->get('module_genNewsletter_settings'))->reqEmail;
		} else {
			$data['reqEmail'] = 1;
		}
		if (isset($this->request->post['requireTown'])) {
			$data['reqTown'] = $this->request->post['reqTown'];
		} elseif ($this->config->get('module_genNewsletter_settings')) {
			$data['reqTown'] = json_decode($this->config->get('module_genNewsletter_settings'))->reqTown;
		} else {
			$data['reqTown'] = 0;
		}
		if (isset($this->request->post['reqName'])) {
			$data['reqName'] = $this->request->post['reqName'];
		} elseif ($this->config->get('module_genNewsletter_settings')) {
			$data['reqName'] = json_decode($this->config->get('module_genNewsletter_settings'))->reqName;
		} else {
			$data['reqName'] = 0;
		}
		if (isset($this->request->post['showEmail'])) {
			$data['showEmail'] = $this->request->post['showEmail'];
		} elseif ($this->config->get('module_genNewsletter_settings')) {
			$data['showEmail'] = json_decode($this->config->get('module_genNewsletter_settings'))->showEmail;
		} else {
			$data['showEmail'] = 0;
		}
		if (isset($this->request->post['showTown'])) {
			$data['showTown'] = $this->request->post['showTown'];
		} elseif ($this->config->get('module_genNewsletter_settings')) {
			$data['showTown'] = json_decode($this->config->get('module_genNewsletter_settings'))->showTown;
		} else {
			$data['showTown'] = 0;
		}
		if (isset($this->request->post['showUnsubscribe'])) {
			$data['showUnsubscribe'] = $this->request->post['showUnsubscribe'];
		} elseif ($this->config->get('module_genNewsletter_settings')) {
			$data['showUnsubscribe'] = json_decode($this->config->get('module_genNewsletter_settings'))->showUnsubscribe;
		} else {
			$data['showUnsubscribe'] = 0;
		}
		if (isset($this->request->post['showName'])) {
			$data['showName'] = $this->request->post['showName'];
		} elseif ($this->config->get('module_genNewsletter_settings')) {
			$data['showName'] = json_decode($this->config->get('module_genNewsletter_settings'))->showName;
		} else {
			$data['showName'] = 0;
		}
		if (isset($this->request->post['module_genNewsletter_status'])) {
			$data['module_genNewsletter_status'] = $this->request->post['module_genNewsletter_status'];
		} elseif ($this->config->get('module_genNewsletter_settings')) {
			$data['module_genNewsletter_status'] = $this->config->get('module_genNewsletter_status');
		} else {
			$data['module_genNewsletter_status'] = 0;
		}
		if (isset($this->request->post['showPhone'])) {
			$data['showPhone'] = $this->request->post['showPhone'];
		} elseif ($this->config->get('module_genNewsletter_settings')) {
			$data['showPhone'] = json_decode($this->config->get('module_genNewsletter_settings'))->showPhone;
		} else {
			$data['showPhone'] = 0;
		}
		if (isset($this->request->post['showUserRegister'])) {
			$data['showUserRegister'] = $this->request->post['showUserRegister'];
		} elseif ($this->config->get('module_genNewsletter_settings')) {
			$data['showUserRegister'] = json_decode($this->config->get('module_genNewsletter_settings'))->showUserRegister;
		} else {
			$data['showUserRegister'] = 0;
		}
		if (isset($this->request->post['reqPhone'])) {
			$data['reqPhone'] = $this->request->post['reqPhone'];
		} elseif ($this->config->get('module_genNewsletter_settings')) {
			$data['reqPhone'] = json_decode($this->config->get('module_genNewsletter_settings'))->reqPhone;
		} else {
			$data['reqPhone'] = 0;
		}
		if (isset($this->request->post['req_field'])) {
			$data['req_field'] = $this->request->post['req_field'];
		} elseif ($this->config->get('module_genNewsletter_settings')) {
			$data['req_field'] = json_decode($this->config->get('module_genNewsletter_settings'))->req_field;
		} else {
			$data['req_field'] = 0;
		}
		if (isset($this->request->post['showPolitical'])) {
			$data['showPolitical'] = $this->request->post['showPolitical'];
		} elseif ($this->config->get('module_genNewsletter_settings')) {
			$data['showPolitical'] = json_decode($this->config->get('module_genNewsletter_settings'))->showPolitical;
		} else {
			$data['showPolitical'] = 0;
		}
		if (isset($this->request->post['political_text'])) {
			$data['political_text'] = $this->request->post['political_text'];
		} elseif ($this->config->get('module_genNewsletter_settings')) {
			foreach (json_decode($this->config->get('module_genNewsletter_settings'))->political_text as $key => $value) {
				$data['political_text'][$key] = $value;
			}
		} else {
			$data['political_text'] = 0;
		}
		if (isset($this->request->post['text_mail1'])) {
			$data['text_mail1'] = $this->request->post['text_mail1'];
		} elseif ($this->config->get('module_genNewsletter_settings')) {
			foreach (json_decode($this->config->get('module_genNewsletter_settings'))->text_mail1 as $key => $value) {
				$data['text_mail1'][$key] = $value;
			}
		} else {
			$data['text_mail1'] = '';
		}
		if (isset($this->request->post['text_mail2'])) {
			$data['text_mail2'] = $this->request->post['text_mail2'];
		} elseif ($this->config->get('module_genNewsletter_settings')) {
			foreach (json_decode($this->config->get('module_genNewsletter_settings'))->text_mail2 as $key => $value) {
				$data['text_mail2'][$key] = $value;
			}
		} else {
			$data['text_mail2'] = '';
		}
		$data['link_subscribe']=HTTPS_CATALOG . 'index.php?route=extension/module/genNewsletter/subscribe&email={{email}}';
		$data['link_unsubscribe']=HTTPS_CATALOG . 'index.php?route=extension/module/genNewsletter/unsubscribe&email={{email}}';
		$data['help_mail_subscrive']=sprintf($this->language->get('help_mail_subscrive'), $data['link_subscribe']);
		$data['help_mail_unsubscribe']=sprintf($this->language->get('help_mail_unsubscribe'), $data['link_unsubscribe']);		


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/genNewsletter', $data));
	}

	public function delete() {
		$this->load->language('extension/module/genNewsletter');
		$this->load->model('extension/module/genNewsletter');

		$this->document->setTitle($this->language->get('heading_title'));		

		if (isset($this->request->post['selected']) && $this->validate()) {
			foreach ($this->request->post['selected'] as $newsletter_id) {
				$this->model_extension_module_genNewsletter->deleteNewsletter($newsletter_id);
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

			$this->response->redirect($this->url->link('extension/module/genNewsletter/list', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->list();
	}

	public function list() {
		$this->load->language('extension/module/genNewsletter');
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('marketing/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/genNewsletter', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_list'),
			'href' => $this->url->link('extension/module/genNewsletter/list', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL')
		);

		$data['delete'] = $this->url->link('extension/module/genNewsletter/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$this->load->model('extension/module/genNewsletter');
		$result=$this->model_extension_module_genNewsletter->getNewsLetter();
		
		$data['newsltrs'] = array();
		foreach($result as $res)
		{
			$data['newsltrs'][] = array(
			'newsletter_id' => $res['newsletter_id'],
			'email' => $res['email'],
			'phone' => $res['phone'],
			'name' => $res['name'],
			'town' => $res['town'],
			'selected'    	=> isset($this->request->post['selected']) && in_array($res['newsletter_id'], $this->request->post['selected'])
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['text_list'] = $this->language->get('text_list');
		
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_send'] = $this->language->get('button_send');
		$data['button_delete'] = $this->language->get('button_delete');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_town'] = $this->language->get('column_town');	
		$data['column_phone'] = $this->language->get('column_phone');


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
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

		$data['sort_name'] = $this->url->link('extension/module/genNewsletter/list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, 'SSL');
		$data['sort_town'] = $this->url->link('extension/module/genNewsletter/list', 'user_token=' . $this->session->data['user_token'] . '&sort=sort_town' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['path'])) {
			$url .= '&path=' . $this->request->get['path'];
		}
		
		$pagination = new Pagination();
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/genNewsletter/list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/genNewsletter_list', $data));
	}

	protected function validate()
	{
		if (!$this->user->hasPermission('modify', 'extension/module/genNewsletter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
