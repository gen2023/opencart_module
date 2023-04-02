<?php
class ModelExtensionModuleGenGraphProduct extends Model {
	public function install() {
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gen_graphWork` (`work_id` int(11) NOT NULL auto_increment, `status` int(1) NOT NULL default '0', `name` VARCHAR(255) COLLATE utf8_general_ci default NULL,`color` VARCHAR(255) COLLATE utf8_general_ci default NULL, `product_id` VARCHAR(255) COLLATE utf8_general_ci default NULL, `month_start` float(1) default '1', `month_end` float(1) default '1',`sort_order` int(3) default NULL, PRIMARY KEY (`work_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");			
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gen_graphWork_description` (`work_id` int(11) NOT NULL default '0', `language_id` int(11) NOT NULL default '0', `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`work_id`,`language_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");	
		
		/*$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gen_graphWork_setting` (`setting_id` int(11) NOT NULL auto_increment, `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `moreOption` int(1) NOT NULL default '0', PRIMARY KEY (`setting_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "gen_graphWork_setting` SET `setting_id` = '1', `title`='',`moreOption`=0 ");*/
	}

	public function uninstall() { 
		$this->db->query("DROP TABLE `" . DB_PREFIX . "gen_graphWork`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "gen_graphWork_description`");
		//$this->db->query("DROP TABLE `" . DB_PREFIX . "gen_graphWork_setting`");
	}

	public function getTotalWorks() { 

     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gen_graphWork");
	
		return $query->row['total'];
	}

	public function getWorksList($data = array()) {

		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "gen_graphWork ";

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
	}

	public function getWorkInfo($work_id) { 
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_graphWork n LEFT JOIN " . DB_PREFIX . "gen_graphWork_description nd ON (n.work_id = nd.work_id) WHERE n.work_id = '" . (int)$work_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
		return $query->row;
	}
	
	public function addWork($data) {
		
		//var_dump($data);
	 
		$this->db->query("INSERT INTO " . DB_PREFIX . "gen_graphWork SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']). "', color = '" . $this->db->escape($data['color']). "',
		month_end = '" . $this->db->escape($data['month_end']) . "', month_start = '" . $this->db->escape($data['month_start']) . "', sort_order = '" . (int)$data['sort_order'] . "'");
	
		$work_id = $this->db->getLastId();
		
		if (isset($data['copyProduct'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gen_graphWork SET product_id = '" .  $this->db->escape($data['copyProduct']) . "' WHERE work_id = '" . (int)$work_id . "'");
		}else{	
			if (isset($data['product'])) {
				$result = implode(',', $data['product']);
				$this->db->query("UPDATE " . DB_PREFIX . "gen_graphWork SET product_id = '" .  $this->db->escape($result) . "' WHERE work_id = '" . (int)$work_id . "'");
			}
		}
	
		foreach ($data['work_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "gen_graphWork_description SET work_id = '" . (int)$work_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "'");
		}
	
		$this->cache->delete('work');
	}

	public function editWork($work_id, $data) {

		$this->db->query("UPDATE " . DB_PREFIX . "gen_graphWork SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']). "', color = '" . $this->db->escape($data['color']). "', month_start = '" . $this->db->escape($data['month_start']) . "', month_end = '" . $this->db->escape($data['month_end']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE work_id = '" . (int)$work_id . "'");
			
		if (isset($data['product'])) {
			$result = implode(',', $data['product']);

			$this->db->query("UPDATE " . DB_PREFIX . "gen_graphWork SET product_id = '" .  $this->db->escape($result) . "' WHERE work_id = '" . (int)$work_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_graphWork_description WHERE work_id = '" . (int)$work_id . "'");
	
		foreach ($data['work_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "gen_graphWork_description SET work_id = '" . (int)$work_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "'");
		}

		$this->cache->delete('work');
	}

	public function deleteWork($work_id) { 
		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_graphWork WHERE work_id = '" . (int)$work_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_graphWork_description WHERE work_id = '" . (int)$work_id . "'");
	
		$this->cache->delete('work');
	}
	
	public function copyWork($work_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_graphWork WHERE work_id = '" . (int)$work_id . "'");

		if ($query->num_rows) {
			$data = $query->row;
			
			$data['status'] = '0';
			
			$data['color'] = $query->row["color"];
			$data['copyProduct'] = $query->row["product_id"];
			$data['work_description'] = $this->getWorkDescriptions($work_id);

			$this->addWork($data);
		}
	}
	
	public function getWorkDescriptions($work_id) { 
		$work_description_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_graphWork_description WHERE work_id = '" . (int)$work_id . "'");
	
		foreach ($query->rows as $result) {
			$work_description_data[$result['language_id']] = array(
				'title'            	=> $result['title'],
			);
		}
	
		return $work_description_data;
	}

	public function setSetting($data) {

		$this->db->query("UPDATE " . DB_PREFIX . "gen_graphWork_setting SET `title` = '" . (int)$data['title'] . "',`moreOption`= '" . (int)$data['moreOption'] . "'");

	}

	public function getSetting() {
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_graphWork_setting WHERE setting_id ='1'");
	
		return $query->row;
	}
	
}
?>