<?php
class ModelExtensionModuleEvent extends Model {

	public function getEvent($event_id) {
		
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "events i LEFT JOIN " . DB_PREFIX . "events_description id ON (i.event_id = id.event_id) WHERE i.event_id = '" . (int)$event_id . "' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' and status = '1' ");
		
		if ($query->num_rows) {
			return array(
				'event_id'         => $query->row['event_id'],
				'image'            => $query->row['image'],
				'product_id'       => $query->row['product_id'],
				'date_from'        => $query->row['date_from'],
				'date_to'          => $query->row['date_to'],
				'time_from'        => $query->row['time_from'],
				'time_to'          => $query->row['time_to'],
				'title'            => $query->row['title'],
				'mindescription'   => $query->row['mindescription'],
				'description'      => $query->row['description'],
				'status'           => $query->row['status']
			);
		} else {
			return false;
		}
	}

    public function getEvents($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "events i LEFT JOIN " . DB_PREFIX . "events_description id ON (i.event_id = id.event_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' and status = '1' ORDER BY sort_order";

			/*$sort_data = array(
				'id.title',
				'i.date_to',
				'i.date_from'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
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
			}*/

			$query = $this->db->query($sql);

			return $query->rows;
		} else {
			$event_data = $this->cache->get('event.' . (int)$this->config->get('config_language_id'));

			if (!$event_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "event i LEFT JOIN " . DB_PREFIX . "events_description id ON (i.event_id = id.event_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");

				$event_data = $query->rows;

				$this->cache->set('event.' . (int)$this->config->get('config_language_id'), $event_data);
			}

			return $event_data;
		}
	}

	public function getTotalEvents() {
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "events WHERE `status`=1");

		return $query->row['total'];
	}
	
	public function getEventSetting() {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "events_setting");
		$query2 = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = 'setting_module_genEvents' AND `key`='rightMenu'");
		
		foreach ($query2->rows as $result) {			
			$results_settingNameRightMenu[$result['key']] = json_decode($result['value'], true);			
		}

		return array_merge($query->row,$results_settingNameRightMenu);
	}
}