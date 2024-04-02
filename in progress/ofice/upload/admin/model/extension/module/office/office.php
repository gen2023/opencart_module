<?php
class ModelExtensionModuleOfficeOffice extends Model {
	public function addOffice($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "office SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "'");
	}

	public function editOffice($office_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "office SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "' WHERE office_id = '" . (int)$office_id . "'");
	}
	
	public function deleteOffice($office_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "office WHERE office_id = '" . (int)$office_id . "'");
	}

	public function getOffice($office_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "office WHERE office_id = '" . (int)$office_id . "'");

		return $query->row;
	}

	public function getOffices($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "office";

			$sort_data = array(
				'name',
				'sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY sort_order, name";
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
		} else {
			$office_data = $this->cache->get('office');

			if (!$office_data) {
				$office_data = array();

				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "office ORDER BY sort_order, name");

				foreach ($query->rows as $result) {
					$office_data[] = array(
						'office_id' => $result['office_id'],
						'name'        => $result['name'],
						'sort_order'  => $result['sort_order'],
						'status'      => $result['status']
					);
				}
			}

			return $office_data;
		}
	}

	public function getTotalOffices() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "office");

		return $query->row['total'];
	}
}
