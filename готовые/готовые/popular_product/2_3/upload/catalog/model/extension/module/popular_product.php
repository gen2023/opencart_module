<?php
class ModelExtensionModulePopularProduct extends Model {
	public function getProduct($type, $limit) {

		if ($type==1){
			
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE `status`=1 ORDER BY `viewed` DESC LIMIT " . (int)$limit . "");
			
		} elseif ($type==2) {
		
			$query = $this->db->query("SELECT op.product_id, SUM(op.quantity) AS quantity, SUM(op.price + (op.tax * op.quantity)) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);
	
		} elseif ($type==3) {
			
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE `status`=1 ORDER BY `price` DESC LIMIT " . (int)$limit . "");
			
		} elseif ($type==4) {
			
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE `status`=1 ORDER BY `price` ASC LIMIT " . (int)$limit . "");
		
		} elseif ($type==5) {
			
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE `status`=1 ORDER BY RAND() LIMIT " . (int)$limit . "");
		
		}

		return $query->rows;
	}
		
}