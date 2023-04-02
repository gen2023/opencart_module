<?php

class ModelCatalogNewsJs extends Model { 

	public function updateViewed($newsJs_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "news SET viewed = (viewed + 1) WHERE newsJs_id = '" . (int)$newsJs_id . "'");
	}

	public function getNewsJsStory($newsJs_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "newsJs n LEFT JOIN " . DB_PREFIX . "newsJs_description nd ON (n.newsJs_id = nd.newsJs_id) LEFT JOIN " . DB_PREFIX . "newsJs_to_store n2s ON (n.newsJs_id = n2s.newsJs_id) WHERE n.newsJs_id = '" . (int)$newsJs_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1'");
	
		return $query->row;
	}

	public function getNewsJs($data) {
				$sql = "SELECT * FROM " . DB_PREFIX . "newsJs n LEFT JOIN " . DB_PREFIX . "newsJs_description nd ON (n.newsJs_id = nd.newsJs_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND status = '1'";

				$sort_data = array(
					'nd.title'
				);

				if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
					$sql .= " ORDER BY " . $data['sort'];
				} else {
					$sql .= " ORDER BY nd.title";
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
				$data['limit'] = 10;
				}	
		
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				}	
		
				$query = $this->db->query($sql);
	//var_dump($query);
				return $query->rows;
				}

	public function getNewsJsShorts() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsJs n LEFT JOIN " . DB_PREFIX . "newsJs_description nd ON (n.newsJs_id = nd.newsJs_id) LEFT JOIN " . DB_PREFIX . "newsJs_to_store n2s ON (n.newsJs_id = n2s.newsJs_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1'"); 
	
		return $query->rows;
	}

	public function getTotalNewsJs() {
     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newsJs WHERE status = '1'");
	//var_dump($query->rows);
		if ($query->row) {
			return $query->row['total'];
		} else {
			return FALSE;
		}
	}	
}
?>
