<?php
class ControllerExtensionModulegentestimonialsList extends Controller
{
	private $error = array();

	public function index()
	{

		$this->document->addStyle('catalog/view/theme/default/stylesheet/testimonials-style.min.css');

		$this->load->model('extension/module/gentestimonials');
		$this->load->language('extension/module/gentestimonials');

		$filter = array();

		$results = $this->model_extension_module_gentestimonials->getTestimonials($filter);

		$totalAll = $this->model_extension_module_gentestimonials->getTestimonialsAll();
		$total = count($totalAll);

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

			$resultsAnswers = $this->model_extension_module_gentestimonials->getAnswers($result['testimonial_id']);

			$answers = array();
			foreach ($resultsAnswers as $result_answer) {

				$answers[] = array(
					'id' => $result_answer['testimonial_id'],
					'userLink' => $result_answer['userLink'],
					'author' => $result_answer['user'], /*переименовать в author*/
					'description' => html_entity_decode($result_answer['description'], ENT_QUOTES, 'UTF-8'),
					'date_added' => date($this->language->get('date_format_short'), strtotime($result_answer['date'])),
					'avatar_name' => mb_substr($result_answer['user'], 0, 1),
					'avatar_name_color' => 'background: #' . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . ';'
				);
			}

			$data['testimonations'][] = array(
				'id' => $result['testimonial_id'],
				'positive' => $result['positive'],
				'text_recomended_shop' => $result['recomended_shop'] == 1 ? $this->language->get('text_recomended') : $this->language->get('text_no_recomended'),
				'negative' => $result['negative'],
				'userLink' => $result['userLink'],
				'rating' => $result['rating'],
				'author' => $result['user'], /*переименовать в author*/
				'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date'])),
				'avatar_name' => mb_substr($result['user'], 0, 1),
				'avatar_name_color' => 'background: #' . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . ';',
				'answers' => $answers
			);
		}

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/module/gentestimonials_list', $data));
	}

	public function write_answer()
	{
		$this->load->language('extension/module/gentestimonials');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (!isset($json['error'])) {
				$this->load->model('extension/module/gentestimonials');

				$this->model_extension_module_gentestimonials->addAnswerTestimonial($this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}