<?php
class ModelExtensionModuleEvents extends Model {

	public function addEvents($data) {
		//var_dump($data);
		$this->db->query("UPDATE " . DB_PREFIX . "gen_events_setting SET value2 = '" . $this->db->escape($data['license']) . "' WHERE event_value_id = '1'");
		 
		$this->db->query("INSERT INTO " . DB_PREFIX . "gen_events SET status = '" . (int)$data['status'] . "', date_from = '" . $this->db->escape($data['date_from']) . "', date_to = '" . $this->db->escape($data['date_to']) . "', sort_order = '" . (int)$data['sort_order'] . "', price = '" . (float)$data['event_price'] . "', additional_field = '" . $this->db->escape($data['additional_field']) . "', time_to = '" . $this->db->escape($data['time_to']) . "', time_from = '" . $this->db->escape($data['time_from']) . "', color = '" . $this->db->escape($data['event_color']) . "'");
	
		$event_id = $this->db->getLastId();
	
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET image = '" . $this->db->escape($data['image']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['product'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET product_id = '" . $this->db->escape($data["product"][0]) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		else{
			$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET product_id = '0' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['doctor'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET doctor_id = '" . $this->db->escape($data["doctor"][0]) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		else{
			$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET product_id = '0' WHERE event_id = '" . (int)$event_id . "'");
		}
	
		foreach ($data['events_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "gen_events_description SET event_id = '" . (int)$event_id . "', language_id = '" . (int)$language_id . "', title1 = '" . $this->db->escape($value['title1']) . "', title = '" . $this->db->escape($value['title']) . "', title3 = '" . $this->db->escape($value['title3']) . "',  mindescription = '" . $this->db->escape($value['mindescription']) . "',  description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "',  meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}
	
		if (isset($data['events_store'])) {
			foreach ($data['events_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "gen_events_to_store SET event_id = '" . (int)$event_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'event_id=" . (int)$event_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('events');
	}

	public function editEvents($event_id, $data) {
		//var_dump($data);
		$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET status = '" . (int)$data['status'] . "', date_from = '" . $this->db->escape($data['date_from']) . "', date_to = '" . $this->db->escape($data['date_to']) . "', sort_order = '" . (int)$data['sort_order'] . "', color = '" . $this->db->escape($data['event_color']) . "', price = '" . (float)$data['event_price'] . "', additional_field = '" . $this->db->escape($data['additional_field']) . "', time_to = '" . $this->db->escape($data['time_to']) . "', time_from = '" . $this->db->escape($data['time_from']) . "' WHERE event_id = '" . (int)$event_id . "'");
		
		if (isset($data['product'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET product_id = '" . $this->db->escape($data["product"][0]) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		else{
			$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET product_id = '0' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['doctor'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET doctor_id = '" . $this->db->escape($data["doctor"][0]) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		else{
			$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET product_id = '0' WHERE event_id = '" . (int)$event_id . "'");
		}

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET image = '" . $this->db->escape($data['image']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_events_description WHERE event_id = '" . (int)$event_id . "'");
	
		foreach ($data['events_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "gen_events_description SET event_id = '" . (int)$event_id . "', language_id = '" . (int)$language_id . "', title1 = '" . $this->db->escape($value['title1']) . "', title = '" . $this->db->escape($value['title']) . "', title3 = '" . $this->db->escape($value['title3']) . "', mindescription = '" . $this->db->escape($value['mindescription']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "',  meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_events_to_store WHERE event_id = '" . (int)$event_id . "'");
	
		if (isset($data['events_store'])) {		
			foreach ($data['events_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "gen_events_to_store SET event_id = '" . (int)$event_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'event_id=" . (int)$event_id . "'");
	
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'event_id=" . (int)$event_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('events');
	}

	public function deleteEvents($event_id) { 
		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_events WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_events_description WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_events_to_store WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'event_id=" . (int)$event_id . "'");
	
		$this->cache->delete('events');
	}

	public function getEventsList($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "gen_events n LEFT JOIN " . DB_PREFIX . "gen_events_description nd ON (n.event_id = nd.event_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

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
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_events n LEFT JOIN " . DB_PREFIX . "gen_events_description nd ON (n.event_id = nd.event_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY nd.title");

				$events_data = $query->rows;

				$this->cache->set('events.' . (int)$this->config->get('config_language_id'), $events_data);
			}

			return $events_data;
		}
	}

	public function getEventsStory($event_id) { 
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'event_id=" . (int)$event_id . "') AS keyword FROM " . DB_PREFIX . "gen_events n LEFT JOIN " . DB_PREFIX . "gen_events_description nd ON (n.event_id = nd.event_id) WHERE n.event_id = '" . (int)$event_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
		return $query->row;
	}

	public function getEventsDescriptions($event_id) { 
		$events_description_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_events_description WHERE event_id = '" . (int)$event_id . "'");
	
		foreach ($query->rows as $result) {
			$events_description_data[$result['language_id']] = array(
				'title1'            => $result['title1'],
				'title'            	=> $result['title'],
				'title3'            => $result['title3'],
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
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_events_to_store WHERE event_id = '" . (int)$event_id . "'");
		
		foreach ($query->rows as $result) {
			$eventspage_store_data[] = $result['store_id'];
		}
	
		return $eventspage_store_data;
	}

	public function getTotalEvents() { 

     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gen_events");
	
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
			
		} 
		
		$this->db->query("UPDATE " . DB_PREFIX . "gen_events_setting SET event_share = '" . (int)$data['event_share'] . "', firstDay = '" . (int)$data['event_firstDay'] . "', dayMaxEvents = '" . (int)$data['dayMaxEvents'] . "' WHERE event_value_id = '1'");
		
	}

	public function getEventsSetting() {
		$query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event' LIMIT 1");
		$data[0]=$query->row;
		
		$query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event_list' LIMIT 1");
		$data[1]=$query->row;
		
		$query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event_detail' LIMIT 1");
		$data[2]=$query->row;
		
		$query = $this->db->query("SELECT event_share FROM " . DB_PREFIX . "gen_events_setting WHERE event_value_id ='1'");
		$data[3]=$query->row;
		
		$query = $this->db->query("SELECT firstDay FROM " . DB_PREFIX . "gen_events_setting WHERE event_value_id ='1'");
		$data[4]=$query->row;
		
		$query = $this->db->query("SELECT dayMaxEvents FROM " . DB_PREFIX . "gen_events_setting WHERE event_value_id ='1'");
		$data[5]=$query->row;
		
		return $data;
	}
	
	public function getValue() {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_events_setting WHERE `event_value_id`=1");

		return $query->row;
	}
	public function getValue2() {
		
		$query = $this->db->query("SELECT value2 FROM " . DB_PREFIX . "gen_events_setting WHERE `event_value_id`=1");
		$a=$query->row;

		return $a['value2'];
	}
	
	public function copyEvent($event_id) {

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gen_events WHERE event_id = '" . (int)$event_id . "'");
		if ($query->num_rows) {
			$data = $query->row;
			
			$data['keyword'] = '';
			$data['status'] = '0';
			
			$data['event_color'] = $query->row["color"];
			$data['event_price'] = $query->row["price"];
			$data['product'][0] = $query->row["product_id"];
			$data['doctor'][0] = $query->row["doctor_id"];

			$data['events_description'] = $this->getEventsDescriptions($event_id);
			$data['license'] = $this->getValue2();
			$data['events_store'] = $this->getEventsStores($event_id);

			$this->addEvents($data);
		}
	}
	
	public function getDoctors($data = array()) {

			$sql = "SELECT * FROM " . DB_PREFIX . "doctor d LEFT JOIN " . DB_PREFIX . "doctor_description dd ON (d.doctor_id = dd.doctor_id)";
			
			$sql .= " WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND dd.title LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND d.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_image']) && !is_null($data['filter_image'])) {
			if ($data['filter_image'] == 1) {
				$sql .= " AND (d.image IS NOT NULL AND d.image <> '' AND d.image <> 'no_image.png')";
			} else {
				$sql .= " AND (d.image IS NULL OR d.image = '' OR d.image = 'no_image.png')";
			}
		}

		$sql .= " GROUP BY d.doctor_id";

		$sort_data = array(
			'dd.title',
			'd.status',
			'd.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY dd.title";
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
	}
	
	public function getDoctor($doctor_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "doctor d LEFT JOIN " . DB_PREFIX . "doctor_description dd ON (d.doctor_id = dd.doctor_id)  WHERE d.doctor_id = '" . (int)$doctor_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
		public function editStatusEvent($event_id,$status) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET status = '" . (int)$status . "' WHERE event_id = '" . (int)$event_id . "'");
		
	}
}
?>