<?php
class ModelCatalogNewsJs extends Model {

	public function addNewsJs($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "newsJs SET status = '" . (int)$data['status'] . "'");
	
		$newsJs_id = $this->db->getLastId();
	
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "newsJs SET image = '" . $this->db->escape($data['image']) . "' WHERE newsJs_id = '" . (int)$newsJs_id . "'");
		}
	
		foreach ($data['newsJs_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "newsJs_description SET newsJs_id = '" . (int)$newsJs_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "',  description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "',  meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}
	
		if (isset($data['newsJs_to_store'])) {
			foreach ($data['newsJs_to_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsJs_to_store SET newsJs_id = '" . (int)$newsJs_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'newsJs_id=" . (int)$newsJs_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('newsJs');
	}

	public function editNewsJs($newsJs_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "newsJs SET status = '" . (int)$data['status'] . "' WHERE newsJs_id = '" . (int)$newsJs_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "newsJs SET image = '" . $this->db->escape($data['image']) . "' WHERE newsJs_id = '" . (int)$newsJs_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsJs_description WHERE newsJs_id = '" . (int)$newsJs_id . "'");
	
		foreach ($data['newsJs_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "newsJs_description SET newsJs_id = '" . (int)$newsJs_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "',  meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsJs_to_store WHERE newsJs_id = '" . (int)$newsJs_id . "'");
	//var_dump($data['newsJs_to_store']);
		if (isset($data['newsJs_to_store'])) {		
			foreach ($data['newsJs_to_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "newsJs_to_store SET newsJs_id = '" . (int)$newsJs_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'newsJs_id=" . (int)$newsJs_id . "'");
	
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'newsJs_id=" . (int)$newsJs_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('newsJs');
	}

	public function deleteNewsJs($newsJs_id) { 
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsJs WHERE newsJs_id = '" . (int)$newsJs_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsJs_description WHERE newsJs_id = '" . (int)$newsJs_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsJs_to_store WHERE newsJs_id = '" . (int)$newsJs_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'newsJs_id=" . (int)$newsJs_id . "'");
	
		$this->cache->delete('newsJs');
	}

	public function getNewsJsList($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "newsJs n LEFT JOIN " . DB_PREFIX . "newsJs_description nd ON (n.newsJs_id = nd.newsJs_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

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
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);

			return $query->rows;
		} else {
			$news_data = $this->cache->get('newsJs.' . (int)$this->config->get('config_language_id'));

			if (!$news_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsJs n LEFT JOIN " . DB_PREFIX . "newsJs_description nd ON (n.newsJs_id = nd.newsJs_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY nd.title");

				$news_data = $query->rows;

				$this->cache->set('newsJs.' . (int)$this->config->get('config_language_id'), $news_data);
			}

			return $news_data;
		}
	}

	public function getNewsJsStory($newsJs_id) { 
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'newsJs_id=" . (int)$newsJs_id . "') AS keyword FROM " . DB_PREFIX . "newsJs n LEFT JOIN " . DB_PREFIX . "newsJs_description nd ON (n.newsJs_id = nd.newsJs_id) WHERE n.newsJs_id = '" . (int)$newsJs_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
		return $query->row;
	}

	public function getNewsJsDescriptions($newsJs_id) { 
		$newsJs_description_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsJs_description WHERE newsJs_id = '" . (int)$newsJs_id . "'");
	
		foreach ($query->rows as $result) {
			$newsJs_description_data[$result['language_id']] = array(
				'title'            	=> $result['title'],
				'description'      	=> $result['description'],
				'meta_title'        => $result['meta_title'],
				'meta_h1'           => $result['meta_h1'],
				'meta_description' 	=> $result['meta_description'],
				'meta_keyword'		=> $result['meta_keyword'],
			);
		}
	
		return $newsJs_description_data;
	}

	public function getNewsJsStores($newsJs_id) { 
		$newsjspage_store_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsJs_to_store WHERE newsJs_id = '" . (int)$newsJs_id . "'");
		
		foreach ($query->rows as $result) {
			$newsjspage_store_data[] = $result['store_id'];
		}
	
		return $newsjspage_store_data;
	}

	public function getTotalNewsJs() { 

     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newsJs");
	
		return $query->row['total'];
	}

}
?>