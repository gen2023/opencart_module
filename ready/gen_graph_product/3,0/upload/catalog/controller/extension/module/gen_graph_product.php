<?php  
class ControllerExtensionModuleGenGraphProduct extends Controller {
	public function index() {

		//$this->load->language('extension/module/gen_graph_product'); 
		$this->load->model('extension/module/gen_graph_product');


		$results = $this->model_extension_module_gen_graph_product->getWork($this->request->get['product_id']);		
		
		// Правило, по которому будут сравниваться строки
		function cmp($a, $b) { 
		  return strnatcmp($a["sort_order"], $b["sort_order"]); 
		} 

		// Сама функция сортировки 
		usort($results, "cmp");
		//var_dump($result);

		$data['works']=array();
		//var_dump($result[0]);
		foreach ($results as $result) {
			$data['works'][] = array(
				'work_id'     	=> $result['work_id'],
				'color'       	=> $result['color'],
				'month_start'   => $result['month_start'],
				'month_end'     => $result['month_end'],
				'sort_order'    => $result['sort_order'],
				'title'       	=> $result['title']				
			);
		}
		
		return $this->load->view('extension/module/gen_graph_product', $data);		

	}
}
