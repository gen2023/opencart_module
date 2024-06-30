<?php
class ControllerExtensionModuleGenNewsletter extends Controller
{
	public function index()
	{
		$this->load->language('extension/module/genNewsletter');
		$this->load->model('extension/module/genNewsletter');


		$data['heading_title'] = $this->language->get('heading_title');
		$data['name'] = $this->language->get('name');
		$data['town'] = $this->language->get('town');
		$data['phone'] = $this->language->get('phone');

		$data['but_newsletter'] = $this->language->get('but_newsletter');

		$setting_newsLetter = json_decode($this->config->get('module_genNewsletter_settings'));

		foreach ($setting_newsLetter as $key => $value) {
			$data[$key] = $value;
		}
		$array = array();
		if ($data['political_text']) {
			foreach ($data['political_text'] as $key => $value) {
				$array[$key] = $value->text;
			}
		}
		$data['text'] = html_entity_decode($array[$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');

		if($data['showUserRegister']==1){
			return $this->load->view('extension/module/genNewsletter', $data);
		}else{
			if (!$this->customer->getFirstName()){
				return $this->load->view('extension/module/genNewsletter', $data);
			}else{
				return '';
			}
		}
	}
	public function sendMailSubscribe() {
		$this->load->language('extension/module/genNewsletter');
		$this->load->model('extension/module/genNewsletter');

		$data['error_email'] = $this->language->get('error_email');
		$data['error_town'] = $this->language->get('error_town');
		$data['error_name'] = $this->language->get('error_name');
		$data['error_email2'] = $this->language->get('error_email2');
		$data['error_phone'] = $this->language->get('error_phone');

		$data['text_success'] = $this->language->get('text_success');
		$data['text_dublicateEmail'] = $this->language->get('text_dublicateEmail');
		$data['text_dublicatePhone'] = $this->language->get('text_dublicatePhone');
		$data['text_error'] = $this->language->get('text_error');
		$required_field = $this->config->get('module_genNewsletter_required_field');

		$json = array();
		$json['error'] = '';

		$setting_newsLetter = json_decode($this->config->get('module_genNewsletter_settings'));

		if (($setting_newsLetter->showEmail == 1) && ($setting_newsLetter->reqEmail == 1) && (($this->request->post['email']) == '')) {
			$json['error'] = $data['error_email2'];
		}
		if (($setting_newsLetter->showName == 1) && ($setting_newsLetter->reqName == 1) && (($this->request->post['name']) == '')) {
			$json['error'] = $data['error_name'];
		}
		if (($setting_newsLetter->showTown == 1) && ($setting_newsLetter->reqTown == 1) && (($this->request->post['town']) == '')) {
			$json['error'] = $data['error_town'];
		}
		if (($setting_newsLetter->showPhone == 1) && ($setting_newsLetter->reqPhone == 1) && (($this->request->post['phone']) == '')) {
			$json['error'] = $data['error_phone'];
		}

		if ($json['error'] == '') {

			$result = $this->model_extension_module_genNewsletter->is_subscribes($required_field, $this->request->post);

			switch ($result) {
				case 0:
					$json['message'] = $data['text_error'];
					break;
				case 1:
					$json['message'] = $data['text_success'];
					break;
				case 2:
					$json['message'] = $data['text_dublicateEmail'];
					break;
				case 3:
					$json['message'] = $data['text_dublicatePhone'];
					break;
			}
		}
		if($result==1){
			$result = $this->model_extension_module_genNewsletter->getTextForMail();
					
			$result_decode=json_decode($result['value'], true);

			$textMail1=$result_decode['text_mail1'];
			// $textMail2=$result_decode['text_mail2'];

			
			$email=$this->request->post['email'];			
			// $coupon=substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(6 / strlen($x)))), 1, 6);
			// $this->model_extension_module_genNewsletter->addCoupon($coupon);



			$this->sendMail($email,$textMail1);

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	protected function sendMail($email,$textMail,$coupon='') {

		$currentLang=$this->config->get('config_language_id');


			$message=str_replace(['{{coupon}}','{{email}}'],[$coupon,$email],$textMail[$currentLang]['description']);
		
			
			$data['text_message']=html_entity_decode($message, ENT_QUOTES, 'UTF-8');
	
			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
	
			$mail->setTo($email);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')));
	
			$mail->setHtml($data['text_message']);
			$mail->send(); 
		





	}
	public function subscribe(){

		

		$this->load->model('extension/module/genNewsletter');

		$email=$this->request->get['email'];
		$coupon=substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(6 / strlen($x)))), 1, 6);
		$this->model_extension_module_genNewsletter->addCoupon($coupon);

		$result = $this->model_extension_module_genNewsletter->getTextForMail();
		$result_decode=json_decode($result['value'], true);
		$textMail2=$result_decode['text_mail2'];


		$this->sendMail($email,$textMail2,$coupon);

		$result = $this->model_extension_module_genNewsletter->subscribes($email);


		$this->response->setOutput($this->load->view('common/home'));
		


	}
	protected function setSubscribe()
	{
		$this->load->language('extension/module/genNewsletter');
		$this->load->model('extension/module/genNewsletter');

		$data['error_email'] = $this->language->get('error_email');
		$data['error_town'] = $this->language->get('error_town');
		$data['error_name'] = $this->language->get('error_name');
		$data['error_email2'] = $this->language->get('error_email2');
		$data['error_phone'] = $this->language->get('error_phone');

		$data['text_success'] = $this->language->get('text_success');
		$data['text_dublicateEmail'] = $this->language->get('text_dublicateEmail');
		$data['text_dublicatePhone'] = $this->language->get('text_dublicatePhone');
		$data['text_error'] = $this->language->get('text_error');
		$required_field = $this->config->get('module_genNewsletter_required_field');

		$json = array();
		$json['error'] = '';

		$setting_newsLetter = json_decode($this->config->get('module_genNewsletter_settings'));


		if (($setting_newsLetter->showEmail == 1) && ($setting_newsLetter->reqEmail == 1) && (($this->request->post['email']) == '')) {
			$json['error'] = $data['error_email2'];
		}
		if (($setting_newsLetter->showName == 1) && ($setting_newsLetter->reqName == 1) && (($this->request->post['name']) == '')) {
			$json['error'] = $data['error_name'];
		}
		if (($setting_newsLetter->showTown == 1) && ($setting_newsLetter->reqTown == 1) && (($this->request->post['town']) == '')) {
			$json['error'] = $data['error_town'];
		}
		if (($setting_newsLetter->showPhone == 1) && ($setting_newsLetter->reqPhone == 1) && (($this->request->post['phone']) == '')) {
			$json['error'] = $data['error_phone'];
		}

		if ($json['error'] == '') {

			$result = $this->model_extension_module_genNewsletter->subscribes($required_field, $this->request->post);

			switch ($result) {
				case 0:
					$json['message'] = $data['text_error'];
					break;
				case 1:
					$json['message'] = $data['text_success'];
					break;
				case 2:
					$json['message'] = $data['text_dublicateEmail'];
					break;
				case 3:
					$json['message'] = $data['text_dublicatePhone'];
					break;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}
	public function unsubscribe()
	{
		$this->load->language('extension/module/genNewsletter');
		$this->load->model('extension/module/genNewsletter');

		$data['error_email'] = $this->language->get('error_email');
		$data['error_town'] = $this->language->get('error_town');
		$data['error_name'] = $this->language->get('error_name');
		$data['error_email2'] = $this->language->get('error_email2');
		$data['error_phone'] = $this->language->get('error_phone');

		$data['text_success'] = $this->language->get('text_unsubscribe');
		$data['text_error'] = $this->language->get('text_error_unsubscribe');

		$required_field = $this->config->get('module_genNewsletter_required_field');

		$json = array();
		$json['error'] = '';

		$setting_newsLetter = json_decode($this->config->get('module_genNewsletter_settings'));


		if (($setting_newsLetter->showEmail == 1) && ($setting_newsLetter->reqEmail == 1) && (($this->request->post['email']) == '')) {
			$json['error'] = $data['error_email2'];
		}
		if (($setting_newsLetter->showName == 1) && ($setting_newsLetter->reqName == 1) && (($this->request->post['name']) == '')) {
			$json['error'] = $data['error_name'];
		}
		if (($setting_newsLetter->showTown == 1) && ($setting_newsLetter->reqTown == 1) && (($this->request->post['town']) == '')) {
			$json['error'] = $data['error_town'];
		}
		if (($setting_newsLetter->showPhone == 1) && ($setting_newsLetter->reqPhone == 1) && (($this->request->post['phone']) == '')) {
			$json['error'] = $data['error_phone'];
		}

		if ($json['error'] == '') {

			$result = $this->model_extension_module_genNewsletter->unsubscribes($this->request->post);

			switch ($result) {
				case 0:
					$json['message'] = $data['text_error'];
					break;
				case 1:
					$json['message'] = $data['text_success'];
					break;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}
}
