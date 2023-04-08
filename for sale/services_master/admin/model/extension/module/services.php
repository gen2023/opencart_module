<?php
class ModelExtensionModuleServices extends Model {
	
		public function createServices()
	{
					
		$res0 = $this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."services_master'");
		
		if($res0->num_rows == 0){			
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `".DB_PREFIX."services_master` (
				  `service_id` int(11),
				  `language_id` int(11),
				  `service_name` varchar(255)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
			");			
		}		
	}
	
	public function getServices() {
		$service_data = array();

		$service_query = $this->db->query("SELECT DISTINCT service_id FROM " . DB_PREFIX . "services_master");

		foreach ($service_query->rows as $service) {
			$service_name_data = array();

			$service_name_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "services_master WHERE service_id = '" . (int)$service['service_id'] . "'");

			foreach ($service_name_query->rows as $service_name) {
				$service_name_data[$service_name['language_id']] = array('name' => $service_name['service_name']);
			}

			$service_data[] = array(
				'service_id'          => $service['service_id'],
				'service_name' => $service_name_data
			);
		}

		return $service_data;
	}

	public function addServices($data) {
		
		if (!isset($data['service'])){
			$this->db->query("DELETE FROM " . DB_PREFIX . "services_master");
		}
		else{
		$service_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "services_master");
		
		if (!$service_query->rows)
		{foreach ($data['service'] as $service_id=>$service) {
	foreach ($service["service_name"] as $language_id => $service_name) { 
					$this->db->query("INSERT INTO " . DB_PREFIX . "services_master SET service_id='" . (int)$service_id . "',language_id = '" . (int)$language_id . "', service_name = '" . $this->db->escape($service_name['name']) . "'");
		}}} else{ 
					$this->db->query("DELETE FROM " . DB_PREFIX . "services_master");
					foreach ($data['service'] as $service_id=>$service) {
					foreach ($service['service_name'] as $language_id => $service_name) { 
					$this->db->query("INSERT INTO " . DB_PREFIX . "services_master SET service_id='" . (int)$service_id . "', language_id = '" . (int)$language_id . "', service_name = '" . $this->db->escape($service_name['name']) . "'");
		}}}}
			
		
		
		
	}
}
