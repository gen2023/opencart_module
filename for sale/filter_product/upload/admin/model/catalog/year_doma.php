<?php
class ModelCatalogYearDoma extends Model {
	public function addYearDoma($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "year_doma SET name = '" . $this->db->escape($data['name']) . "'");

		$year_doma_id = $this->db->getLastId();
				
		$this->cache->delete('year_doma');

		return $year_doma_id;
	}

	public function editYearDoma($year_doma_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "year_doma SET name = '" . $this->db->escape($data['name']) . "' WHERE year_doma_id = '" . (int)$year_doma_id . "'");

		$this->cache->delete('year_doma');
	}

	public function deleteYearDoma($year_doma_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "year_doma` WHERE year_doma_id = '" . (int)$year_doma_id . "'");

		$this->cache->delete('year_doma');
	}

	public function getYearDoma($year_doma_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "year_doma WHERE year_doma_id = '" . (int)$year_doma_id . "'");

		return $query->row;
	}
	
	public function getYearsDoma($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "year_doma";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalYearsDoma() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "year_doma");

		return $query->row['total'];
	}
}
