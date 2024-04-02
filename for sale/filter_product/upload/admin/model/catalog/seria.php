<?php
class ModelCatalogSeria extends Model {
	public function addSeria($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "seria SET name = '" . $this->db->escape($data['name']) . "', product_id = '" . $this->db->escape($data['product_id']) . "'");

		$seria_id = $this->db->getLastId();
				
		$this->cache->delete('seria');

		return $seria_id;
	}

	public function editSeria($seria_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "seria SET name = '" . $this->db->escape($data['name']) . "', product_id = '" . $this->db->escape($data['product_id']) . "' WHERE seria_id = '" . (int)$seria_id . "'");

		$this->cache->delete('seria');
	}

	public function deleteSeria($seria_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seria` WHERE seria_id = '" . (int)$seria_id . "'");

		$this->cache->delete('seria');
	}

	public function getSeria($seria_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "seria WHERE seria_id = '" . (int)$seria_id . "'");

		return $query->row;
	}
	
	public function getSerias($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "seria";

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

	public function getTotalSerias() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "seria");

		return $query->row['total'];
	}
}
