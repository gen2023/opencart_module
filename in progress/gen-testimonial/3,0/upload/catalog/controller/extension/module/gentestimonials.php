<?php
class ControllerExtensionModuleGentestimonials extends Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/opencart.css');
		$this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');
		
		$this->load->model('extension/module/gentestimonials');

		$result_sql=$this->model_extension_module_gentestimonials->getTestimonials();

		$data['testimonations'] = array();

		//$results = $this->model_design_banner->getBanner($setting['banner_id']);

		foreach ($result_sql as $result) {
				$data['testimonations'][] = array(
					'userLink' => $result['userLink'],
					'rating' => $result['rating'],
					'author' => $result['user'], /*переименовать в author*/
					'description' =>html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
					'color' => $result['color'],
					'date' => $result['date'],
					'testimonial_title' => 'test title', /*Добавить в написание отзывов */
					'job_title' => 'test JOB', /*Добавить в написание отзывов */
					'company' => 'test company', /*Добавить в написание отзывов */
					'urlCompany' => 'http://www.google.com', /*Добавить в написание отзывов */
					'author_image' => $this->model_tool_image->resize($result['image'], 100, 100)
				);
		}
//var_dump($data['testimonations']);

		$data['module'] = $module++;
		$data['title'] = '';
		
		$data['direction'] = $setting['direction'];
		$data['effect'] = $setting['effect'];
		$data['enabled'] = $setting['enabled'];
		$data['followFinger'] = $setting['followFinger'];
		$data['longSwipesMs'] = $setting['longSwipesMs'];
		$data['loop'] = $setting['loop'];
		$data['preloadImages'] = $setting['preloadImages'];
		$data['spaceBetween'] = $setting['spaceBetween'];
		$data['autoplay'] = $setting['autoplay'];
		$data['viewPagination'] = $setting['viewPagination'];
		$data['viewnavigation'] = $setting['viewnavigation'];
		
		$data['display_avatar'] = $setting['display_avatar'];
		$data['display_quotes'] = $setting['display_quotes'];
		$data['display_client'] = $setting['display_client'];
		$data['display_job'] = $setting['display_job'];
		$data['display_company'] = $setting['display_company'];
		
		$data['template'] = $setting['template'];
		$data['css_class'] = 'design' . $setting['template'];
		$data['class_image'] = 'circle'; /* добавить в настройки вид изображения square circle*/
		
		if ($setting['viewTitle']=='1')
			$data['title'] = $setting['name'];
		
		$data['design'] = $this->load->view('extension/module/testimonial_template/design' . $data['template'], $data);

		return $this->load->view('extension/module/gentestimonials', $data);
	}
}