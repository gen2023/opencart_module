<?php
class ModelExtensionModuleEvents extends Model {

	public function addEvents($data) {
		$this->db->query("UPDATE " . DB_PREFIX . "events_setting SET value2 = '" . $this->db->escape($data['license']) . "' WHERE event_value_id = '1'");
		 
		$this->db->query("INSERT INTO " . DB_PREFIX . "events SET status = '" . (int)$data['status'] . "',alternativeLink = '" . $this->db->escape($data['alternativeLink']). "', sort_order = '" . (int)$data['sort_order'] . "'");
	
		$event_id = $this->db->getLastId();
	
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET image = '" . $this->db->escape($data['image']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['event_color'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET color = '" . $this->db->escape($data['event_color']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
	
		if (isset($data['date_from'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET date_from = '" . $this->db->escape($data['date_from']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['date_to'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET date_to = '" . $this->db->escape($data['date_to']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}

		if (isset($data['time_from'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET time_from = '" . $this->db->escape($data['time_from']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['time_to'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET time_to = '" . $this->db->escape($data['time_to']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['product'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET product_id = '" .  $this->db->escape($data["product"][0]) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['product_id'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET product_id = '" .  $this->db->escape($data["product_id"]) . "' WHERE event_id = '" . (int)$event_id . "'");
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
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'event_id=" . (int)$event_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('events');
	}

	public function editEvents($event_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "events SET status = '" . (int)$data['status'] . "',alternativeLink = '" . $this->db->escape($data['alternativeLink']). "', sort_order = '" . (int)$data['sort_order'] . "' WHERE event_id = '" . (int)$event_id . "'");
		
		if (isset($data['product'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET product_id = '" . $this->db->escape($data["product"][0]) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		else{
			$this->db->query("UPDATE " . DB_PREFIX . "events SET product_id = '0' WHERE event_id = '" . (int)$event_id . "'");
		}

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET image = '" . $this->db->escape($data['image']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['event_color'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET color = '" . $this->db->escape($data['event_color']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['date_from'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET date_from = '" . $this->db->escape($data['date_from']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['date_to'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET date_to = '" . $this->db->escape($data['date_to']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['time_from'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET time_from = '" . $this->db->escape($data['time_from']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['time_to'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "events SET time_to = '" . $this->db->escape($data['time_to']) . "' WHERE event_id = '" . (int)$event_id . "'");
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
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'event_id=" . (int)$event_id . "'");
	
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'event_id=" . (int)$event_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('events');
	}

	public function deleteEvents($event_id) { 
		$this->db->query("DELETE FROM " . DB_PREFIX . "events WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "events_description WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "events_to_store WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'event_id=" . (int)$event_id . "'");
	
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
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'event_id=" . (int)$event_id . "') AS keyword FROM " . DB_PREFIX . "events n LEFT JOIN " . DB_PREFIX . "events_description nd ON (n.event_id = nd.event_id) WHERE n.event_id = '" . (int)$event_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
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

	public function setEventsSetting($data) {

		if ($data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event_list'");
			
			if ($query) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event_list'");
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/event_list', `keyword` = '" . $this->db->escape($data['eventslist_url']) . "'");
			}else{
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/event_list', `keyword` = '" . $this->db->escape($data['eventslist_url']) . "'");
			}
		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event'");
			if ($query) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event'");
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/event', `keyword` = '" . $this->db->escape($data['events_url']) . "'");
			}else{
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/event', `keyword` = '" . $this->db->escape($data['events_url']) . "'");
			}
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event_detail'");
			
			if ($query) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event_detail'");
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/event_detail', `keyword` = '" . $this->db->escape($data['eventsDetail_url']) . "'");
			}else{
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/event_detail', `keyword` = '" . $this->db->escape($data['eventsDetail_url']) . "'");
			}

			if (isset($data['rightMenu'])){			
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = 'setting_module_genEvents' AND `key`='rightMenu'");
			
				if ($query) {
					$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = 'setting_module_genEvents' AND `key`='rightMenu'");
				}
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'setting_module_genEvents', `key`='rightMenu', `value` = '" . $this->db->escape(json_encode($data['rightMenu'])) . "', `serialized`='0'");

			} 
			
		} 
		if (isset($data['rightColumnMenu'])){
		$rightColumnMenu = implode(",", $data['rightColumnMenu']);
		}
		else
		{
			$rightColumnMenu = '';
		}
		if (isset($data['event_initialView'])){$event_initialView=$data['event_initialView'];}else {$event_initialView='dayGridMonth';}
		
		$this->db->query("UPDATE " . DB_PREFIX . "events_setting SET event_share = '" . (int)$data['event_share'] . "', firstDay = '" . (int)$data['event_firstDay'] . "', dayMaxEvents = '" . (int)$data['dayMaxEvents'] . "', rightColumnMenu = '" . $this->db->escape($rightColumnMenu) . "', initialView = '" . $this->db->escape($event_initialView) . "' WHERE event_value_id = '1'");
		
	}

	public function getEventsSetting() {
		$results_url= array();
		$query = $this->db->query("SELECT keyword as event FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event' LIMIT 1");
		$results_url['event']=$query->row['event'];

		$query = $this->db->query("SELECT keyword as list FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event_list' LIMIT 1");
		$results_url['list']=$query->row['list'];
		
		$query = $this->db->query("SELECT keyword as detail FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event_detail' LIMIT 1");
		$results_url['detail']=$query->row['detail'];
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "events_setting WHERE event_value_id ='1'");
		$results_setting=$query->row;
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = 'setting_module_genEvents' AND `key`='rightMenu'");
		
		if ($query->num_rows!=0){
		
			foreach ($query->rows as $result) {			
				$results_settingNameRightMenu[$result['key']] = json_decode($result['value'], true);			
			}
		} else{$results_settingNameRightMenu=array('rightMenu'=>'');}
		
		return array_merge($results_url, $results_setting,$results_settingNameRightMenu);
	}
	
	public function getValue() {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "events_setting WHERE `event_value_id`=1");

		return $query->row;
	}
	public function getValue2() {
		
		$query = $this->db->query("SELECT value2 FROM " . DB_PREFIX . "events_setting WHERE `event_value_id`=1");
		$a=$query->row;

		return $a['value2'];
	}
	
	public function copyEvent($event_id) {

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "events WHERE event_id = '" . (int)$event_id . "'");
		if ($query->num_rows) {
			$data = $query->row;
			
			$data['keyword'] = '';
			$data['status'] = '0';
			
			$data['event_color'] = $query->row["color"];

			$data['events_description'] = $this->getEventsDescriptions($event_id);
			$data['license'] = $this->getValue2();
			$data['events_store'] = $this->getEventsStores($event_id);

			$this->addEvents($data);
		}
	}
}
?>