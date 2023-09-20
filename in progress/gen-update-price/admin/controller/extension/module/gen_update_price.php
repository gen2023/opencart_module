<?php
class ControllerExtensionModuleGenUpdatePrice extends Controller {
	private $error = array();
	
	public function install() {
		$this->load->model('extension/module/gen_update_price');
		$this->model_extension_module_gen_update_price->install();
	}
	public function uninstall() {
		$this->load->model('extension/module/gen_update_price');
		$this->model_extension_module_gen_update_price->uninstall();
	}

	public function index() {
		$this->load->language('extension/module/gen_update_price');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/gen_update_price');	
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			//var_dump($this->request->post);
			$this->model_setting_setting->editSetting('module_gen_update_price', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
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
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/gen_update_price', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/gen_update_price', 'user_token=' . $this->session->data['user_token'], true);
		
		$data['user_token'] = $this->session->data['user_token'];

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

	/* list product */
		$this->load->model('catalog/product');
		$data['products'] = array();

		$filter_data = array(
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);
		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

		$results = $this->model_catalog_product->getProducts($filter_data);
		foreach ($results as $result) {
			$special = false;
			$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

			foreach ($product_specials  as $product_special) {
				if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
					$special = $this->currency->format($product_special['price'], $this->config->get('config_currency'));

					break;
				}
			}
			$data['products'][] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'model'      => $result['model'],
				'price'      => $this->currency->format($result['price'], $this->config->get('config_currency')),
				'special'    => $special,
				'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')
			);
		}

		$url = '';

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/gen_update_price', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf(
			$this->language->get('text_pagination'), 
			($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, 
			((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), 
			$product_total, 
			ceil($product_total / $this->config->get('config_limit_admin')));
		

	/* list product end*/

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/gen_update_price', $data));
	}
	
	public function apply(){
		
		$this->load->model('extension/module/gen_update_price');
	
		$json = array();

		if ($this->request->post['typePrice']=='price'){
			$result = $this->model_extension_module_gen_update_price->applyPrice($this->request->post);
		} else {
			$result = $this->model_extension_module_gen_update_price->applySpecialPrice($this->request->post);
		}		

		$json['result']=$result;
		
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function resetPrice(){
		
		$this->load->model('extension/module/gen_update_price');
	
		$json = array();
		$result = $this->model_extension_module_gen_update_price->resetPrice();

		$json['result']=$result;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function updateNewPrice(){
		
		$this->load->model('extension/module/gen_update_price');
	
		$json = array();
		$result = $this->model_extension_module_gen_update_price->updatePrice();

		$json['result']=$result;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/gen_update_price')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}