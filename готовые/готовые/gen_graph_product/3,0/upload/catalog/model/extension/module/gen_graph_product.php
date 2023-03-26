<?php
class ModelExtensionModuleGenGraphProduct extends Model {
	public function getWork($product_id) {
		$result_productArr = $this->db->query("SELECT work_id, product_id FROM " . DB_PREFIX . "gen_graphWork WHERE status = '1' ");
		$work_id=array();
		$result=array();
		
		foreach ($result_productArr->rows as $value) {
			$idArr=explode(',',$value['product_id']);
			if(in_array($product_id,$idArr)){
				array_push($work_id,$value['work_id']);
			}
		}		
		
		for($i=0; $i<count($work_id);$i++){
			$query[$i]=$this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gen_graphWork i LEFT JOIN " . DB_PREFIX . "gen_graphWork_description id ON (i.work_id = id.work_id) WHERE i.work_id='". (int)$work_id[$i] ."' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND status = '1' ");
			$result[$i]=$query[$i]->row;
		}
		//var_dump($result);
		return $result;		
		
	}
		
}