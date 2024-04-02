<?php
class ModelCatalogStMetro extends Model {
/*	public function addAddressDoma($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "address_doma SET name = '" . $this->db->escape($data['name']) . "'");

		$address_doma_id = $this->db->getLastId();
				
		$this->cache->delete('address_doma');

		return $address_doma_id;
	}

	public function editAddressDoma($address_doma_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "address_doma SET name = '" . $this->db->escape($data['name']) . "' WHERE address_doma_id = '" . (int)$address_doma_id . "'");

		$this->cache->delete('address_doma');
	}

	public function deleteAddressDoma($address_doma_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "address_doma` WHERE address_doma_id = '" . (int)$address_doma_id . "'");

		$this->cache->delete('address_doma');
	}

	public function getAddressDoma($address_doma_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "address_doma WHERE address_doma_id = '" . (int)$address_doma_id . "'");

		return $query->row;
	}
	
	public function getAddressDomas($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "address_doma";

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

	public function getTotalAddressDomas() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address_doma");

		return $query->row['total'];
	}*/
}
