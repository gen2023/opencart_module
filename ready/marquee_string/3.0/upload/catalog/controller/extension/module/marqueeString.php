<?php  
class ControllerExtensionModuleMarqueeString extends Controller {
		public function index($data) {	

		$data['string'] = html_entity_decode($data['string'][$this->config->get('config_language_id')]['string'], ENT_QUOTES, 'UTF-8');
		
		if (!$data['delayBeforeStart']){$data['delayBeforeStart']=1000;}
		if (!$data['duration']){$data['duration']=5000;}
		if (!$data['speed']){$data['speed']=0;}
		if (!$data['pauseOnCycle']){$data['pauseOnCycle']=0;}
		if (!$data['gap']){$data['gap']=20;}
		if (!$data['width']){$data['width']='100%';}
		if (!$data['height']){$data['height']='20';}
		if (!$data['amount']){$data['amount']='2';}			
		if (!$data['textBg']){$data['textBg']='#ffffff';}
		if (!$data['textColor']){$data['textColor']='#000000';}		
		if (!$data['fontSize']){$data['fontSize']='14';}
		if (!$data['css']){$data['css']='';}		

		switch ($data['direction']) {
			case 0:
				$data['direction']="right";
				break;
			case 1:
				$data['direction']= "left";
				break;
			case 2:
				$data['direction']= "up";
				break;
			case 3:
				$data['direction']= "down";
				break;
		}
		
		$this->document->addStyle('catalog/view/javascript/jquery.marquee/1.4.0/css/style.css');
		$this->document->addScript('catalog/view/javascript/jquery.marquee/1.4.0/js/jquery.marquee.js');		
		return $this->load->view('extension/module/marqueeString', $data);
	}
}