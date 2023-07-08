<?php
class ModelExtensionDashboardCountProductCategory extends Model {
	
	public function getTotal($category_id) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_category` WHERE `category_id`='".$category_id."'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
}
?>