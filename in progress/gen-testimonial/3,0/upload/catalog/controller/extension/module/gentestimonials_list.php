<?php
class ControllerExtensionModulegentestimonialsList extends Controller {
	private $error = array();

	public function index()
	{

		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/theme/default/stylesheet/testimonials-style.min.css');

		$this->load->model('extension/module/gentestimonials');
		$this->load->language('extension/module/gentestimonials');

		$filter=array();


		$results = $this->model_extension_module_gentestimonials->getTestimonials($filter);
		$totalAll = $this->model_extension_module_gentestimonials->getTestimonialsAll();
		$total=count($totalAll);

    $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

    $data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_all_testimonial'),
			'href' => $this->url->link('extension/module/gentestimonials_list')
		);

		$data['total_testimonial'] = $this->language->get('text_total_testimonial') . ' ' . $total;

		$data['testimonations'] = array();
		$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');

		foreach ($results as $result) {
			// var_dump($result);

			$data['testimonations'][] = array(
				'id'=> $result['testimonial_id'],
				'positive' => $result['positive'],
        'text_recomended_shop' => $result['recomended_shop']==1 ? $this->language->get('text_recomended') : $this->language->get('text_no_recomended'),
				'negative' => $result['negative'],
				'userLink' => $result['userLink'],
				'rating' => $result['rating'],
				'author' => $result['user'], /*переименовать в author*/
				'description' =>html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
				// 'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, 200) . '...',
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date'])),
				'avatar_name' => $result['user'][0],
				'author_image' => $this->model_tool_image->resize($result['image'], 100, 100),
				'avatar_name_color' => 'background: #' . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . ';'
			);
		}		

    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');

    $this->response->setOutput($this->load->view('extension/module/gentestimonials_list', $data));
	}

}