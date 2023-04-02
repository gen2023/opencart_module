<?php  
class ControllerExtensionModuleGenVoting extends Controller {
	public function index($setting) {
/*
array(6) { 
["apply"]=> string(0) "" 
["module_id"]=> string(2) "38" 
["name"]=> string(21) "lembergcaviar-romanko" 
["title_module"]=> array(2) { [1]=> array(1) { ["name"]=> string(25) "Заголовок рус" } [2]=> array(1) { ["name"]=> string(27) "Заголовок англ" } } 
["voting_attributes"]=> array(3) { 
	[0]=> array(2) { 
		[1]=> array(1) { ["text"]=> string(1) "1" } 
		[2]=> array(1) { ["text"]=> string(1) "2" } } 
	[1]=> array(2) { 
		[1]=> array(1) { ["text"]=> string(1) "3" } 	
		[2]=> array(1) { ["text"]=> string(1) "4" } } 
	[2]=> array(2) { 
		[1]=> array(1) { ["text"]=> string(1) "5" } 
		[2]=> array(1) { ["text"]=> string(1) "6" } } } 
["status"]=> string(1) "1" }
*/

//при нажатии на кнопку голосовать добавляются куки и если стоит галочка непоказывать результаты через проверку куки в контроллере и галочку непоказывать скрыть модуль с глаз долой, если показывать результаты тогда заменить форму на форму показа результатов и в дальнейшем выводить фору показа результатов

		$this->load->language('extension/module/genVoting'); 
		$this->document->addStyle('catalog/view/theme/default/stylesheet/genVoting.css');
		
		$data['text_btn'] = $this->language->get('text_btn');
		$data['text_result'] = $this->language->get('text_edit');
		$data['error_voting'] = $this->language->get('error_voting');
		$data['lang_id']=$this->config->get('config_language_id');
		
		//var_dump($setting);
		$data['heading_title'] = $setting['title_module'][$data['lang_id']]['name']; 
		$data['module_id']=$setting['module_id'];
		$data['voting_attributes']=$setting['voting_attributes'];
		$data['type_module']=$setting['type_module'];
		$data['viewResult']=$setting['viewResult'];
		$data['displayNone']='display:block;';

		return $this->load->view('extension/module/genVoting', $data);

	}
	
	public function setValueVoting(){
		$this->load->model('extension/module/genVoting');
		$this->load->language('extension/module/genVoting'); 
		
		$data['text_success'] = $this->language->get('text_success');
	
		$json = array();
		$result = $this->model_extension_module_genVoting->editValue($this->request->post);

		$json['message']=$data['text_success'];
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function getResultVoting(){
		
		$this->load->model('extension/module/genVoting');
		//$this->load->language('extension/module/genVoting'); 
		
		//$data['text_success'] = $this->language->get('text_success');
	
		$json = array();
		$result = $this->model_extension_module_genVoting->getResult($this->request->post);

		$json['result']=$result;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
