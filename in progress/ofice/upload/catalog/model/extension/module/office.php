<?php
class ModelExtensionModuleOffice extends Model {
	public function getOffices() {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "office WHERE status = '1' ORDER BY sort_order");

		return $query->rows;
	}






}