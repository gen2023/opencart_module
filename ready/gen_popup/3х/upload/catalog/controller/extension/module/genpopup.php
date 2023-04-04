<?php
class ControllerExtensionModuleGenpopup extends Controller {
	public function index($setting) {

		if ($setting['viewSecond']){$data['viewSecond']=$setting['viewSecond'] . '000';}
		if ($setting['closeModalSecond']){$data['closeModalSecond']=$setting['closeModalSecond'] . '000';}
		$data['closeModal']=$setting['closeModal'];
		$data['countView']=$setting['countView'];
		$data['cookie']=$setting['cookie'];
		$data['radius']=$setting['radius'];
		$data['height']=$setting['height'];
		$data['width']=$setting['width'];
		$data['modalMobile']=$setting['modalMobile'];
		$data['module_id']=$setting['module_id'];
		$data['view_user']=$setting['view_user'];
		$data['validity']=$setting['validity'];
		$data['content'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');
		$data['logged'] = $this->customer->isLogged();
		if ($setting['styleBackground']==0){
			$data['background']='background:' . $setting['color'];
		} else{
			$data['background']='background:url(image/' . $setting['background'] .') no-repeat;background-size:contain;';
		}
		
		$this->document->addStyle('catalog/view/theme/default/stylesheet/genpopup.css');
		
		$getLogget=1;
		$getDay=1;
//var_dump($data['validity']);
		switch($setting['viewLogged']){
			case 1:
				$getLogget=1;
				break;
			case 2: 
				$data['logged'] ? $getLogget=1 : $getLogget=0;				
				break;
			case 3:
				$data['logged'] ? $getLogget=0 : $getLogget=1;	
				break;
			default:
				$getLogget=0;
		}
		
		if(!isset($setting['timezone'])){
			$setting['timezone']=0;
		}
		$data['viewed']=1;
		$currentTime=(int)preg_replace('/[^0-9]/', '', date("H:i",strtotime($setting['timezone'].' hour'))); 
		$timeStartModal=(int)preg_replace('/[^0-9]/', '', $setting['time_to']);
		$timeEndModal=(int)preg_replace('/[^0-9]/', '', $setting['time_from']);
	//var_dump($data['cookie']);
		if(isset($_COOKIE["genpopup_".$data['module_id']]) && isset($data['cookie'])){
			$resultCook=explode(',',$_COOKIE["genpopup_".$data['module_id']]);
			if ($resultCook[1]==$data['countView']){
				$setting['ifViewModal']=6;
			}else{$data['viewed']=$resultCook[1]+1;}
		}else{
			if($setting['ifViewModal']==4){
				if (($timeStartModal>$timeEndModal) && ($timeStartModal<$currentTime) && ($timeEndModal<$currentTime)){ 
					$setting['ifViewModal']=5;
				}else if (($timeStartModal>$timeEndModal) && ($timeStartModal>$currentTime) && ($timeEndModal>$currentTime)){
					$setting['ifViewModal']=5;
				}else if($timeStartModal<$timeEndModal){
					$setting['ifViewModal']=4;
				}else{
					$setting['ifViewModal']=6;
				}
			}
		}
		switch($setting['ifViewModal']){
			case 1:
				$getDay=1;
				break;
			case 2: 
				date("d")%2 == 0 ? $getDay=1 : $getDay=0;				
				break;
			case 3:
				date("d")%2 == 0 ? $getDay=0 : $getDay=1;	
				break;
			case 4:
				($timeStartModal<$currentTime) && ($timeEndModal>$currentTime) ? $getDay=1 : $getDay=0;
				break;
			case 5:
				$getDay=1;
				break;
			case 6:
				$getDay=0;
				break;
			default:
				$getDay=0;
		}
		if(($getLogget==1) && ($getDay==1)){
			return $this->load->view('extension/module/genpopup', $data);
		}else{
			return 0;
		}
	
		
	}
	
	public function addView(){
			
		$this->load->model('extension/module/genpopup');
	
		$json = array();
		$result = $this->model_extension_module_genpopup->addView($this->request->post);

		$json['message']=$result;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
			
	}
}