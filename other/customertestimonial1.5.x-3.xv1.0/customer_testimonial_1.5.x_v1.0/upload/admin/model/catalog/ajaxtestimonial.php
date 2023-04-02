<?php
class ModelCatalogAjaxTestimonial extends Model {

	public function addajaxtestimonial($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "ajaxtestimonial SET name='".$this->db->escape($data['name'])."', status = '" . (int)$data['status'] . "',rating = '".(int)$data['rating'] . "',date_added = '" . $this->db->escape($data['date_added']) . "',email='" . $this->db->escape($data['email']) . "' ,phone='" . $this->db->escape($data['phone']). "'");

		$ajaxtestimonial_id = $this->db->getLastId(); 
			
		foreach ($data['ajaxtestimonial_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ajaxtestimonial_description SET ajaxtestimonial_id = '" . (int)$ajaxtestimonial_id . "', language_id = '" . (int)$language_id . "', description = '" . $this->db->escape($value['description']) . "'");
		}
	}
	
	public function editajaxtestimonial($ajaxtestimonial_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "ajaxtestimonial SET name='".$this->db->escape($data['name'])."', status = '" . (int)$data['status'] . "',date_added = '".$this->db->escape($data['date_added']). "',rating = '".(int)$data['rating']."',email='". $this->db->escape($data['email']) . "',phone='" . $this->db->escape($data['phone']). "' WHERE ajaxtestimonial_id = '" . (int)$ajaxtestimonial_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "ajaxtestimonial_description WHERE ajaxtestimonial_id = '" . (int)$ajaxtestimonial_id . "'");
					
		foreach ($data['ajaxtestimonial_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ajaxtestimonial_description SET ajaxtestimonial_id = '" . (int)$ajaxtestimonial_id . "', language_id = '" . (int)$language_id . "', description = '" . $this->db->escape($value['description']) . "'");
		}	
	}
	
	public function deleteajaxtestimonial($ajaxtestimonial_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ajaxtestimonial WHERE ajaxtestimonial_id = '" . (int)$ajaxtestimonial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ajaxtestimonial_description WHERE ajaxtestimonial_id = '" . (int)$ajaxtestimonial_id . "'");
	}	

	public function getajaxtestimonial($ajaxtestimonial_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "ajaxtestimonial WHERE ajaxtestimonial_id = '" . (int)$ajaxtestimonial_id . "'");
		
		return $query->row;
	}
		
	public function getajaxtestimonials($data = array()) {
	
		if ($data) {
			if (!isset($data['language_id']))  $data['language_id']=$this->config->get('config_language_id');
			$sql = "SELECT * FROM " . DB_PREFIX . "ajaxtestimonial t LEFT JOIN " . DB_PREFIX . "ajaxtestimonial_description td ON (t.ajaxtestimonial_id = td.ajaxtestimonial_id) where reply = 0 and language_id = " . $data['language_id'];
		
			$sort_data = array(
				'td.description',				
				't.name',
				't.date_added',
				't.status'
			);		
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY td.description";	
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
		
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ajaxtestimonial t LEFT JOIN " . DB_PREFIX . "ajaxtestimonial_description td ON (t.ajaxtestimonial_id = td.ajaxtestimonial_id) WHERE reply = 0 AND td.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
				$ajaxtestimonial_data = $query->rows;
			
			return $ajaxtestimonial_data;			
		}
	}
	
	public function getajaxtestimonialsReply($id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ajaxtestimonial t LEFT JOIN " . DB_PREFIX . "ajaxtestimonial_description td ON (t.ajaxtestimonial_id = td.ajaxtestimonial_id) WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "' AND t.reply = '1' AND t.parent_testimonial_id = '" . $id. "' ORDER BY t.date_added DESC");

		return $query->rows;
	}
	
	public function getajaxtestimonialDescriptions($ajaxtestimonial_id) {
		$ajaxtestimonial_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ajaxtestimonial_description WHERE ajaxtestimonial_id = '" . (int)$ajaxtestimonial_id . "'");

		foreach ($query->rows as $result) {
			$ajaxtestimonial_description_data[$result['language_id']] = array(
				'description' => $result['description']
			);
		}
		
		return $ajaxtestimonial_description_data;
	}

	public function isTableExists($table_name) {

		$query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . $table_name . "'");
		if (count($query->rows) == 0)
	      	return FALSE;
		else
	      	return TRUE;
	}
	
	public function getTotalajaxtestimonials() {

		if ($this->isTableExists("ajaxtestimonial") == false)
			return -1;

      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ajaxtestimonial");
		
		return $query->row['total'];
	}	

	public function getCurrentDateTime() {
      	$query = $this->db->query("SELECT NOW() AS cdatetime ");
		
		return $query->row['cdatetime'];
	}	

	public function createDatabaseTables() {
		$sql  = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."ajaxtestimonial` ( ";
		$sql .= "`ajaxtestimonial_id` int(11) NOT NULL AUTO_INCREMENT, ";
		$sql .= "`parent_testimonial_id` int(11) DEFAULT NULL , ";
		$sql .= "`name` varchar(64) COLLATE utf8_bin NOT NULL, ";
  		$sql .= "`phone` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL, ";
		$sql .= "`email` varchar(96) COLLATE utf8_bin DEFAULT NULL, ";
		$sql .= "`status` int(1) NOT NULL DEFAULT '0', ";
		$sql .= "`reply` int(1) NOT NULL DEFAULT '0', ";
		$sql .= "`rating` int(1) NOT NULL DEFAULT '0', ";
		$sql .= "`likes` int(20) NOT NULL DEFAULT '0', ";
		$sql .= "`unlikes` int(20) NOT NULL DEFAULT '0', ";
		$sql .= "`date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', ";
		$sql .= "PRIMARY KEY (`ajaxtestimonial_id`) ";
		$sql .= ") ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		$this->db->query($sql);

		$sql  = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."ajaxtestimonial_description` ( ";
		$sql .= "`ajaxtestimonial_id` int(11) NOT NULL, ";
		$sql .= "`language_id` int(11) NOT NULL, ";
		$sql .= "`description` text COLLATE utf8_unicode_ci NOT NULL, ";
		$sql .= "PRIMARY KEY (`ajaxtestimonial_id`,`language_id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$this->db->query($sql);
	}

	public function dropDatabaseTables() {
		$sql = "DROP TABLE IF EXISTS `".DB_PREFIX."ajaxtestimonial`;";
		$this->db->query($sql);
		$sql = "DROP TABLE IF EXISTS `".DB_PREFIX."ajaxtestimonial_description`;";
		$this->db->query($sql);
	
	}

}
?>