<?php  
class ControllerExtensionModuleGenFilterProduct extends Controller {
	public function index() {

		$this->load->language('extension/module/gen_filter_product'); 
		$data['heading_title'] = $this->language->get('heading_title'); 

		return $this->load->view('extension/module/gen_filter_product', $data);


	}
	
	public function search(){
		$this->load->language('extension/module/gen_filter_product'); 
		$this->load->model('extension/module/gen_filter_product');
		$data['text_table_name'] = $this->language->get('text_table_name'); 
		$data['text_table_series'] = $this->language->get('text_table_series'); 
		$data['text_table_floor'] = $this->language->get('text_table_floor'); 
		$data['text_table_year'] = $this->language->get('text_table_year'); 
		$data['text_table_type'] = $this->language->get('text_table_type'); 
		
		//var_dump($this->request->post['search']);
		
		
		//$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		//$this->load->model('tool/image');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
		}		
		
		//$category_id=18;
		$category_id=$this->config->get('module_gen_filter_product_category_id');
		//var_dump($category_id); 
		$product_name=$this->request->post['search'];
		$this->request->get['path']=$category_id;
		
		$filter_data = array(
				'filter_category_id' => $category_id,
				'filter_name'      	 => $product_name,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);
		
		//$results=$this->model_catalog_product->getProducts($filter_data);
		$results=$this->model_extension_module_gen_filter_product->getProducts($filter_data);
		
		//var_dump($results);
		
		$data['products'] = array();

		foreach ($results as $result) {

			$data['products'][] = array(
				'product_id'  => $result['product_id'],
				'name'        => $result['name'],
				//'hrefSeria'        => $this->url->link('extension/module/tip_doma', 'product_id=' . $result['product_id']),
				'hrefPdoduct'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
			);
		}
		
		$json = '';
		$table="";
		
		if($data['products']){
			$table ='<table border="1" width="100%" class=""><thead><tr><th align="left">Улица</th></tr></thead><tbody>';
			foreach ($data['products'] as $value) {
				$table .='<tr>';
				$table .='<td><a href="'.$value['hrefPdoduct'].'">';
				$table .=$value['name'];
				$table .='</a></td>';				
				$table .='</tr>';				
			}
			$table .='<tbody></table>';			
		}
		
		$json=$table;
//var_dump($json); 
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
