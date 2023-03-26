<?php  
class ControllerExtensionModuleMymodul extends Controller {
	public function index() {

		$this->load->language('extension/module/mymodul'); //подключаем языковой файл
		$data['heading_title'] = $this->language->get('heading_title'); //объявляем переменную heading_title с данными из языкового файла

		$data['content']="Ваш контент";        //можно задать данные, сразу в контроллере

		$this->load->model('catalog/product'); //подключаем любую модель из OpenCart

		$data['product_info']=$this->model_catalog_product->getProduct(42); //используем метод подключенной модели, например getProduct(42) – информация о продукте id  42

		//стандартная процедура для контроллеров OpenCart, вывода данных
		return $this->load->view('extension/module/mymodul', $data);	

	}
}
