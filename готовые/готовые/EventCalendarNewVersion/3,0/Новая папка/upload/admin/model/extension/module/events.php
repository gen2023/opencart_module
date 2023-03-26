<?php
class ModelExtensionModuleEvents extends Model {
	
	public function install() {
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "events` (`event_id` int(11) NOT NULL auto_increment, `status` int(1) NOT NULL default '0', `events_adminNote` VARCHAR(255) COLLATE utf8_general_ci default NULL,`image` VARCHAR(255) COLLATE utf8_general_ci default NULL, `product_id` int(11) default NULL, `date_from` date default NULL, `date_to` date default NULL, PRIMARY KEY (`event_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");			
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "events_description` (`event_id` int(11) NOT NULL default '0', `language_id` int(11) NOT NULL default '0', `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `mindescription` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,  `meta_title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,  `meta_h1` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `meta_description` VARCHAR(255) COLLATE utf8_general_ci NOT NULL, `meta_keyword` varchar(255) COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`event_id`,`language_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");	
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "events_to_store` (`event_id` int(11) NOT NULL, `store_id` int(11) NOT NULL, PRIMARY KEY (`event_id`, `store_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

		$query1=$this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `query`='extension/module/event_list'");
		$num_rows1 = $query1->num_rows;
		if ($num_rows1==0)	{
			$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `query` = 'extension/module/event_list', `keyword` = 'event_list'");
		}

		$query2=$this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `query`='extension/module/event'");
		$num_rows2 = $query2->num_rows;
		if ($num_rows2==0)	{
			$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `query` = 'extension/module/event', `keyword` = 'event'");
		}

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "event_value` (`event_value_id` int(11) NOT NULL auto_increment,`value1` int(8) NOT NULL, `value2` int(8) default '0', PRIMARY KEY (`event_value_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		
		$query3=$this->db->query("SELECT * FROM `" . DB_PREFIX . "event_value` WHERE value1='26028713'");
		$num_rows3 = $query3->num_rows;
		if ($num_rows3==0)	{
			$this->db->query("INSERT INTO `" . DB_PREFIX . "event_value` SET value1 = '26028713', value2 = ''");
		}
		
}

	public function addEvents($data) {
	//var_dump($data);	
		$this->db->query("UPDATE " . DB_PREFIX . "event_value SET value2 = '" . $this->db->escape($data['license']) . "' WHERE event_value_id = '1'");
		 
		$this->db->query("INSERT INTO " . DB_PREFIX . "events SET status = '" . (int)$data['status'] . "', events_adminNote='" . $this->db->escape($data['events_adminNote']) . "', date_from = now(), date_to = now()");
	
		$event_id = $this->db->getLastId();
	
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET image = '" . $this->db->escape($data['image']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		if (isset($data['product'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET product_id = '" .  $this->db->escape($data["product"][0]) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['product_id'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET product_id = '" .  $this->db->escape($data["product_id"]) . "' WHERE event_id = '" . (int)$event_id . "'");
			
		}
	
		if (isset($data['date_from'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET date_from = '" . $this->db->escape($data['date_from']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['date_to'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET date_to = '" . $this->db->escape($data['date_to']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
	
		foreach ($data['events_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "events_description SET event_id = '" . (int)$event_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "',  mindescription = '" . $this->db->escape($value['mindescription']) . "',  description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "',  meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}
	
		if (isset($data['events_store'])) {
			foreach ($data['events_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "events_to_store SET event_id = '" . (int)$event_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'event_id=" . (int)$event_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('events');
	}

	public function editEvents($event_id, $data) {
		//var_dump($data);
			$this->db->query("UPDATE " . DB_PREFIX . "events SET status = '" . (int)$data['status'] . "', events_adminNote='" . $this->db->escape($data['events_adminNote']) . "' WHERE event_id = '" . (int)$event_id . "'");
			
		if (isset($data['product'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET product_id = '" . $this->db->escape($data["product"][0]) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		else{
			$this->db->query("UPDATE " . DB_PREFIX . "events SET product_id = '0' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET image = '" . $this->db->escape($data['image']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['date_from'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET date_from = '" . $this->db->escape($data['date_from']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['date_to'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET date_to = '" . $this->db->escape($data['date_to']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "events_description WHERE event_id = '" . (int)$event_id . "'");
	
		foreach ($data['events_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "events_description SET event_id = '" . (int)$event_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', mindescription = '" . $this->db->escape($value['mindescription']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "',  meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "events_to_store WHERE event_id = '" . (int)$event_id . "'");
	
		if (isset($data['events_store'])) {		
			foreach ($data['events_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "events_to_store SET event_id = '" . (int)$event_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'event_id=" . (int)$event_id . "'");
	
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'event_id=" . (int)$event_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('events');
	}

	public function deleteEvents($event_id) { 
		$this->db->query("DELETE FROM " . DB_PREFIX . "events WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "events_description WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "events_to_store WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'event_id=" . (int)$event_id . "'");
	
		$this->cache->delete('events');
	}

	public function getEventsList($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "events n LEFT JOIN " . DB_PREFIX . "events_description nd ON (n.event_id = nd.event_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sort_data = array(
				'nd.title',
				'n.date_to'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY nd.title";
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
			$events_data = $this->cache->get('events.' . (int)$this->config->get('config_language_id'));

			if (!$events_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "events n LEFT JOIN " . DB_PREFIX . "events_description nd ON (n.event_id = nd.event_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY nd.title");

				$events_data = $query->rows;

				$this->cache->set('events.' . (int)$this->config->get('config_language_id'), $events_data);
			}

			return $events_data;
		}
	}

	public function getEventsStory($event_id) { 
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE query = 'event_id=" . (int)$event_id . "') AS keyword FROM " . DB_PREFIX . "events n LEFT JOIN " . DB_PREFIX . "events_description nd ON (n.event_id = nd.event_id) WHERE n.event_id = '" . (int)$event_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
		return $query->row;
	}

	public function getEventsDescriptions($event_id) { 
		$events_description_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "events_description WHERE event_id = '" . (int)$event_id . "'");
	
		foreach ($query->rows as $result) {
			$events_description_data[$result['language_id']] = array(
				'title'            	=> $result['title'],
				'mindescription'    => $result['mindescription'],
				'description'      	=> $result['description'],
				'meta_title'        => $result['meta_title'],
				'meta_h1'           => $result['meta_h1'],
				'meta_description' 	=> $result['meta_description'],
				'meta_keyword'		=> $result['meta_keyword'],
			);
		}
	
		return $events_description_data;
	}

	public function getEventsStores($event_id) { 
		$eventspage_store_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "events_to_store WHERE event_id = '" . (int)$event_id . "'");
		
		foreach ($query->rows as $result) {
			$eventspage_store_data[] = $result['store_id'];
		}
	
		return $eventspage_store_data;
	}

	public function getTotalEvents() { 

     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "events");
	
		return $query->row['total'];
	}

	public function setEventsListUrl($url) {
		//var_dump($url);
		if ($url) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'extension/module/event_list'");
			
			if ($query) {
				$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE query = 'extension/module/event_list'");
				$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `query` = 'extension/module/event_list', `keyword` = '" . $this->db->escape($url['eventslist_url']) . "'");
			}else{
				$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `query` = 'extension/module/event_list', `keyword` = '" . $this->db->escape($url['eventslist_url']) . "'");
			}
		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'extension/module/event'");
			if ($query) {
				$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE query = 'extension/module/event'");
				$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `query` = 'extension/module/event', `keyword` = '" . $this->db->escape($url['events_url']) . "'");
			}else{
				$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `query` = 'extension/module/event', `keyword` = '" . $this->db->escape($url['events_url']) . "'");
			}
			
		} 
		
	}

	public function getEventsListUrl() {
		$query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE query = 'extension/module/event' LIMIT 1");
		$data[0]=$query->row;
		
		$query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE query = 'extension/module/event_list' LIMIT 1");
		$data[1]=$query->row;
		
		return $data;
	}
	
	public function getValue() {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "event_value WHERE `event_value_id`=1");

		return $query->row;
	}
	
	public function getValue2() {
		
		$query = $this->db->query("SELECT value2 FROM " . DB_PREFIX . "event_value WHERE `event_value_id`=1");
		$a=$query->row;

		return $a['value2'];
	}
	
	public function copyEvent($event_id) {

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "events WHERE event_id = '" . (int)$event_id . "'");

		if ($query->num_rows) {
			$data = $query->row;
			
			$data['keyword'] = '';
			$data['status'] = '0';

			$data['events_description'] = $this->getEventsDescriptions($event_id);
			$data['license'] = $this->getValue2();
			$data['events_store'] = $this->getEventsStores($event_id);

			$this->addEvents($data);
		}
	}
}
?>