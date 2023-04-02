<?php
		$create_newsJs = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "newsJs` (`newsJs_id` int(11) NOT NULL auto_increment, `status` int(1) NOT NULL default '0', `image` VARCHAR(255) COLLATE utf8_general_ci default NULL, `viewed` int(5) NOT NULL DEFAULT '0', PRIMARY KEY (`newsJs_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		$this->db->query($create_newsJs);
	
		$create_newsJs_descriptions = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "newsJs_description` (`newsJs_id` int(11) NOT NULL default '0', `language_id` int(11) NOT NULL default '0', `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,  `meta_title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,  `meta_h1` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `meta_description` VARCHAR(255) COLLATE utf8_general_ci NOT NULL, `meta_keyword` varchar(255) COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`newsJs_id`,`language_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		$this->db->query($create_newsJs_descriptions);
	
		$create_news_js_to_store = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "newsJs_to_store` (`newsJs_id` int(11) NOT NULL, `store_id` int(11) NOT NULL, PRIMARY KEY (`newsJs_id`, `store_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		$this->db->query($create_news_js_to_store);
?>