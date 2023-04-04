<?php
class ModelCatalogTypeWork extends Model {

	public function getTypeWork($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_typeWork WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}
	
	public function getTypeWorkTitle($typeWork_id) { 
		$work_description_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_typeWork_title WHERE typeWork_id = '" . (int)$typeWork_id . "'");
	
		foreach ($query->rows as $result) {
			$work_description_data[$result['language_id']] = array(
				'title'            	=> $result['title'],
			);
		}
	
		return $work_description_data;
	}

}
