<?php
class ControllerExtensionModuleMymodul extends Controller {
	private $error = array();
	
	public function install() {
		$this->load->model('extension/module/mymodul');
		$this->model_extension_module_mymodul->install();
	}
	public function uninstall() {
		$this->load->model('extension/module/mymodul');
		$this->model_extension_module_mymodul->uninstall();
	}

	public function index() {
		$this->load->language('extension/module/mymodul'); //подключаем наш языковой файл

		$this->load->model('setting/setting');   //подключаем модель setting, он позволяет сохранять настройки модуля в БД
		
		/* для типа модулей (одиночные)
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_mymodule', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}		
		*/

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) { //если мы нажали "Сохранить"  в панели, мы сохраняем текущие настройки
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('mymodul', $this->request->post);
			} else {
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
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/mymodul', 'user_token=' . $this->session->data['user_token'], true)
		);

        //ссылки для формы и кнопки "cancel"
		$data['action'] = $this->url->link('extension/module/mymodul', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		//переменная с статусом модуля
        if (isset($this->request->post['mymodul_status'])) {
			$data['mymodul_status'] = $this->request->post['mymodul_status'];
		} else {
			$data['mymodul_status'] = $this->config->get('mymodul_status');
		}

        //ссылки на контроллеры header,column_left,footer, иначе мы не сможем вывести заголовок, подвал и левое меню в файле представления
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

        //в качестве файла представления модуля для панели администратора использовать файл mymodul.twig
		$this->response->setOutput($this->load->view('extension/module/mymodul', $data));
	}

    //обязательный метод в контроллере, он запускается для проверки разрешено ли пользователю изменять настройки данного модуля
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/mymodul')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
}