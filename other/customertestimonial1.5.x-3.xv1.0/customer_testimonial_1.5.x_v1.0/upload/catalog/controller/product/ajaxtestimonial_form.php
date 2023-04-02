<?php 
	class ControllerProductAjaxTestimonialForm extends Controller {
		private $error = array(); 
		
		public function index() {
			$json = array();
			
			$this->language->load('product/ajaxtestimonial_form');
			
			$this->load->model('catalog/ajaxtestimonial');
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				
				if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
					$json['error'][] = $this->language->get('error_name');
					$json['input'][] = 'name';
				}
				
				if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
					$json['error'][] = $this->language->get('error_email');
					$json['input'][] = 'email';
				}	
				
				if ((utf8_strlen($this->request->post['description']) < 8) || (utf8_strlen($this->request->post['description']) > 1000)) {
					$json['error'][] = $this->language->get('error_description');
					$json['input'][] = 'description';
				}
				
				
				if (!isset($this->request->post['captcha'])){
					$this->request->post['captcha'] = "";
				}
				
				if (!$this->customer->isLogged()){	
					if (!isset($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
						$json['error'][] = $this->language->get('error_captcha');
						$json['input'][] = 'captcha';
					}
				}
				
				if (!isset($json['error'])) {
					
					$this->data['data'] = array();
					
					$this->data['data']['name'] = strip_tags(html_entity_decode($this->request->post['name']));
					$this->data['data']['phone'] = strip_tags(html_entity_decode($this->request->post['phone']));
					$this->data['data']['rating'] = $this->request->post['rating'];				
					$this->data['data']['email'] = strip_tags(html_entity_decode($this->request->post['email']));
					$this->data['data']['description'] = strip_tags(html_entity_decode($this->request->post['description']));
					
					if ($this->config->get('ajaxtestimonial_admin_approved') == ''){
						
						$lastid = $this->model_catalog_ajaxtestimonial->addajaxtestimonial($this->data['data'], 1);
						$json['success'] = $this->language->get('text_success');
						
					}else{
						
						$lastid = $this->model_catalog_ajaxtestimonial->addajaxtestimonial($this->data['data'], 0);
						$json['success'] = $this->language->get('text_success_approved');
					}
					
					// Send author email
					$email = $this->data['data']['email'];
					if ($email == "") $email = "empty";
					
					$sender = $this->data['data']['name'];
					if ($sender == "") $sender = "empty";
					
					$store_name = $this->config->get('config_name');
					
					$subject = sprintf($this->language->get('text_subject'), $sender, $store_name);
					$message  = '<html>'.$this->language->get('text_header') . "<br>";
					$message .= "<br>";
				    $message .= "<b>". $this->language->get('entry_name') . "</b> " . $this->data['data']['name'];
					$message .= "<br><br>";
					$message .= '<b>Email:</b> ' . $this->data['data']['email'];
					$message .= "<br><br>";
					$message .= "<b>". $this->language->get('entry_message') . "</b> ";					
					$message .= $this->data['data']['description']. "<br>";
					$message .= "<br>";
					$message .= $this->language->get('text_footer')."</html>";
					
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
		
	}
?>
