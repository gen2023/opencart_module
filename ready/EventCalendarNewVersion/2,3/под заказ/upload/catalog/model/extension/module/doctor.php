<?php
class ModelExtensionModuleDoctor extends Model {

		public function getDoctors($data = array()) {
		
		if ($data) {
	
				$sql= "SELECT d.doctor_id, status, image, dd.language_id, dd.title, dd.description, dd.post FROM " . DB_PREFIX . "doctor d LEFT JOIN " . DB_PREFIX . "doctor_description dd ON (d.doctor_id = dd.doctor_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "' and status = '1' ";

				$sort_data = array(
					'dd.title'
				);

				if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
					$sql .= " ORDER BY " . $data['sort'];
				} else {
					$sql .= " ORDER BY dd.title";
				}

				if (isset($data['order']) && ($data['order'] == 'ASC')) {
					$sql .= " ASC";
				} else {
					$sql .= " DESC";
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
//var_dump($query->rows);
				return $query->rows;				
		} else {
			$doctor_data = $this->cache->get('doctor_description.' . (int)$this->config->get('config_language_id'));

			if (!$doctor_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "doctor d LEFT JOIN " . DB_PREFIX . "doctor_description dd ON (d.doctor_id = dd.doctor_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY dd.title");

				$event_data = $query->rows;

				$this->cache->set('doctor_description.' . (int)$this->config->get('config_language_id'), $event_data);
			}
//var_dump($event_data);
			return $event_data;
		}
	}

	public function getTotalDoctors() {
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "doctor WHERE `status`=1");

		return $query->row['total'];
	}
	
	public function getDoctor($doctor_id) {
		
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "doctor d LEFT JOIN " . DB_PREFIX . "doctor_description dd ON (d.doctor_id = dd.doctor_id) WHERE d.doctor_id = '" . (int)$doctor_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "' and status = '1' ");
		
		if ($query->num_rows) {
			return array(
				'doctor_id'         => $query->row['doctor_id'],
				'image'            => $query->row['image'],
				'title'           => $query->row['title'],
				'description'      => $query->row['description'],
				'post'      	   => $query->row['post'],
				'status'           => $query->row['status']
			);
		} else {
			return false;
		}
	}
	
	public function getDoctorEvent($doctor_id) {
		
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gen_events e LEFT JOIN " . DB_PREFIX . "gen_events_description ed ON (e.event_id = ed.event_id) WHERE e.doctor_id = '" . (int)$doctor_id . "' AND ed.language_id = '" . (int)$this->config->get('config_language_id') . "' and status = '1' ");
	//var_dump($query->rows);	
		if ($query->num_rows) {
			return $query->rows;
		} else {
			return false;
		}
	}
}