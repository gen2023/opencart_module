<?php
class ModelExtensionModuleEvent extends Model {

		public function getEvent($event_id) {
		
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gen_events i LEFT JOIN " . DB_PREFIX . "gen_events_description id ON (i.event_id = id.event_id) WHERE i.event_id = '" . (int)$event_id . "' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' and status = '1' ");
		
		if ($query->num_rows) {
			return array(
				'event_id'         => $query->row['event_id'],
				'image'            => $query->row['image'],
				'product_id'       => $query->row['product_id'],
				'doctor_id'        => $query->row['doctor_id'],
				'date_from'        => $query->row['date_from'],
				'date_to'          => $query->row['date_to'],
				'title1'           => $query->row['title1'],
				'title'            => $query->row['title'],
				'title3'           => $query->row['title3'],
				'additional_field' => $query->row['additional_field'],
				'mindescription'   => $query->row['mindescription'],
				'description'      => $query->row['description'],
				'time_to'      	   => $query->row['time_to'],
				'time_from'        => $query->row['time_from'],
				'price'      	   => $query->row['price'],
				'status'           => $query->row['status']
			);
		} else {
			return false;
		}
	}

    public function getEvents($data = array()) {
		if ($data) {
			/*$sql_temp="SELECT doctor_id FROM " . DB_PREFIX . "gen_events WHERE doctor_id=0 or doctor_id=NULL and status = '1'";
			//var_dump($this->db->query($sql_temp)->rows);
			//если невыбран доктор событие не отображается
			if ($this->db->query($sql_temp)->rows){
				$sql_1= "SELECT i.event_id, status, image, color, price, product_id, date_to, date_from, time_to, time_from, sort_order, id.language_id, id.title as event_title, mindescription, id.description as event_description, doctor_id FROM " . DB_PREFIX . "gen_events i LEFT JOIN " . DB_PREFIX . "gen_events_description id ON (i.event_id = id.event_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' and status = '1' and doctor_id=0 or doctor_id=NULL";
			}
			else{$sql_1='';}*/
			
				$sql= "SELECT i.event_id, status, image, color, price, product_id, i.doctor_id, date_to, date_from, time_to, time_from, sort_order, id.language_id, d.language_id, id.title as event_title, mindescription, id.description as event_description, d.title as doctor_title, d.description as doctor_description, post FROM " . DB_PREFIX . "gen_events i LEFT JOIN " . DB_PREFIX . "gen_events_description id ON (i.event_id = id.event_id) join " . DB_PREFIX . "doctor_description d ON (i.doctor_id = d.doctor_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' and d.language_id = '" . (int)$this->config->get('config_language_id') . "' and status = '1' ";

				$sort_data = array(
					'id.title',
					'i.date_to',
					'i.date_from',
					'd.title'
				);

				if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
					$sql .= " ORDER BY " . $data['sort'];
					//$sql_1 .= " ORDER BY " . $data['sort'];
				} else {
					$sql .= " ORDER BY i.date_to";
					//$sql_1 .= " ORDER BY id.title";
				}

				if (isset($data['order']) && ($data['order'] == 'ASC')) {
					$sql .= " DESC";
					//$sql_1 .= " DESC";
				} else {
					$sql .= " ASC";
					//$sql_1 .= " ASC";
				}

				if (isset($data['start']) || isset($data['limit'])) {
					if ($data['start'] < 0) {
						$data['start'] = 0;
					}

					if ($data['limit'] < 1) {
						$data['limit'] = 20;
					}

					$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
					//$sql_1 .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				}

				//$query1 = $this->db->query($sql);
				//$query2 = $this->db->query($sql_1);
				$query = $this->db->query($sql);
				//$query = $query1->rows + $query2->rows;
//var_dump($sql);
				return $query->rows;				
		} else {
			$event_data = $this->cache->get('gen_events.' . (int)$this->config->get('config_language_id'));

			if (!$event_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_events i LEFT JOIN " . DB_PREFIX . "gen_events_description id ON (i.event_id = id.event_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.date_to");

				$event_data = $query->rows;

				$this->cache->set('gen_events.' . (int)$this->config->get('config_language_id'), $event_data);
			}
//var_dump($event_data);
			return $event_data;
		}
	}

	public function getTotalEvents() {
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gen_events WHERE `status`=1");

		return $query->row['total'];
	}
	
	public function getEventSetting() {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_events_setting");

		return $query->row;
	}
	
	public function getDoctor($doctor_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "doctor d LEFT JOIN " . DB_PREFIX . "doctor_description dd ON (d.doctor_id = dd.doctor_id)  WHERE d.doctor_id = '" . (int)$doctor_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows) {
			return array(
				'doctor_id'         => $query->row['doctor_id'],
				'image'            	=> $query->row['image'],
				'title'            	=> $query->row['title'],
				'post'   			=> $query->row['post']
			);
		} else {
			return false;
		}
	}
}