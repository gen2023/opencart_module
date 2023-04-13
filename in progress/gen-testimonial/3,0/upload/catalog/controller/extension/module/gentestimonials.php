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

		foreach ($result_sql as $result) {
			$data['testimonations'][] = array(
				'userLink' => $result['userLink'],
				'rating' => $result['rating'],
				'author' => $result['user'], /*переименовать в author*/
				'description' =>html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date'])),
				'testimonial_title' => $result['testimonial_title'],
				'job_title' => $result['job_title'], 
				'company' => $result['company'], 
				'urlCompany' => $result['urlCompany'], 
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
		$data['display_rating'] = $setting['display_rating'];
		$data['display_date'] = $setting['display_date'];
		
		$data['template'] = $setting['template'];
		$data['css_class'] = 'design' . $setting['template'];
		$data['class_image'] = $setting['template'];
		$data['slidesPerView'] = $setting['slidesPerView'];
		
		if ($setting['slidesPerView320']==''){
			$data['slidesPerView320']=$setting['slidesPerView'];
		} else {
			$data['slidesPerView320'] 	= $setting['slidesPerView320'];
		}
		if ($setting['slidesPerView1024']==''){
			$data['slidesPerView1024']=$setting['slidesPerView'];
		} else {
			$data['slidesPerView1024'] 	= $setting['slidesPerView1024'];
		}
		if ($setting['slidesPerView425']==''){
			$data['slidesPerView425']=$setting['slidesPerView'];
		} else {
			$data['slidesPerView425'] 	= $setting['slidesPerView425'];
		}
		if ($setting['slidesPerView768']==''){
			$data['slidesPerView768']=$setting['slidesPerView'];
		} else {
			$data['slidesPerView768'] 	= $setting['slidesPerView768'];
		}
		
		if ($setting['spaceBetween320']==''){
			$data['spaceBetween320']=$setting['spaceBetween'];
		} else {
			$data['spaceBetween320'] 	= $setting['spaceBetween320'];
		}
		if ($setting['spaceBetween1024']==''){
			$data['spaceBetween1024']=$setting['spaceBetween'];
		} else {
			$data['spaceBetween1024'] 	= $setting['spaceBetween1024'];
		}
		if ($setting['spaceBetween768']==''){
			$data['spaceBetween768']=$setting['spaceBetween'];
		} else {
			$data['spaceBetween768'] 	= $setting['spaceBetween768'];
		}
		if ($setting['spaceBetween425']==''){
			$data['spaceBetween425']=$setting['spaceBetween'];
		} else {
			$data['spaceBetween425'] 	= $setting['spaceBetween425'];
		}
		
		if ($setting['viewTitle']=='1')
			$data['title'] = $setting['name'];
		
		$data['design'] = $this->load->view('extension/module/testimonial_template/design' . $data['template'], $data);

		return $this->load->view('extension/module/gentestimonials', $data);
	}
}