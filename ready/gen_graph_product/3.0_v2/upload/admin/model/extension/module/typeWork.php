<?php
class ModelExtensionModuleTypeWork extends Model {
	public function install() {
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_typeWork` ( `typeWork_id` int(11) NOT NULL auto_increment, `color` VARCHAR(255) COLLATE utf8_general_ci default NULL, `product_id` VARCHAR(255) COLLATE utf8_general_ci default NULL, `month_start` int(1) default '0', `month_end` int(1) default '1', `viewTypeX` int(1) default '0', `sort_order` int(3) default NULL, PRIMARY KEY (`typeWork_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");			
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_typeWork_title` (`typeWork_id` int(11) NOT NULL default '0', `language_id` int(11) NOT NULL default '0', `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`typeWork_id`,`language_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");	
	}

	public function uninstall() { 
		$this->db->query("DROP TABLE `" . DB_PREFIX . "product_typeWork`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "product_typeWork_title`");
	}
	
}
?>