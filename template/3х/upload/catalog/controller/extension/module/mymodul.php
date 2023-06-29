<?php  
class ControllerExtensionModuleMymodul extends Controller {
	public function index() {

		$this->load->language('extension/module/mymodul'); //подключаем языковой файл
		$data['heading_title'] = $this->language->get('heading_title'); //объявляем переменную heading_title с данными из языкового файла

		$data['content']="Ваш контент";        //можно задать данные, сразу в контроллере

		$this->load->model('extension/module/mymodul'); //подключаем любую модель из OpenCart



		//стандартная процедура для контроллеров OpenCart, вывода данных
		return $this->load->view('extension/module/mymodul', $data);		

	}
}
