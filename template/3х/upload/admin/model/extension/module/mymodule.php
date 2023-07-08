<?php
class ModelExtensionModuleMymodule extends Model {
	
	public function install() {
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "mymodule` (`mymodule_id` int(11) NOT NULL auto_increment, `mymodule_text` VARCHAR(255) COLLATE utf8_general_ci default NULL,  PRIMARY KEY (`mymodule_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");			
		
}

	public function uninstall() { 
		$this->db->query("DROP TABLE `" . DB_PREFIX . "mymodule`");
	}
}
?>