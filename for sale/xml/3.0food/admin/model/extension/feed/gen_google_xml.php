<?php
class ModelExtensionFeedGenGoogleXml extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "gen_google_xml_category` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`google_category_id` INT(11) NOT NULL,
				`category_id` INT(11) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "gen_google_xml_category`");
	}

    public function updateGoogleCategory($data) {
		$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gen_google_xml_category` WHERE `category_id`='" . (int)$data['category_id'] . "'");
		
		if ($sql->row){
			$this->db->query("UPDATE `" . DB_PREFIX . "gen_google_xml_category` SET `google_category_id`='" . (int)$data['categoryGoogle'] . "' WHERE `category_id`='" . (int)$data['category_id'] . "'");
		} else{			
			$this->db->query("INSERT INTO " . DB_PREFIX . "gen_google_xml_category SET google_category_id = '" . (int)$data['categoryGoogle'] . "', category_id = '" . (int)$data['category_id'] . "'");
		}
		
        
    }
	
	public function getGoogleCategory($category_id) {
        $sql = "SELECT google_category_id FROM `" . DB_PREFIX . "gen_google_xml_category` WHERE `category_id`='" . (int)$category_id . "'";		
		
		$query = $this->db->query($sql);
		
		return $query->row;
    }
}
