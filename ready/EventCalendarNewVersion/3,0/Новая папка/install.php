
  <?php
		$create_events = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "events` (`event_id` int(11) NOT NULL auto_increment, `status` int(1) NOT NULL default '0', `image` VARCHAR(255) COLLATE utf8_general_ci default NULL, `date_from` date default NULL, `date_to` date default NULL, PRIMARY KEY (`event_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		$this->db->query($create_events);
	
		$create_event_descriptions = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "events_description` (`event_id` int(11) NOT NULL default '0', `language_id` int(11) NOT NULL default '0', `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `mindescription` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,  `meta_title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,  `meta_h1` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `meta_description` VARCHAR(255) COLLATE utf8_general_ci NOT NULL, `meta_keyword` varchar(255) COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`event_id`,`language_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		$this->db->query($create_event_descriptions);
	
		$create_event_to_store = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "events_to_store` (`event_id` int(11) NOT NULL, `store_id` int(11) NOT NULL, PRIMARY KEY (`event_id`, `store_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		$this->db->query($create_event_to_store);		
		
		$insert_event_value = "INSERT INTO " . DB_PREFIX . "event_value SET value1 = '26028713', value2 = ''";
		$this->db->query($insert_event_value);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/event_list', `keyword` = ''");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/event', `keyword` = ''");
		
?>