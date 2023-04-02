<?php
	class ModelCatalogAjaxTestimonial extends Model {
		public function getajaxtestimonial($ajaxtestimonial_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ajaxtestimonial t LEFT JOIN " . DB_PREFIX . "ajaxtestimonial_description td ON (t.ajaxtestimonial_id = td.ajaxtestimonial_id) WHERE t.ajaxtestimonial_id = '" . (int)$ajaxtestimonial_id . "' AND td.language_id = '" . (int)$this->config->get('config_language_id') . "' AND t.status = '1'");
			
			return $query->rows;
		}
		
		public function getajaxtestimonialImages($ajaxtestimonial_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ajaxtestimonial_images where ajaxtestimonial_id=" . $ajaxtestimonial_id);
			
			return $query->rows;
		}
		
		public function getajaxtestimonials($data = array()) {
			$sql = "SELECT * FROM " . DB_PREFIX . "ajaxtestimonial t LEFT JOIN " . DB_PREFIX . "ajaxtestimonial_description td ON (t.ajaxtestimonial_id = td.ajaxtestimonial_id) WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "' AND t.status = '1' AND t.reply = '0'";
			
			$sort_data = array(
			't.rating',
			't.date_added'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
				} elseif ($data['random'] == true){
				$sql .= " ORDER BY rand()";	
				}else{
				$sql .= " ORDER BY t.date_added";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
				} elseif ($data['random'] == true){
				$sql .= "";
				}else{
				$sql .= " ASC";
			}
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			
			$query = $this->db->query($sql);
			return $query->rows;
		}
		
		public function getajaxtestimonials_module($start = 0, $limit = 20, $random = false) {
			if ($random == false)
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ajaxtestimonial t LEFT JOIN " . DB_PREFIX . "ajaxtestimonial_description td ON (t.ajaxtestimonial_id = td.ajaxtestimonial_id) WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "' AND t.status = '1' AND t.reply = '0' ORDER BY t.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
			else
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ajaxtestimonial t LEFT JOIN " . DB_PREFIX . "ajaxtestimonial_description td ON (t.ajaxtestimonial_id = td.ajaxtestimonial_id) WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "' AND t.status = '1' AND t.reply = '0' ORDER BY RAND() LIMIT " . (int)$start . "," . (int)$limit);
			
			return $query->rows;
		}
		
		public function getajaxtestimonialsReply($id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ajaxtestimonial t LEFT JOIN " . DB_PREFIX . "ajaxtestimonial_description td ON (t.ajaxtestimonial_id = td.ajaxtestimonial_id) WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "' AND t.status = '1' AND t.reply = '1' AND t.parent_testimonial_id = '" . $id. "' ORDER BY t.date_added DESC");
			
			return $query->rows;
		}
		
		public function getTotalajaxtestimonials() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ajaxtestimonial t LEFT JOIN " . DB_PREFIX . "ajaxtestimonial_description td ON (t.ajaxtestimonial_id = td.ajaxtestimonial_id) WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "' AND t.reply = '0' AND t.status = '1'");
			
			return $query->row['total'];
		}
		
		public function addajaxtestimonial($data, $status) {
				
			$this->db->query("INSERT INTO " . DB_PREFIX . "ajaxtestimonial SET status = '".$status."', rating = '".$this->db->escape($data['rating'])."', name='".$this->db->escape($data['name'])."', email='".$this->db->escape($data['email'])."', phone='".$this->db->escape($data['phone'])."', date_added = NOW()");
			
			$ajaxtestimonial_id = $this->db->getLastId(); 
			
			$results = $this->db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name"); 
			
			foreach ($results->rows as $result) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ajaxtestimonial_description SET ajaxtestimonial_id = '" . (int)$ajaxtestimonial_id . "', language_id = '".(int)$result['language_id']."', description = '" . $this->db->escape($data['description']) . "'");
			}
		}
		
		public function addajaxtestimonialReply($data, $status, $reply_id = false) {
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "ajaxtestimonial SET status = '".$status."', reply = '1', name='".$this->db->escape($data['name'])."', parent_testimonial_id='".(int)$reply_id."', date_added = NOW()");
			
			$ajaxtestimonial_id = $this->db->getLastId(); 
			
			$results = $this->db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name"); 
			
			foreach ($results->rows as $result) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ajaxtestimonial_description SET ajaxtestimonial_id = '" . (int)$ajaxtestimonial_id . "', language_id = '".(int)$result['language_id']."', description = '" . $this->db->escape($data['description']) . "'");
			}	
		}
		
		public function updateAjaxtestimonialVote($ajaxtestimonial_id, $vote) {
			if ($vote == 'likes'){
				$sql = "UPDATE " . DB_PREFIX . "ajaxtestimonial SET likes = likes+1 WHERE ajaxtestimonial_id = " . (int)$ajaxtestimonial_id;
				}else{
				$sql = "UPDATE " . DB_PREFIX . "ajaxtestimonial SET unlikes = unlikes+1 WHERE reply = '0' AND ajaxtestimonial_id = " . (int)$ajaxtestimonial_id;
			}
			
			$this->db->query($sql);
		}	
		
	}
?>