
  <?php
		$create_gallery = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gallery` (`gallery_id` int(11) NOT NULL auto_increment, `status` int(1) NOT NULL default '0', `image` VARCHAR(255) COLLATE utf8_general_ci default NULL, `date_from` date default NULL, `date_to` date default NULL, PRIMARY KEY (`gallery_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		$this->db->query($create_gallery);
	
		$create_gallery_descriptions = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gallery_description` (`gallery_id` int(11) NOT NULL default '0', `language_id` int(11) NOT NULL default '0', `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `mindescription` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,  `meta_title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,  `meta_h1` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `meta_description` VARCHAR(255) COLLATE utf8_general_ci NOT NULL, `meta_keyword` varchar(255) COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`gallery_id`,`language_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		$this->db->query($create_gallery_descriptions);
	
		$create_gallery_to_store = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gallery_to_store` (`gallery_id` int(11) NOT NULL, `store_id` int(11) NOT NULL, PRIMARY KEY (`gallery_id`, `store_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		$this->db->query($create_gallery_to_store);	

		$create_gallery_value="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gallery_setting` (`gallery_value_id` int(11) NOT NULL auto_increment, `value1` VARCHAR(255) COLLATE utf8_general_ci default NULL, `value2` VARCHAR(255) COLLATE utf8_general_ci default NULL, `gallery_share` int(1) NOT NULL, PRIMARY KEY (`gallery_value_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		$this->db->query($create_gallery_value);		
		
		$insert_gallery_value = "INSERT INTO " . DB_PREFIX . "gallery_setting SET gallery_value_id='1', value1 = '26028713', value2 = '', gallery_share='0'";
		$this->db->query($insert_gallery_value);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/gallery_list', `keyword` = ''");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/gallery', `keyword` = ''");
		
?>