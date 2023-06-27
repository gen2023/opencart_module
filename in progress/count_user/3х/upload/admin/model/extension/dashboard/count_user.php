<?php
class ModelExtensionDashboardCountUser extends Model {
	public function getTotal() {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "gen_count_user` ";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function getTotalOnline($currentDay) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "gen_count_user` ";
		$query = $this->db->query($sql);

		return $query->rows;
	}
}