<?php  
	class ControllerModuleAjaxTestimonial extends Controller {
		
		protected function index($setting) {
			$this->language->load('module/ajaxtestimonial');
			
			$this->data['setting'] = $setting; 
			
			$this->data['ajaxtestimonial_title'] = html_entity_decode($setting['ajaxtestimonial_title'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			$this->data['text_more'] = $this->language->get('text_more');
			$this->data['text_more2'] = $this->language->get('text_more2');
			$this->data['ajaxtestimonial_form'] = $this->language->get('ajaxtestimonial_form');
			$this->data['show_all'] = $this->language->get('show_all');
			$this->data['showall_url'] = $this->url->link('product/ajaxtestimonial', '', 'SSL'); 
			$this->data['more'] = $this->url->link('product/ajaxtestimonial', 'ajaxtestimonial_id=' , 'SSL'); 
			$this->data['ajaxtestimonial_form_url'] = $this->url->link('product/ajaxtestimonial_form', '', 'SSL');
			
			$this->data['template_path'] = 'catalog/view/theme/'.$this->config->get('config_template');
			$this->document->addStyle($this->data['template_path'].'/stylesheet/ajaxtestimonial.css');
			
			$this->load->model('catalog/ajaxtestimonial');
			
			$this->data['ajaxtestimonials'] = array();
			
			$results = $this->model_catalog_ajaxtestimonial->getajaxtestimonials_module(0, $setting['ajaxtestimonial_limit'], (isset($setting['ajaxtestimonial_random']))?true:false);
			
			$this->load->model('tool/image');
			
			foreach ($results as $result) {
				
				$full_review = $this->data['more']. $result['ajaxtestimonial_id'];
				
				if (!isset($setting['ajaxtestimonial_character_limit'])) {
					$setting['ajaxtestimonial_character_limit'] = 0;
				}
				
				if ($setting['ajaxtestimonial_character_limit'] > 0 ) {
					$lim = $setting['ajaxtestimonial_character_limit'];
					
					if (mb_strlen($result['description'],'UTF-8') > $lim) {
						$result['description'] = mb_substr($result['description'], 0, $lim-3, 'UTF-8').'...';
						$result['description'] = strip_tags($result['description'], '<br><p><b>');
					}		
				}
				
				if (mb_strlen($result['name'],'UTF-8') > 20) {
					$result['name'] = mb_substr($result['name'], 0, 7, 'UTF-8').'...';
				}
				
				$this->data['ajaxtestimonials'][] = array(
				'id'			=> $result['ajaxtestimonial_id'],											  
				'description'	=> $result['description'],
				'rating'		=> $result['rating'],
				'name'		    => $result['name'],
				'date_added'	=> date($this->config->get('ajaxtestimonial_all_page_date_format'), strtotime($result['date_added'])),
				'full_review'   => $full_review
				);
			}
			
			$this->id = 'ajaxtestimonial';
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/ajaxtestimonial.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/ajaxtestimonial.tpl';
				} else {
				$this->template = 'default/template/module/ajaxtestimonial.tpl';
			}
			
			$this->render();
		}
	}
?>	