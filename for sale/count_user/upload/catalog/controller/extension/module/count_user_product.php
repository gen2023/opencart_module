<<<<<<< HEAD
<?php  
class ControllerExtensionModuleCountUserProduct extends Controller {
	public function index() {
		
		if ($this->config->get('module_count_user_status')){
			
			$this->load->language('extension/module/count_user');

			$this->load->model('extension/module/count_user');

			if(getenv('HTTP_X_FORWARDED_FOR')){
				$current_user=getenv('HTTP_X_FORWARDED_FOR');
			} else {
				$current_user=getenv('REMOTE_ADDR');
			}

			$nowTime=time();
			$current_day=date('d.m.Y', $nowTime);
			
			$flag=0;
			$item_id = $this->request->get['product_id'];
			$results=$this->model_extension_module_count_user->getUserProduct($item_id);

			$users = array();
			$countDay=0;

			foreach ($results as $result) {
				$users[] = array(
						'user_id'  	=> $result['user_id'],
						'ip_user'   => $result['ip_user'],
						'time'     	=> $result['time']
					);
			}

			foreach ($users as $user) {

				if ($user['ip_user']==$current_user){
					
					if(date('d.m.Y', $user['time'])==$current_day){
						$countDay +=1;						
					}
						$this->model_extension_module_count_user->updateUser($user['user_id'],$nowTime);
						$flag=1;					
				}
				
			}

			if ($flag!=1){
				$this->model_extension_module_count_user->addUserProduct($current_user,$nowTime,$item_id);
			}
			$data['view2']=count($results);
			$data['view1']=$countDay;

			return $this->load->view('extension/module/count_user', $data);
		}		

	}
}
=======
<?php  
class ControllerExtensionModuleCountUserProduct extends Controller {
	public function index() {
		
		if ($this->config->get('module_count_user_status')){
			
			$this->load->language('extension/module/count_user');

			$this->load->model('extension/module/count_user');

			if(getenv('HTTP_X_FORWARDED_FOR')){
				$current_user=getenv('HTTP_X_FORWARDED_FOR');
			} else {
				$current_user=getenv('REMOTE_ADDR');
			}

			$nowTime=time();
			$current_day=date('d.m.Y', $nowTime);
			
			$flag=0;
			$item_id = $this->request->get['product_id'];
			$results=$this->model_extension_module_count_user->getUserProduct($item_id);

			$users = array();
			$countDay=0;

			foreach ($results as $result) {
				$users[] = array(
						'user_id'  	=> $result['user_id'],
						'ip_user'   => $result['ip_user'],
						'time'     	=> $result['time']
					);
			}

			foreach ($users as $user) {

				if ($user['ip_user']==$current_user){
					
					if(date('d.m.Y', $user['time'])==$current_day){
						$countDay +=1;						
					}
						$this->model_extension_module_count_user->updateUser($user['user_id'],$nowTime);
						$flag=1;					
				}
				
			}

			if ($flag!=1){
				$this->model_extension_module_count_user->addUserProduct($current_user,$nowTime,$item_id);
			}
			$data['view2']=count($results);
			$data['view1']=$countDay;

			return $this->load->view('extension/module/count_user', $data);
		}		

	}
}
>>>>>>> 8841f7ebec4bd47799d1024a24781a11759b4854
