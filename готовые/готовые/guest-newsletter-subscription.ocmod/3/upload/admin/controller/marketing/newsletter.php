<?php
class ControllerMarketingNewsletter extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('marketing/newsletter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('marketing/newsletter');
		$this->model_marketing_newsletter->createNewsletter();

		$this->getList();
	}
	
	public function delete() {
		$this->load->language('marketing/newsletter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('marketing/newsletter');

//var_dump($this->request->post['selected']);
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $news_id) {
				$this->model_marketing_newsletter->deleteNewsletter($news_id);
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

			$this->response->redirect($this->url->link('marketing/newsletter', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
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
			'href' => $this->url->link('marketing/newsletter', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL')
		);

		$data['cancel'] = $this->url->link('marketing/newsletter', 'user_token=' . $this->session->data['user_token'], true);
		$data['delete'] = $this->url->link('marketing/newsletter/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$this->load->model('marketing/newsletter');
		$result=$this->model_marketing_newsletter->getNewsLetter();
		
		$data['newsltrs'] = array();
		foreach($result as $res)
		{
			$data['newsltrs'][] = array(
			'news_id' => $res['news_id'],
			'news_email' => $res['news_email'],
			'news_name' => $res['news_name'],
			'news_town' => $res['news_town'],
			'selected'    	=> isset($this->request->post['selected']) && in_array($res['news_id'], $this->request->post['selected'])
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

		$data['sort_name'] = $this->url->link('marketing/newsletter', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, 'SSL');
		$data['sort_town'] = $this->url->link('marketing/newsletter', 'user_token=' . $this->session->data['user_token'] . '&sort=sort_town' . $url, 'SSL');

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
		//$pagination->total = $category_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('marketing/newsletter', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/newsletter', $data));
	}
	

		protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'marketing/newsletter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	
		return !$this->error;
	}
	
}	