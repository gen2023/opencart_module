<?php
class ModelExtensionModuleServices extends Model {
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
}