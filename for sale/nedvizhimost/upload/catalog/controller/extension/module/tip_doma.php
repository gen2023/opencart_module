<?php  
class ControllerExtensionModuleTipDoma extends Controller {
	public function index() {

		$this->load->language('extension/module/gen_filter_product'); 
		$this->load->model('extension/module/gen_filter_product');
		
		$data['heading_title'] = $this->language->get('heading_title'); 
	
		$results = $this->model_extension_module_gen_filter_product->getProductAdressD($this->request->get['product_id']);

		$data['homes'] = array();

		foreach ($results as $result) {
			$seria=$this->model_extension_module_gen_filter_product->getSeriasName($result['seria_id']);

			$data['homes'][] = array(
				'addressD'          => $this->model_extension_module_gen_filter_product->getAddressDomaName($result['address_doma_id']),
				'seriaD'          	=> $seria['name'],
				'href'        			=> $this->url->link('product/product', 'product_id=' . $seria['product_id']),
				'floorD'            => $this->model_extension_module_gen_filter_product->getFloorDomaName($result['floor_doma_id']),
				'yearD'        			=> $this->model_extension_module_gen_filter_product->getYearDomaName($result['year_doma_id']),
				'typeD'          		=> $this->model_extension_module_gen_filter_product->getTypesDomaName($result['type_doma_id'])
			);
		}

		return $this->load->view('extension/module/tip_doma', $data);
	}
}
