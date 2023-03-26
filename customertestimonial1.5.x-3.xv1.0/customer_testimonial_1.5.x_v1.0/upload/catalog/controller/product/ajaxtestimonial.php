<?php 
	class ControllerProductAjaxTestimonial extends Controller {	
		
		public function index() {  
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'date_added';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = 'DESC';
			}
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else { 
				$page = 1;
			}	
			
			$this->language->load('product/ajaxtestimonial');
			$this->language->load('product/ajaxtestimonial_form');
			
			$this->load->model('catalog/ajaxtestimonial');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}	
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', '', 'SSL'),
      		'separator' => false
			);
			
			
			$this->data['template_path'] = 'catalog/view/theme/'.$this->config->get('config_template');
			
			$this->document->addScript('catalog/view/javascript/ajaxtestimonial.js');
			$this->document->addStyle($this->data['template_path'].'/stylesheet/ajaxtestimonial.css');
			$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
			
			$ajaxtestimonial_total = $this->model_catalog_ajaxtestimonial->getTotalajaxtestimonials();
			
			if ($this->customer->isLogged()) {
				$this->data['need_captcha'] = false;
				}else{
				$this->data['need_captcha'] = true;
			}
			
			$this->data['captcha_reply'] = false;
			
	  		$this->document->SetTitle($this->language->get('heading_title'));
			
	   		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('product/ajaxtestimonial', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
	   		);	
			
			
      		$this->data['heading_title'] = $this->language->get('heading_title');
			
      		$this->data['showall'] = $this->language->get('text_showall');
			$this->data['showall_url'] = $this->url->link('product/ajaxtestimonial', '', 'SSL');
      		$this->data['write'] = $this->language->get('text_write');
      		$this->data['text_average'] = $this->language->get('text_average');
      		$this->data['text_stars'] = $this->language->get('text_stars');
      		$this->data['text_no_rating'] = $this->language->get('text_no_rating');
			$this->data['text_empty'] = $this->language->get('text_empty');
			$this->data['default_rating'] = $this->config->get('ajaxtestimonial_default_rating');
			
      		$this->data['text_leave'] = $this->language->get('text_leave');
			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_telephone'] = $this->language->get('entry_telephone');
			$this->data['entry_email'] = $this->language->get('entry_email');
			$this->data['entry_rating'] = $this->language->get('entry_rating');
			$this->data['entry_message'] = $this->language->get('entry_message');
			$this->data['text_button'] = $this->language->get('text_button');
			
			$this->data['text_replies'] = $this->language->get('text_replies');
			$this->data['text_reply'] = $this->language->get('text_reply');
			$this->data['text_newreply'] = $this->language->get('text_newreply');
			$this->data['text_sort'] = $this->language->get('text_sort');
			$this->data['entry_rname'] = $this->language->get('entry_rname');
			$this->data['entry_reply'] = $this->language->get('entry_reply');
			
			$this->data['entry_captcha'] = $this->language->get('entry_captcha');
			$this->data['error_captcha'] = $this->language->get('error_captcha');
			
			if (isset($this->request->post['captcha'])) {
				$this->data['captcha'] = $this->request->post['captcha'];
				} else {
				$this->data['captcha'] = '';
			}		
			
			if ( isset($this->request->get['ajaxtestimonial_id']) ){
				$this->data['single_review'] = true;
				}else{
				$this->data['single_review'] = false;
			}
			
			if ($this->config->get('ajaxtestimonial_admin_loadmore') == ''){
				$this->data['loadmore_button'] = false;
				}else{
				$this->data['loadmore_button'] = true;
			}
			
			$this->data['lang_id'] = $this->config->get('config_language_id');
			
			if (isset($this->session->data['token'])){
				$this->data['token'] = $this->session->data['token'];
			}
			
			$ajaxtestimonial_all_page_limit = $this->config->get('ajaxtestimonial_all_page_limit');
			if (!isset($ajaxtestimonial_all_page_limit)) $ajaxtestimonial_all_page_limit = "20";
			
		    $this->data['load_more'] = sprintf($this->language->get('text_loadmore'), $ajaxtestimonial_all_page_limit);
			
			$this->page_limit = $ajaxtestimonial_all_page_limit;
			
			$this->data['ajaxtestimonials'] = array();
			
			$data = array(
			'start'  => ($page - 1) * $this->page_limit,
			'limit'  => $this->page_limit,
			'sort'   => $sort,
			'order'  => $order,
			'random' => false
			);
			
			if ( isset($this->request->get['ajaxtestimonial_id']) ){
				$results = $this->model_catalog_ajaxtestimonial->getajaxtestimonial($this->request->get['ajaxtestimonial_id']);
		  		$this->document->SetTitle (strip_tags($results[0]['description']));
			}
			else{
			    $results = $this->model_catalog_ajaxtestimonial->getajaxtestimonials($data);
			}
			
			// Cookie
			$cookie_name = 'TestimonialVote';
			if (isset($_COOKIE[$cookie_name])){
				$vote_explode = explode(":", $_COOKIE[$cookie_name]);
			}
			
			foreach ($results as $result) {
				$my_replys = array();
				$reply = array();
				$my_replys = $this->model_catalog_ajaxtestimonial->getajaxtestimonialsReply($result['ajaxtestimonial_id']);
				
				if ($my_replys){
					foreach ($my_replys as $my_reply) {
						$reply[] = array(
						'id'		=> $my_reply['ajaxtestimonial_id'],
						'name'		=> $my_reply['name'],
						'description'	=> strip_tags($my_reply['description'], '<br><p><b>'),
						'date_added'	=> date($this->config->get('ajaxtestimonial_all_page_date_format'), strtotime($result['date_added']))
						);
					}
				}
				
				// Cookie
				$voted = true;
				if (isset($_COOKIE[$cookie_name])){
					if (in_array($result['ajaxtestimonial_id'], $vote_explode)){
						$voted = false;
					}
				}
				
				$this->data['ajaxtestimonials'][] = array(
				'voted'         => $voted,
			    'reply'         => $reply,
				'parent_id'		=> $result['parent_testimonial_id'],
	            'rating'		=> $result['rating'],
				'id'		    => $result['ajaxtestimonial_id'],
				'name'		    => $result['name'],
				'rating'		=> $result['rating'],
				'description'	=> strip_tags($result['description'], '<br><p><b>'),
				'likes'		    => $result['likes'],
				'unlikes'		=> $result['unlikes'],
				'date_added'	=> date($this->config->get('ajaxtestimonial_all_page_date_format'), strtotime($result['date_added']))
				);
			}
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['sorts'] = array();
			
			$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_date_desc'),
			'value' => 't.date_added-DESC',
			'href'  => $this->url->link('product/ajaxtestimonial&sort=t.date_added&order=DESC' . $url)
			);
			
			$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_date_asc'),
			'value' => 't.date_added-ASC',
			'href'  => $this->url->link('product/ajaxtestimonial&sort=t.date_added&order=ASC' . $url)
			);
			
			$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_rating_desc'),
			'value' => 't.rating-DESC',
			'href'  => $this->url->link('product/ajaxtestimonial&sort=t.rating&order=DESC' . $url)
			); 
			
			$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_rating_asc'),
			'value' => 't.rating-ASC',
			'href'  => $this->url->link('product/ajaxtestimonial&sort=t.rating&order=ASC' . $url)
			);
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $ajaxtestimonial_total;
			$pagination->page = $page;
			$pagination->limit = $this->page_limit; 
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('product/ajaxtestimonial', $url . '&page={page}', 'SSL');
			$this->data['pagination'] = $pagination->render();	
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['page_limit'] = $this->page_limit;
			$this->data['page_total'] = $num_pages = ceil($ajaxtestimonial_total / $this->page_limit);
			$this->data['page_current'] = $page;
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/ajaxtestimonial.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/ajaxtestimonial.tpl';
				} else {
				$this->template = 'default/template/product/ajaxtestimonial.tpl';
			}
			
			$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
			);		
			
	  		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		}
		
		public function reply() {
			$this->language->load('product/ajaxtestimonial');
			$this->language->load('product/ajaxtestimonial_form');
			$this->load->model('catalog/ajaxtestimonial');
			
			$json = array();
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				
				if ((utf8_strlen($this->request->post['reply_name']) < 3) || (utf8_strlen($this->request->post['reply_name']) > 32)) {
					$json['error'][] = $this->language->get('error_name');
					$json['input'][] = 'reply_name';
				}
				
				if ((utf8_strlen($this->request->post['reply_description']) < 8) || (utf8_strlen($this->request->post['reply_description']) > 1000)) {
					$json['error'][] = $this->language->get('error_description');
					$json['input'][] = 'reply_description';
				}
				
				if (isset($this->request->post['captcha']) && $this->request->post['captcha'] != '') {
					if (!isset($this->request->post['captcha'])) {
						$this->request->post['captcha'] = "";
					}
					
					if (!$this->customer->isLogged()) {	
						if (!isset($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
							$json['error'][] = $this->language->get('error_captcha');
							$json['input'][] = 'captcha';
						}
					}
				}
				
				if (!isset($json['error'])) {
					
					$this->data['data'] = array();
					
					$this->data['data']['name'] = strip_tags(html_entity_decode($this->request->post['reply_name']));
					$this->data['data']['description'] = strip_tags(html_entity_decode($this->request->post['reply_description']));
					$reply_id = $this->request->post['reply_id'];
					
					if ($this->config->get('ajaxtestimonial_admin_approved') == '') {
						$this->model_catalog_ajaxtestimonial->addajaxtestimonialReply($this->data['data'], 1, $reply_id);
						$json['success'] = $this->language->get('text_success');
						$json['approved_off'] = 'true';
					}else{
						$this->model_catalog_ajaxtestimonial->addajaxtestimonialReply($this->data['data'], 0, $reply_id);
						$json['success'] = $this->language->get('text_success_approved');
						$json['approved_off'] = 'false';
					}
					$json['name'] = $this->data['data']['name'];
					$json['description'] = $this->data['data']['description'];
					$json['date'] = date($this->config->get('ajaxtestimonial_all_page_date_format'));
					
					// Send author email
					$email = "empty";
					
					$sender = $this->data['data']['name'];
					if ($sender == "") $sender = "empty";
					
					$store_name = $this->config->get('config_name');
					
					$subject = sprintf($this->language->get('reply_subject'), $reply_id, $store_name);
					$message  = '<html>'.$this->language->get('reply_header') . "<br>";
					$message .= "<br>";
					$message .= "<b>". $this->language->get('entry_name') . "</b> " . $this->data['data']['name'];
					$message .= "<br><br>";
					$message .= "<b>". $this->language->get('entry_reply') . ":</b> ";
					$message .= $this->data['data']['description']. "<br>";
					$message .= "<br>";
					$message .= $this->language->get('reply_footer')."</html>";
					
					// Send admin email
					if ($this->config->get('ajaxtestimonial_send_to_admin') != '') {
						$mail = new Mail();
						$mail->protocol = $this->config->get('config_mail_protocol');
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->hostname = $this->config->get('config_smtp_host');
						$mail->username = $this->config->get('config_smtp_username');
						$mail->password = $this->config->get('config_smtp_password');
						$mail->port = $this->config->get('config_smtp_port');
						$mail->timeout = $this->config->get('config_smtp_timeout');
						$mail->setFrom($email);
						$mail->setTo($this->config->get('config_email'));
						$mail->setSender($sender);
						$mail->setSubject($subject);
						$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
						$mail->send();
					}	
					
				}
			}			
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function vote() {
			
			$this->language->load('product/ajaxtestimonial');
			$this->load->model('catalog/ajaxtestimonial');
			
			$json = array();
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				
				$json['id'] = stripslashes($this->request->post['id']);
				$json['vote'] = $this->request->post['vote'];
				
				$cookie_name = 'TestimonialVote';
				if (isset($_COOKIE[$cookie_name])){
					$explode = explode(":", $_COOKIE[$cookie_name]);
					
					if (!in_array($json['id'], $explode)){
						setcookie($cookie_name, $_COOKIE[$cookie_name].':'.$json['id'], time() + (86400 * 12), '/');	
						$this->model_catalog_ajaxtestimonial->updateAjaxtestimonialVote($json['id'], $json['vote']);
					}
					
					}else{
					setcookie($cookie_name, $json['id'], time() + (86400 * 12), '/');
					$this->model_catalog_ajaxtestimonial->updateAjaxtestimonialVote($json['id'], $json['vote']);
				}
				
			}			
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function captcha() {
			$this->load->library('captcha');
			
			$captcha = new Captcha();
			
			$this->session->data['captcha'] = $captcha->getCode();
			
			$captcha->showImage();
		}
		
	}	
?>