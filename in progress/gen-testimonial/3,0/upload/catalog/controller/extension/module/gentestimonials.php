<?php
class ControllerExtensionModuleGentestimonials extends Controller
{
	public function index($setting)
	{
		static $module = 0;

		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/opencart.css');
		$this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');
		$this->document->addStyle('catalog/view/theme/default/stylesheet/testimonials-style.min.css');

		$this->load->model('extension/module/gentestimonials');
		$this->load->language('extension/module/gentestimonials');

		$filter = array();
		$filter['count_slider'] = $setting['count_slider'];

		$results = $this->model_extension_module_gentestimonials->getTestimonials($filter);
		$totalAll = $this->model_extension_module_gentestimonials->getTestimonialsAll();
		$total = count($totalAll);

		$data['link_all_testimonial'] = $this->url->link('extension/module/gentestimonials_list');
		$data['total_testimonial'] = $this->language->get('text_total_testimonial') . ' ' . $total;

		$data['testimonations'] = array();
		$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');

		foreach ($results as $result) {
			// var_dump($result);

			$data['testimonations'][] = array(
				'id' => $result['testimonial_id'],
				'positive' => $result['positive'],
				'recomended_shop' => $result['recomended_shop'] == 1 ? $this->language->get('text_recomended') : $this->language->get('text_no_recomended'),
				'negative' => $result['negative'],
				'userLink' => $result['userLink'],
				'rating' => $result['rating'],
				'author' => $result['user'],
				'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, 200) . '...',
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date'])),
				'avatar_name' => mb_substr($result['user'], 0, 1),
				'avatar_name_color' => 'background: #' . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . $rand[mt_rand(0, 15)] . ';'
			);
		}

		$average_rating = 0;
		$count_rating_5 = 0;
		$count_rating_4 = 0;
		$count_rating_3 = 0;
		$count_rating_2 = 0;
		$count_rating_1 = 0;


		foreach ($totalAll as $key => $value) {
			$average_rating = $average_rating + $value['rating'];
			switch ($value['rating']) {
				case '5':
					$count_rating_5 = $count_rating_5 + 1;
					break;
				case '4':
					$count_rating_4 = $count_rating_4 + 1;
					break;
				case '3':
					$count_rating_3 = $count_rating_3 + 1;
					break;
				case '2':
					$count_rating_2 = $count_rating_2 + 1;
					break;
				case '1':
					$count_rating_1 = $count_rating_1 + 1;
					break;

			}
		}

		if ($totalAll) {
			$data['count_rating_5'] = round($count_rating_5 / $total * 100);
			$data['count_rating_4'] = round($count_rating_4 / $total * 100);
			$data['count_rating_3'] = round($count_rating_3 / $total * 100);
			$data['count_rating_2'] = round($count_rating_2 / $total * 100);
			$data['count_rating_1'] = round($count_rating_1 / $total * 100);
			$data['average_rating'] = round($average_rating / $total, 2);
		} else {
			$data['count_rating_5'] = 0;
			$data['count_rating_4'] = 0;
			$data['count_rating_3'] = 0;
			$data['count_rating_2'] = 0;
			$data['count_rating_1'] = 0;
			$data['average_rating'] = 0;
		}

		$data['module'] = $module++;
		$data['direction'] = $setting['direction'];
		$data['effect'] = $setting['effect'];
		$data['enabled'] = $setting['enabled'];
		$data['followFinger'] = $setting['followFinger'];
		$data['longSwipesMs'] = $setting['longSwipesMs'];
		$data['loop'] = $setting['loop'];
		$data['preloadImages'] = $setting['preloadImages'];
		$data['autoplay'] = $setting['autoplay'];
		$data['viewPagination'] = $setting['viewPagination'];
		$data['viewnavigation'] = $setting['viewnavigation'];
		$data['display_avatar'] = $setting['display_avatar'];
		$data['display_client'] = $setting['display_client'];
		$data['display_rating'] = $setting['display_rating'];
		$data['display_date'] = $setting['display_date'];
		$data['display_userRating'] = $setting['display_userRating'];
		$data['all_testimonial'] = $setting['all_testimonial'];
		$data['add_testimonial'] = $setting['add_testimonial'];
		$data['template'] = $setting['template'];
		$data['class_image'] = $setting['class_image'];
		$data['status_newTestimonial'] = $setting['status_newTestimonial'];
		$data['bg_rating'] = ' rat-avg-' . (int) $data['average_rating'];

		$data['title'] = '';



		if ($setting['viewTitle'] == '1') {
			$data['title'] = $setting['module_title'][$this->config->get('config_language_id')]['name'];
		}

		$data['design'] = $this->load->view('extension/module/testimonial_template/design0', $data);

		return $this->load->view('extension/module/gentestimonials', $data);
	}

	public function write()
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

			if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
				$json['error'] = $this->language->get('error_rating');
			}

			// Captcha
			// if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
			// 	$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

			// 	if ($captcha) {
			// 		$json['error'] = $captcha;
			// 	}
			// }

			if (!isset($json['error'])) {
				$this->load->model('extension/module/gentestimonials');


				$this->model_extension_module_gentestimonials->addTestimonial($this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function updateTestomonialRating()
	{
		$this->load->language('extension/module/gentestimonials');
		$this->load->model('extension/module/gentestimonials');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {

			$this->model_extension_module_gentestimonials->updateTestomonialRating($this->request->post);

			$json['success'] = $this->language->get('alert_success_updateRating');

		}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}