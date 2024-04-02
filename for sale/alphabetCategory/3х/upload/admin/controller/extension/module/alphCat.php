<?php
class ControllerExtensionModuleAlphCat extends Controller {
	private $error = array();
	
	// public function install() {
	// 	$this->load->model('extension/module/alphCat');
	// 	$this->model_extension_module_alph_cat->install();
	// }
	// public function uninstall() {
	// 	$this->load->model('extension/module/alphCat');
	// 	$this->model_extension_module_alph_cat->uninstall();
	// }

	public function index() {
		$this->load->language('extension/module/alphCat'); //подключаем наш языковой файл

		$this->load->model('setting/module');   //подключаем модель setting, он позволяет сохранять настройки модуля в БД
		// var_dump($this->request->get);
		// var_dump($this->request->get['module_id']);
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) { //если мы нажали "Сохранить"  в панели, мы сохраняем текущие настройки
			
			if (!isset($this->request->get['module_id'])) {
				// var_dump('add');die;
				$this->model_setting_module->addModule('alphCat', $this->request->post);
			} else {
				// var_dump('add');die;
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}		

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
			
		}

         // ваши переменные
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');		

		$data['entry_status'] = $this->language->get('entry_status');

        // если метод validate вернул warning, передадим его представлению
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}		

        // далее идет формирование массива breadcrumbs (хлебные крошки)
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true)
		);
		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/alphCat', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/alphCat', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

        //ссылки для формы и кнопки "cancel"


		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/alphCat', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/alphCat', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		// Categories
		$this->load->model('catalog/category');

		if (isset($this->request->post['product_category'])) {
			$categories = $this->request->post['product_category'];
		} elseif (isset($module_info['product_category'])) {
			$categories = $module_info['product_category'];
		} else {
			$categories = array();
		}

		$data['product_categories'] = array();

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}

		//переменная с статусом модуля
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

        //ссылки на контроллеры header,column_left,footer, иначе мы не сможем вывести заголовок, подвал и левое меню в файле представления
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

        //в качестве файла представления модуля для панели администратора использовать файл mymodul.twig
		$this->response->setOutput($this->load->view('extension/module/alphCat', $data));
	}

    //обязательный метод в контроллере, он запускается для проверки разрешено ли пользователю изменять настройки данного модуля
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/alphCat')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}
}