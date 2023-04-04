<?php
class ModelCatalogTypeWork extends Model {
	
	public function getTypeWork($product_id) {
		
		$result_productArr = $this->db->query("SELECT typeWork_id FROM " . DB_PREFIX . "product_typeWork WHERE product_id = '". (int)$product_id ."' ");
		$result=array();
		$typeWork_id=$result_productArr->rows;
		//var_dump($typeWork_id[0]);
		for($i=0; $i<count($typeWork_id);$i++){
			//var_dump($typeWork_id[$i]);
			$query[$i]=$this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product_typeWork i LEFT JOIN " . DB_PREFIX . "product_typeWork_title id ON (i.typeWork_id = id.typeWork_id) WHERE i.typeWork_id='". (int)$typeWork_id[$i]['typeWork_id'] ."' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "'");
			$result[$i]=$query[$i]->row;
		}
		//var_dump($result);
		return $result;		
		
	}
		
}