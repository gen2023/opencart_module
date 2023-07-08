<?php
class ControllerExtensionModuleCountUser extends Controller {
	private $error = array();
	
	public function install() {
		$this->load->model('extension/module/count_user');
		$this->model_extension_module_count_user->install();
	}
	public function uninstall() {
		$this->load->model('extension/module/count_user');
		$this->model_extension_module_count_user->uninstall();
	}

	public function index() {
		$this->load->language('extension/module/count_user'); //подключаем наш языковой файл

		$this->load->model('setting/setting');   //подключаем модель setting, он позволяет сохранять настройки модуля в БД
		$this->load->model('extension/module/count_user');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_count_user', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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
			'href' => $this->url->link('extension/module/count_user', 'user_token=' . $this->session->data['user_token'], true)
		);

        //ссылки для формы и кнопки "cancel"
		$data['action'] = $this->url->link('extension/module/count_user', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		//переменная с статусом модуля
        if (isset($this->request->post['module_count_user_status'])) {
			$data['module_count_user_status'] = $this->request->post['module_count_user_status'];
		} else {
			$data['module_count_user_status'] = $this->config->get('module_count_user_status');
		}
		
		$nowTime=time();
		$current_day=date('d.m.Y', $nowTime);

		$day=strtotime("-1 day");
		$yesterday_day=date("d.m.Y",$day);
		
		$week=strtotime("-1 week");		
		$month=strtotime("-1 month");
		$year=strtotime("-1 year");
		
		//var_dump(date("d.m.Y",$month));
		
		$totalAll = $this->model_extension_module_count_user->getTotal();
		$results = $this->model_extension_module_count_user->getTotalOnline();
		$countDay=0;
		$countУesterday=0;
		$countWeek=0;
		$countMonth=0;
		$countYear=0;
		
		foreach ($results as $user) {
			if(date('d.m.Y', $user['time'])==$current_day){
				$countDay +=1;
			}
			if(date('d.m.Y', $user['time'])==$yesterday_day){
				$countУesterday +=1;
			}
			if($user['time']>$week){
				$countWeek +=1;
			}
			if($user['time']>$month){
				$countMonth +=1;
			}
			if($user['time']>$year){
				$countYear +=1;
			}
		}
		
		$data['totalAll'] = $totalAll;
		$data['totalDay']=$countDay;
		$data['totalУesterday']=$countУesterday;
		$data['totalWeek']=$countWeek;
		$data['totalMonth']=$countMonth;
		$data['totalYear']=$countYear;

        //ссылки на контроллеры header,column_left,footer, иначе мы не сможем вывести заголовок, подвал и левое меню в файле представления
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

        //в качестве файла представления модуля для панели администратора использовать файл mymodul.twig
		$this->response->setOutput($this->load->view('extension/module/count_user', $data));
	}

    //обязательный метод в контроллере, он запускается для проверки разрешено ли пользователю изменять настройки данного модуля
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/count_user')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
}