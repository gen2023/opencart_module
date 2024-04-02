<?php
class ModelExtensionModuleGenFilterProduct extends Model {
	
	public function install() {
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gen_product_filter` (`id` int(11) NOT NULL auto_increment, `address_doma_id` int(11) COLLATE utf8_general_ci default 0, `seria_id` int(11) COLLATE utf8_general_ci default 0, `floor_doma_id` int(11) COLLATE utf8_general_ci default 0, `year_doma_id` int(11) COLLATE utf8_general_ci default 0, `type_doma_id` int(11) COLLATE utf8_general_ci default 0, `product_id` int(11) COLLATE utf8_general_ci default 0, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "seria` (`seria_id` int(11) NOT NULL auto_increment, `name` VARCHAR(255) COLLATE utf8_general_ci default NULL, `product_id` int(11) COLLATE utf8_general_ci default 0, PRIMARY KEY (`seria_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "type_doma` (`type_doma_id` int(11) NOT NULL auto_increment, `name` VARCHAR(255) COLLATE utf8_general_ci default NULL, PRIMARY KEY (`type_doma_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");	

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "address_doma` (`address_doma_id` int(11) NOT NULL auto_increment, `name` VARCHAR(255) COLLATE utf8_general_ci default NULL, PRIMARY KEY (`address_doma_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");		

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "floor_doma` (`floor_doma_id` int(11) NOT NULL auto_increment, `name` VARCHAR(255) COLLATE utf8_general_ci default NULL, PRIMARY KEY (`floor_doma_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");		
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "year_doma` (`year_doma_id` int(11) NOT NULL auto_increment, `name` VARCHAR(255) COLLATE utf8_general_ci default NULL, PRIMARY KEY (`year_doma_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");		
		
	}

	public function uninstall() { 
		$this->db->query("DROP TABLE `" . DB_PREFIX . "gen_product_filter`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "seria`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "floor_doma`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "year_doma`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "type_doma`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "address_doma`");
	}
	
}
?>