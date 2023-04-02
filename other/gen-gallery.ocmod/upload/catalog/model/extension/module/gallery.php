<?php
class ModelExtensionModuleGallery extends Model {

		public function getGallery($gallery_id) {
		
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gallery i LEFT JOIN " . DB_PREFIX . "gallery_description id ON (i.gallery_id = id.gallery_id) WHERE i.gallery_id = '" . (int)$gallery_id . "' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' and status = '1' ");
		
		if ($query->num_rows) {
			return array(
				'gallery_id'         => $query->row['gallery_id'],
				'image'            => $query->row['image'],
				'date_from'        => $query->row['date_from'],
				'date_to'          => $query->row['date_to'],
				'title'            => $query->row['title'],
				'mindescription'   => $query->row['mindescription'],
				'description'      => $query->row['description'],
				'status'           => $query->row['status']
			);
		} else {
			return false;
		}
	}

    public function getGallery($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "gallery i LEFT JOIN " . DB_PREFIX . "gallery_description id ON (i.gallery_id = id.gallery_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' and status = '1' ";

			$sort_data = array(
				'id.title',
				'i.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
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
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery i LEFT JOIN " . DB_PREFIX . "gallery_description id ON (i.gallery_id = id.gallery_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");

				$gallery_data = $query->rows;

				$this->cache->set('gallery.' . (int)$this->config->get('config_language_id'), $gallery_data);
			}

			return $gallery_data;
		}
	}

	public function getTotalGallery() {
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gallery");


		return $query->row['total'];
	}
	
	public function getGallerySetting() {
		
		$query = $this->db->query("SELECT gallery_share FROM " . DB_PREFIX . "gallery_setting");


		return $query->row;
	}
}