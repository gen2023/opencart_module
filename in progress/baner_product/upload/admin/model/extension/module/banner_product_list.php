<?php
class ModelExtensionModuleBannerProductList extends Model {

	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "banner_product` (`banner_id` int(11) NOT NULL auto_increment, `name` varchar(255) COLLATE utf8_general_ci default NULL, `status` int(1) COLLATE utf8_general_ci default 0, PRIMARY KEY (`banner_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");			
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "banner_product_image` (`banner_image_id` int(11) NOT NULL auto_increment, `banner_id` int(1) COLLATE utf8_general_ci default 0, `language_id` int(1) COLLATE utf8_general_ci default 0, `title` varchar(255) COLLATE utf8_general_ci default NULL, `link` varchar(255) COLLATE utf8_general_ci default NULL, `image` varchar(255) COLLATE utf8_general_ci default NULL, `sort_order` int(1) COLLATE utf8_general_ci default 0, PRIMARY KEY (`banner_image_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");			
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "banner_product_product` (`banner_product_id` int(11) NOT NULL auto_increment, `banner_id` int(1) COLLATE utf8_general_ci default 0, `language_id` int(1) COLLATE utf8_general_ci default 0, `product_id` int(11) COLLATE utf8_general_ci default 0, `row_id` int(11) COLLATE utf8_general_ci default 0, PRIMARY KEY (`banner_product_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");			
	}

	public function uninstall() { 
		$this->db->query("DROP TABLE `" . DB_PREFIX . "banner_product`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "banner_product_image`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "banner_product_product`");
	}

	public function addBanner($data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "banner_product SET name = '" . $this->db->escape($data['name']) . "', status = '" . (int)$data['status'] . "'");

		$banner_id = $this->db->getLastId();

		if (isset($data['banner_image'])) {
			foreach ($data['banner_image'] as $language_id => $value) {
				foreach ($value as $banner_image) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "banner_product_image SET banner_id = '" . (int)$banner_id . "', language_id = '" . (int)$language_id . "', title = '" .  $this->db->escape($banner_image['title']) . "', link = '" .  $this->db->escape($banner_image['link']) . "', image = '" .  $this->db->escape($banner_image['image']) . "', sort_order = '" .  (int)$banner_image['sort_order'] . "'");
				}
			}
		}
		if (isset($data['product'])) {
			$key=0;
			foreach ($data['product'] as $value) {
				foreach ($value as $id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "banner_product_product SET banner_id = '" . (int)$banner_id . "', language_id = '" . (int)$language_id . "', product_id = '" . (int)$id . "', row_id = '" . (int)$key . "'");
				}
				$key++;
			}
		}

		return $banner_id;
	}

	public function editBanner($banner_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "banner_product SET name = '" . $this->db->escape($data['name']) . "', status = '" . (int)$data['status'] . "' WHERE banner_id = '" . (int)$banner_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_product_image WHERE banner_id = '" . (int)$banner_id . "'");

		if (isset($data['banner_image'])) {
			foreach ($data['banner_image'] as $language_id => $value) {
				foreach ($value as $banner_image) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "banner_product_image SET banner_id = '" . (int)$banner_id . "', language_id = '" . (int)$language_id . "', title = '" .  $this->db->escape($banner_image['title']) . "', link = '" .  $this->db->escape($banner_image['link']) . "', image = '" .  $this->db->escape($banner_image['image']) . "', sort_order = '" . (int)$banner_image['sort_order'] . "'");
				}
			}
		}

		if (isset($data['product'])) {
			$key=0;
			foreach ($data['product'] as $value) {
				foreach ($value as $id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "banner_product_product SET banner_id = '" . (int)$banner_id . "', language_id = '" . (int)$language_id . "', product_id = '" . (int)$id . "', row_id = '" . (int)$key . "'");
				}
				$key++;
			}
		}
	}

	public function deleteBanner($banner_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_product WHERE banner_id = '" . (int)$banner_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_product_image WHERE banner_id = '" . (int)$banner_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_product_product WHERE banner_id = '" . (int)$banner_id . "'");
	}

	public function getBanner($banner_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "banner_product WHERE banner_id = '" . (int)$banner_id . "'");

		return $query->row;
	}

	public function getBanners($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "banner_product";

		$sort_data = array(
			'name',
			'status'
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

	public function getBannerImages($banner_id) {
		$banner_image_data = array();

		$banner_image_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_product_image WHERE banner_id = '" . (int)$banner_id . "' ORDER BY sort_order ASC");

		foreach ($banner_image_query->rows as $banner_image) {
			$banner_image_data[$banner_image['language_id']][] = array(
				'title'      => $banner_image['title'],
				'link'       => $banner_image['link'],
				'image'      => $banner_image['image'],
				'sort_order' => $banner_image['sort_order']
			);
		}

		return $banner_image_data;
	}

	public function getBannerProducts($banner_id) {
		$banner_product_data = array();

		$banner_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_product_product WHERE banner_id = '" . (int)$banner_id . "'");
		
		foreach ($banner_product_query->rows as $banner_product) {
			$banner_product_data[$banner_product['language_id']][$banner_product['row_id']][] = array(
				'product_id'      => $banner_product['product_id'],
			);
		}

		return $banner_product_data;
	}

	public function getTotalBanners() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "banner_product");

		return $query->row['total'];
	}
}
