<?php
class ControllerExtensionModuleEvents extends Controller {
	public function index() {

		$this->load->model('extension/module/event');
        $this->load->language('extension/module/event');
		
		$results=$this->model_extension_module_event->getModule();

		$data['module_events']=[];

		foreach ($results as $result){
			$status=json_decode($result['setting']);
			$data['module_events'][] = array(
				'name'    => $result['name'],
				'status'    => $status->status
			);
		}

		foreach ($data['module_events'] as $result){
			if($result['status']=='1'){
				if ($result['name']=='Events slider'){$this->slider();}
				if ($result['name']=='Events calendar'){$this->calendar();}
			}
		}		
		
	}
	public function slider() {
		var_dump('slider');die;
		
	}
	public function calendar() {
		var_dump('calendar');die;
		
	}

}