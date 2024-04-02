<?php
class ModelExtensionModuleOffice extends Model {
	
	public function install() {
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "office` (`office_id` int(11) NOT NULL auto_increment, `name` VARCHAR(255) COLLATE utf8_general_ci default NULL, `sort_order` int(11) COLLATE utf8_general_ci default NULL, `status` int(1) COLLATE utf8_general_ci default NULL,  PRIMARY KEY (`office_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		
}

	public function uninstall() { 
		$this->db->query("DROP TABLE `" . DB_PREFIX . "office`");
	}
}
?>