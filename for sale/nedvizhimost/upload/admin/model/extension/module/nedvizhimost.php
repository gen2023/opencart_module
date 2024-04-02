<?php
class ModelExtensionModuleNedvizhimost extends Model {
	
	public function install() {
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "st_metro` (`st_metro_id` int(11) NOT NULL auto_increment, `name` VARCHAR(255) COLLATE utf8_general_ci default NULL, `distance` int(11) COLLATE utf8_general_ci default 0, PRIMARY KEY (`st_metro_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "rayon` (`rayon_id` int(11) NOT NULL auto_increment, `name` VARCHAR(255) COLLATE utf8_general_ci default NULL, PRIMARY KEY (`rayon_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");	

/*		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "apartment_area` (`id` int(11) NOT NULL auto_increment, `name` VARCHAR(255) COLLATE utf8_general_ci default NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");	*/	
		
	}

	public function uninstall() { 
		$this->db->query("DROP TABLE `" . DB_PREFIX . "st_metro`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "rayon`");
		/*$this->db->query("DROP TABLE `" . DB_PREFIX . "apartment_area`");*/
	}
	
}
?>