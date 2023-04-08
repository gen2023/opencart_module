<?php
class ModelExtensionModuleGentestimonials extends Model {
	public function getTestimonials() {

		$sql = "SELECT * FROM " . DB_PREFIX . "gentestimonial i LEFT JOIN " . DB_PREFIX . "gentestimonial_description id ON (i.testimonial_id = id.testimonial_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' and status = '1'  ORDER BY sort_order";

		$query = $this->db->query($sql);

		return $query->rows;
	}
		
}