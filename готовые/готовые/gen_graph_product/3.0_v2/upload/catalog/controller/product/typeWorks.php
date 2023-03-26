<?php
class ControllerProductTypeWorks extends Controller {
	private $error = array();

	public function index() {

	$this->load->model('catalog/typeWork');

    $results = $this->model_catalog_typeWork->getTypeWork($this->request->get['product_id']);
    if($results){
        if ($results[0]['viewTypeX']==0){
            $data['viewTypeX']=' месяц';
        }else{
            $data['viewTypeX']=' день';
        }
    }

    function cmp($a, $b) { 
        return strnatcmp($a["sort_order"], $b["sort_order"]); 
    } 

    usort($results, "cmp");
    $data['typeWorks']=array();

    foreach ($results as $result) {
        $data['typeWorks'][] = array(
            'typeWork_id'   => $result['typeWork_id'],
            'color'       	=> $result['color'],
            'month_start'   => $result['month_start'],
            'month_end'     => $result['month_end'],
            'sort_order'    => $result['sort_order'],
            'title'       	=> $result['title']				
        );
    }
	
	$data['timeScript1']=$this->config->get('module_typeWork_time1');
	$data['timeScript2']=$this->config->get('module_typeWork_time2');

    return $this->load->view('product/typeWorks', $data);
    }
}