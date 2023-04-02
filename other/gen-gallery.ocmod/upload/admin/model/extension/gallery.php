<?php
class ModelExtensionGallery extends Model {

	public function addGallery($data) {
		//var_dump($data);
		$this->db->query("UPDATE " . DB_PREFIX . "gallery_setting SET value2 = '" . $this->db->escape($data['license']) . "' WHERE gallery_value_id = '1'");
		 
		$this->db->query("INSERT INTO " . DB_PREFIX . "gallery SET status = '" . (int)$data['status'] . "', date_from = now(), date_to = now()");
	
		$gallery_id = $this->db->getLastId();
	
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gallery SET image = '" . $this->db->escape($data['image']) . "' WHERE gallery_id = '" . (int)$gallery_id . "'");
		}
	
		if (isset($data['date_from'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gallery SET date_from = '" . $this->db->escape($data['date_from']) . "' WHERE gallery_id = '" . (int)$gallery_id . "'");
		}
		
		if (isset($data['date_to'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gallery SET date_to = '" . $this->db->escape($data['date_to']) . "' WHERE gallery_id = '" . (int)$gallery_id . "'");
		}
	
		foreach ($data['gallery_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_description SET gallery_id = '" . (int)$gallery_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "',  mindescription = '" . $this->db->escape($value['mindescription']) . "',  description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "',  meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}
	
		if (isset($data['gallery_store'])) {
			foreach ($data['gallery_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_to_store SET gallery_id = '" . (int)$gallery_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'gallery_id=" . (int)$gallery_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('gallery');
	}

	public function editGallery($gallery_id, $data) {
		var_dump($data);
		$this->db->query("UPDATE " . DB_PREFIX . "gallery SET status = '" . (int)$data['status'] . "' WHERE gallery_id = '" . (int)$gallery_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gallery SET image = '" . $this->db->escape($data['image']) . "' WHERE gallery_id = '" . (int)$gallery_id . "'");
		}
		
		if (isset($data['date_from'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gallery SET date_from = '" . $this->db->escape($data['date_from']) . "' WHERE gallery_id = '" . (int)$gallery_id . "'");
		}
		
		if (isset($data['date_to'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gallery SET date_to = '" . $this->db->escape($data['date_to']) . "' WHERE gallery_id = '" . (int)$gallery_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_description WHERE gallery_id = '" . (int)$gallery_id . "'");
	
		foreach ($data['gallery_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_description SET gallery_id = '" . (int)$gallery_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', mindescription = '" . $this->db->escape($value['mindescription']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "',  meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_to_store WHERE gallery_id = '" . (int)$gallery_id . "'");
	
		if (isset($data['gallery_store'])) {		
			foreach ($data['gallery_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_to_store SET gallery_id = '" . (int)$gallery_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'gallery_id=" . (int)$gallery_id . "'");
	
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'gallery_id=" . (int)$gallery_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('gallery');
	}

	public function deleteGallery($gallery_id) { 
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery WHERE gallery_id = '" . (int)$gallery_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_description WHERE gallery_id = '" . (int)$gallery_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_to_store WHERE gallery_id = '" . (int)$gallery_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'gallery_id=" . (int)$gallery_id . "'");
	
		$this->cache->delete('gallery');
	}

	public function getGalleryList($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "gallery n LEFT JOIN " . DB_PREFIX . "gallery_description nd ON (n.gallery_id = nd.gallery_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sort_data = array(
				'nd.title',
				'n.date_from'
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
			$gallery_data = $this->cache->get('gallery.' . (int)$this->config->get('config_language_id'));

			if (!$gallery_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery n LEFT JOIN " . DB_PREFIX . "gallery_description nd ON (n.gallery_id = nd.gallery_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY nd.title");

				$gallery_data = $query->rows;

				$this->cache->set('gallery.' . (int)$this->config->get('config_language_id'), $gallery_data);
			}

			return $gallery_data;
		}
	}

	public function getGalleryStory($gallery_id) { 
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'gallery_id=" . (int)$gallery_id . "') AS keyword FROM " . DB_PREFIX . "gallery n LEFT JOIN " . DB_PREFIX . "gallery_description nd ON (n.gallery_id = nd.gallery_id) WHERE n.gallery_id = '" . (int)$gallery_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
		return $query->row;
	}

	public function getGalleryDescriptions($gallery_id) { 
		$gallery_description_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_description WHERE gallery_id = '" . (int)$gallery_id . "'");
	
		foreach ($query->rows as $result) {
			$gallery_description_data[$result['language_id']] = array(
				'title'            	=> $result['title'],
				'mindescription'    => $result['mindescription'],
				'description'      	=> $result['description'],
				'meta_title'        => $result['meta_title'],
				'meta_h1'           => $result['meta_h1'],
				'meta_description' 	=> $result['meta_description'],
				'meta_keyword'		=> $result['meta_keyword'],
			);
		}
	
		return $gallery_description_data;
	}

	public function getGalleryStores($gallery_id) { 
		$gallerypage_store_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_to_store WHERE gallery_id = '" . (int)$gallery_id . "'");
		
		foreach ($query->rows as $result) {
			$gallerypage_store_data[] = $result['store_id'];
		}
	
		return $gallerypage_store_data;
	}

	public function getTotalGallery() { 

     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gallery");
	
		return $query->row['total'];
	}

	public function setGalleryListUrl($url) {
		//var_dump($url);
		if ($url) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/gallery_list'");
			if ($query) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/gallery_list'");
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/gallery_list', `keyword` = '" . $this->db->escape($url['gallerylist_url']) . "'");
			}else{
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/gallery_list', `keyword` = '" . $this->db->escape($url['gallerylist_url']) . "'");
			}
		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/gallery'");
			if ($query) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/gallery'");
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/gallery', `keyword` = '" . $this->db->escape($url['gallery_url']) . "'");
			}else{
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/gallery', `keyword` = '" . $this->db->escape($url['gallery_url']) . "'");
			}
			
		} 
		
		$this->db->query("UPDATE " . DB_PREFIX . "gallery_setting SET gallery_share = '" . (int)$url['gallery_share'] . "' WHERE gallery_value_id = '1'");
		
	}

	public function getGalleryListUrl() {
		$query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/gallery' LIMIT 1");
		$data[0]=$query->row;
		
		$query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/gallery_list' LIMIT 1");
		$data[1]=$query->row;
		
		$query = $this->db->query("SELECT gallery_share FROM " . DB_PREFIX . "gallery_setting WHERE gallery_value_id ='1'");
		$data[2]=$query->row;
		
		return $data;
	}
	
	public function getValue() {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_setting WHERE `gallery_value_id`=1");

		return $query->row;
	}
	public function getValue2() {
		
		$query = $this->db->query("SELECT value2 FROM " . DB_PREFIX . "gallery_setting WHERE `gallery_value_id`=1");
		$a=$query->row;
		//var_dump($a['value2']);
		return $a['value2'];
	}
	
	public function copygallery($gallery_id) {
		//var_dump($gallery_id);
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gallery WHERE gallery_id = '" . (int)$gallery_id . "'");

		if ($query->num_rows) {
			$data = $query->row;
			
			$data['keyword'] = '';
			$data['status'] = '0';

			$data['gallery_description'] = $this->getGalleryDescriptions($gallery_id);
			$data['license'] = $this->getValue2();
			$data['gallery_store'] = $this->getGalleryStores($gallery_id);

			$this->addGallery($data);
		}
	}
}
?>